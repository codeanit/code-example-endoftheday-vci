<?php
/**
 * VERICHECK INC CONFIDENTIAL
 * 
 * Vericheck Incorporated 
 * All Rights Reserved.
 * 
 * NOTICE: 
 * All information contained herein is, and remains the property of 
 * Vericheck Inc, if any.  The intellectual and technical concepts 
 * contained herein are proprietary to Vericheck Inc and may be covered 
 * by U.S. and Foreign Patents, patents in process, and are protected 
 * by trade secret or copyright law. Dissemination of this information 
 * or reproduction of this material is strictly forbidden unless prior 
 * written permission is obtained from Vericheck Inc.
 *
 * @copyright VeriCheck, Inc. 
 * @version $$Id: Item.php 1384 2013-08-22 11:05:53Z anit $$
 */

App::uses('SettlementWarehouse', 'Model');
App::uses('CustomerTransaction', 'Model');

/**
 * SettlementWarehouseDebitsWithEmbeddedFee Model
 *
 * Fetch the Query to Insert into warehouse.settlement_warehouse for Reversed transaction
 */
class SettlementWarehouseDebitsWithEmbeddedFee extends SettlementWarehouse {
	
	public function __construct($id = false, $table = null, $ds = null) {
		$this->CustomerTransaction = new CustomerTransaction();

		parent::__construct($id, $table, $ds);
		
	}

/**
 * Fetch the Query to Insert into warehouse.settlement_warehouse for Debits with Embedded fees transaction
 * 
 * @param array $transData Fetched Transaction data from warehouse_customer.transactions
 * @return array $sql Query.
 */
	private function __manageDebitsWithEmbeddedFeesQuery($transData) {
		$sql = null;
		$vciDate = new VciDate();

		$actIdealDate = $transData['CustomerTransaction']['origination_ideal_date'];
		$setId = $transData['CustomerTransaction']['id'];

		$fundTime = $transData['Merchant']['funding_time'];
		$setIdealDate = $this->_getSettlementIdealDate($fundTime,$actIdealDate,$fundTime);
		$setScheduledDate = $setIdealDate;
		$setActualDate = 'null';

		$mer_feePostAmt = $transData['Merchant']['feePostAmt'];
		$mer_feePostDiscount = $transData['Merchant']['feePostDiscount'];
		$mer_originalAmount = $transData['CustomerTransaction']['amount'];

		$bankersRound = $vciDate->bround($mer_originalAmount * ($mer_feePostDiscount/100),2);
		$mer_feeAmount = $mer_feePostAmt + $bankersRound;
		$mer_nonFeeAmt = $mer_originalAmount - $mer_feeAmount;

		$setMerchantId = $transData['MerchantFee']['merchantId'];
		$setOdfi = $transData['MerchantFee']['ODFI'];
		$setAmount = $mer_nonFeeAmt;

		$sql = 
						"INSERT INTO warehouse.settlement_warehouse "
					. "(customer_transactions_id,settlement_ideal_date,"
					. "settlement_scheduled_date,settlement_actual_date,"
					. "settlement_merchantId,settlement_odfi,settlement_amount)"
					. "VALUES (".$setId.",".$setIdealDate.",".$setScheduledDate.","
					. "".$setActualDate.",'".$setMerchantId."','".$setOdfi."','".$mer_feeAmount."');".
					'INSERT INTO warehouse.embedded_fees '
					. '(customer_transactions_id,settlement_warehouse_id,merchant_feePostAmt,'
					. 'merchant_feePostDiscount,original_amount,fee_amount,non_fee_amount) '
					. 'SELECT "'.$setId.'",max(id),"'.$mer_feePostAmt.'","'.$mer_feePostDiscount.'",'
					. '"'.$mer_originalAmount.'","'.$mer_feeAmount.'","'.$mer_nonFeeAmt.'"'
					. 'FROM warehouse.settlement_warehouse;'.
						"INSERT INTO warehouse.settlement_warehouse "
					. "(customer_transactions_id,settlement_ideal_date,"
					. "settlement_scheduled_date,settlement_actual_date,"
					. "settlement_merchantId,settlement_odfi,settlement_amount)"
					. "VALUES (".$setId.",".$setIdealDate.",".$setScheduledDate.","
					. "".$setActualDate.",'".$setMerchantId."','".$setOdfi."','".$setAmount."');";
		return $sql;
	}

/**
 * Fetch the Query to Insert into warehouse.settlement_warehouse for Debits with Embedded fees transaction
 * and Update warehouse.workflow_eod's 'populate_settlement_warehouse'
 * 
 * @param array $transIds Transaction Ids
 * @param Date $date Format (Y-m-d)
 * @return array $sql list of queries
 */
	public function createDebitsWithEmbeddedFeesQuery($transIds,$date) {
		$transType = 'debit';
		$data = $this->CustomerTransaction->getSettlementTransactionData($transIds,$transType);
		$sqlQuery = array();

		foreach ($data as $datum){
			if ($datum['Merchant']['feeposttrans'] == 1) {
				$sqlQuery[] = $this->__manageDebitsWithEmbeddedFeesQuery($datum);
			}
		}

		$sqlQuery[] = "UPDATE warehouse.workflow_eod SET "
					. "populate_settlement_warehouse_debits_with_embedded_fees = 'success' "
					. "where id = '".$date."'";

		return $sqlQuery;
	}

}
