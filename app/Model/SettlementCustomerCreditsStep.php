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

App::uses('BackendTransaction', 'Model');
App::uses('CustomerTransaction', 'Model');
App::uses('EodWorkflow', 'Model');
App::uses('Merchant', 'Model');
App::uses('SettlementWarehouse', 'Model');
App::uses('Step', 'Model');

/**
 * Create row in customer_ach_transactions,settlements and backend_transactions 
 * for each settlement_warehouse rows where settlement_sheduled_date = today 
 * and settlement_actual_date is null and customer_transaction.trans_type = 'credit'
 
 */
class SettlementCustomerCreditsStep extends Step {

	/**
	 * Use database config
	 *
	 * @var string
	 */
	public $useDbConfig = 'warehouseRead';

	/**
	 * Use table
	 *
	 * @var mixed False or table name
	 */
	public $useTable = 'workflow_eod';

	/**
	 * Date Property
	 * @var Date Format 'Y-m-d'
	 */
	private $__date;

	/**
	 * Value for 'customer_transactions'.'transaction_type' field
	 * @var string Format 'debit' or 'credit' 
	 */
	private $__transType;

	/**
	 * Initialises 
	 * 
	 * @param Date $date Format 'Y-m-d'
	 */
	public function __construct($date) {
		$this->__date = $date;
		$this->__transType = 'credit';
		$this->_query = array();
		$this->_idempotent = false;
		$this->_stepField = 'settlement_customer_credits';
		$this->BackendTransaction = new BackendTransaction();
		$this->SettlementWarehouse = new SettlementWarehouse();
		$this->EodWorkflow = new EodWorkflow();

		parent::__construct();
	}

	/**
	 * Prepare query to update 'warehouse'.'workflow_eod' to set
	 * 'origination_schedule_adjustment_merchant_orig_hold' to success
	 * 
	 * @param date $date Format 'Y-m-d'
	 * @return array QueryString
	 */
	private function __getEODUpdateQuery($date) {
		$eodQuery = "UPDATE  warehouse.workflow_eod SET"
			. " settlement_customer_credits = 'success'"
			. " WHERE id = '" . $date . "'";
		return $eodQuery;
	}

	/**
	 * Insert a row in 'customer_ach_transactions','settlements' and 'backend_transactions '
	 * for each 'settlement_warehouse ' where 'settlement_sheduled_date ' = today
	 * 'settlement_actual_date' = null and 'customer_transaction.trans_type' = ‘credit’
	 * Also update 'settlement_customer_credits' field from 'workflow_eod' table 
	 * 
	 * @throws Exception If query to be processed is empty
	 */
	public function executeInternal() {
		$this->useDbConfig = 'warehouseWrite';
		$settlementWarehouseData = $this->SettlementWarehouse->getSettlementID(
				$this->__date, $this->__transType
		);
		if (!empty($settlementWarehouseData)) {
			$this->_query = $this->SettlementWarehouse->manageSettlementCustomerDebitCreditQuery(
				$settlementWarehouseData,$this->__date,'Credit');
		}
		array_push($this->_query,$this->__getEodUpdateQuery($this->__date));
		if (!empty($this->_query)) {
			$this->_atomicDbOperation();
		} else {
			throw new Exception("No Query to be processed");
		}
	}

	/**
	 *  Check if 'EodWorkflow'.'settlement_customer_credits' is updated to 'sucess'
	 * 
	 * @return boolean true or false
	 */
	public function executedSuccessfully() {
		$result = $this->EodWorkflow->getTableFieldContent(
				$this->_stepField, $this->__date);

		return $result;
	}

}