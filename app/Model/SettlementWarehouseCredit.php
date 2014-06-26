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
 * SettlementWarehouseCredit Model
 *
 * Fetch the Query to Insert into warehouse.settlement_warehouse for credit transaction
 */
class SettlementWarehouseCredit extends SettlementWarehouse {
	
	public function __construct($id = false, $table = null, $ds = null) {
		$this->CustomerTransaction = new CustomerTransaction();

		parent::__construct($id, $table, $ds);
		
	}

/**
 * Fetch the Query to Insert into warehouse.settlement_warehouse for credit transaction
 * 
 * @param array $transData Fetched Transaction data from warehouse_customer.transactions
 * @return array $sql Query.
 */
	private function __manageCreditsQuery($transData) {
		$sql = null;
		
		$actIdealDate = $transData['CustomerTransaction']['origination_ideal_date'];
		$setId = $transData['CustomerTransaction']['id'];

		if($transData['Merchant']['prefundcr'] == 1) {
				$fundTime = $transData['Merchant']['funding_time'];
				$setIdealDate = $this->_getSettlementIdealDate($fundTime,$actIdealDate,4);
		} else {
			$setIdealDate = $transData['CustomerTransaction']['origination_actual_date'];
		}

		$setScheduledDate = $setIdealDate;
		$setActualDate = 'null';

		$setMerchantId = $transData['CustomerTransaction']['merchant_id'];
		$setOdfi = $transData['Merchant']['ODFI'];
		$setAmount = $transData['CustomerTransaction']['amount'];

		$sql = "INSERT INTO warehouse.settlement_warehouse "
						. "(customer_transactions_id,settlement_ideal_date,"
						. "settlement_scheduled_date,settlement_actual_date,"
						. "settlement_merchantId,settlement_odfi,settlement_amount)"
						. "VALUES (".$setId.",".$setIdealDate.",".$setScheduledDate.","
						. "".$setActualDate.",'".$setMerchantId."','".$setOdfi."','".$setAmount."')";

		return $sql;
	}

	/**
 * Fetch the Query to Insert into warehouse.settlement_warehouse for credit transaction
 * and Update warehouse.workflow_eod's 'populate_settlement_warehouse'
 * 
 * @param array $transIds Transaction Ids
 * @param Date $date Format (Y-m-d)
 * @return array $sql list of queries
 */
	public function createCreditsQuery($transIds,$date) {
		$transType = 'credit';
		$data = $this->CustomerTransaction->getSettlementTransactionData($transIds,$transType);
		$sqlQuery = array();

		foreach ($data as $datum){
			if ($datum['CustomerTransaction']['original_transaction_id'] == null ) {
					$sqlQuery[] = $this->__manageCreditsQuery($datum);
			}
		}

		$sqlQuery[] = "UPDATE warehouse.workflow_eod SET "
					. "populate_settlement_warehouse_credits = 'success' "
					. "where id = '".$date."'";

		return $sqlQuery;
	}

}
