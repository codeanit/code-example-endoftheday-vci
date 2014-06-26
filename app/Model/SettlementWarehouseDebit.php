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
 * @version $$Id: $$
 */

App::uses('SettlementWarehouse', 'Model');
App::uses('CustomerTransaction', 'Model');

/**
 * Fetch the Query to Insert into warehouse.settlement_warehouse for debit transaction
 *
 * @property SettlementWarehouseDebit 
 */
class SettlementWarehouseDebit extends SettlementWarehouse {
	
	public function __construct($id = false, $table = null, $ds = null) {
		$this->CustomerTransaction = new CustomerTransaction();

		parent::__construct($id, $table, $ds);
		
	}

/**
 * Fetch the Query to Insert into warehouse.settlement_warehouse for debit transaction
 * 
 * @param array $transData Fetched Transaction data from warehouse_customer.transactions
 * @return array $sql Query.
 */
	private function __manageDebitsQuery($transData) {
		$sql = null;
		
		$actIdealDate = $transData['CustomerTransaction']['origination_ideal_date'];
		$setId = $transData['CustomerTransaction']['id'];

		$fundTime = $transData['Merchant']['funding_time'];
		$setIdealDate = $this->_getSettlementIdealDate($fundTime,$actIdealDate,$fundTime);
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
 * Fetch the Query to Insert into warehouse.settlement_warehouse for debit transaction
 * and Update warehouse.workflow_eod's 'populate_settlement_warehouse'
 * 
 * @param array $transIds Transaction Ids
 * @param Date $date Format (Y-m-d)
 * @return array $sql list of queries
 */
	public function createDebitsQuery($transIds,$date) {
		$transType = 'debit';
		$data = $this->CustomerTransaction->getSettlementTransactionData($transIds,$transType);
		$sqlQuery = array();
		foreach ($data as $datum) {
			if ($datum['Merchant']['feeposttrans'] != 1) {
				$sqlQuery[] = $this->__manageDebitsQuery($datum);
			}
		}

		$sqlQuery[] = "UPDATE warehouse.workflow_eod SET "
					. "populate_settlement_warehouse_debits = 'success' "
					. "where id = '".$date."'";

		return $sqlQuery;
	}

}
