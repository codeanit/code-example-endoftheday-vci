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
App::uses('CustomerTransaction', 'Model');
App::uses('Merchant', 'Model');
App::uses('EodWorkflow', 'Model');
App::uses('Step', 'Model');

/**
 * Set 'customer_transactions'.'origination_scheduled_date to null.' to null 
 * and update 'EodWorkflow'.'origination_schedule_adjustment_merchant_orig_hold' 
 * to 'sucess'
 */
class OriginationScheduleMerchantOrigHoldStep extends Step {

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
	 * Value for 'merchants'.'origTransHold' field
	 * @var string Format '1' 
	 */
	private $__merchOrigTransHold;

	/**
	 * Status Property of 'echecks'.'customer_transaction'
	 * @var string Format "A"
	 */
	private $__status;

	/**
	 * Initialises 
	 * 
	 * @param Date $date Format 'Y-m-d'
	 */
	public function __construct($date) {
		$this->__date = $date;
		$this->_query = array();
		$this->__merchOrigTransHold = 1;
		$this->__status = 'A';
		$this->_idempotent = true;
		$this->_stepField = 'origination_schedule_adjustment_merchant_orig_hold';
		$this->CustomerTransaction = new CustomerTransaction();
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
				. " origination_schedule_adjustment_merchant_orig_hold = 'success'"
				. " WHERE id = '" . $date . "'";
		return $eodQuery;
	}

	/**
	 * List customer transactions in A status and merchant OrigTransHold = 1 of 
	 * today and set origination_scheduled_date to null
	 * Update 'eod_workflow'.'origination_schedule_adjustment_merchant_orig_hold'
	 * to success
	 * 
	 * @exception: If query to be processed is empty
	 */
	public function executeInternal() {
		$this->useDbConfig = 'warehouseWrite';
		$trans = $this->CustomerTransaction->getOrigScheduleMerchantOrigHoldTrans(
				$this->__date, $this->__status, $this->__merchOrigTransHold
		);
		if (!empty($trans)) {
			$this->_query = $this->CustomerTransaction->getUpdateOrigSchDateQuery($trans);
		} 

		array_push($this->_query,$this->__getEodUpdateQuery($this->__date));

		if(!empty($this->_query)) {
			$this->_atomicDbOperation();
		} else {
			throw new Exception("No Query to be processed");
		}
	}

	/**
	 *  Check if 'EodWorkflow'.'origination_schedule_adjustment_merchant_orig_hold'
	 * is updated to 'sucess'
	 * 
	 * @return boolean true or false
	 */
	public function executedSuccessfully() {
		$result = $this->EodWorkflow->getTableFieldContent(
				$this->_stepField, $this->__date);

		return $result;
	}

}