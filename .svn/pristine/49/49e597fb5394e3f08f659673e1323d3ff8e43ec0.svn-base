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
App::uses('Step', 'Model');
App::uses('CustomerTransaction', 'Model');
App::uses('Merchant', 'Model');
App::uses('EodWorkflow', 'Model');

/**
 * Implement end of the day processes of transactions
 * sequentially.
 * Do not originate for inactive merchants
 * 
 */
class OriginationScheduleAdjustmentInactiveMerchantsStep extends Step {

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
	 * Status Property of 'echecks'.'customer_transaction'
	 * @var string Format "A"
	 */
	private $__status;

	/**
	 * Value for 'merchants'.'origTransHold' field
	 * @var string Format '1' 
	 */
	private $__merchantActive;

	public function __construct($date) {
		$this->__date = $date;
		$this->_query = array();
		$this->__status = 'A';
		$this->__merchantActive = 1;
		$this->_idempotent = true;
		$this->_stepField = 'origination_schedule_adjustment_inactive_merchants';
		$this->CustomerTransaction = new CustomerTransaction();
		$this->EodWorkflow = new EodWorkflow();

		parent::__construct();
	}

	/**
	 *  Get query to update success to field
	 * 'warehouse'.'workflow_eod SET origination_schedule_adjustment_inactive_merchants'
	 * 
	 * @param Date $date Format 'Y-m-d'
	 * @return array Querystring to update the field
	 */
	private function __getEODUpdateQuery($date) {
		$eodQuery = "UPDATE  warehouse.workflow_eod SET "
				. "origination_schedule_adjustment_inactive_merchants = 'success'"
				. " WHERE id = '" . $date . "'";
		return $eodQuery;
	}

	/**
	 *  Set 'customer'.'origination_scheduled_date' to null for all transactions
	 * with status A and origination_scheduled_date = given and 'merchants'.'active'
	 * <> 1.
	 * Also Update 'eod_workflow'.origination_schedule_adjustment_inactive_merchants
	 * to sucess
	 * 
	 * @return boolean true if updated 
	 */
	public function executeInternal() {
		$this->useDbConfig = 'warehouseWrite';

		$transQuery = array();
		$trans = $this->CustomerTransaction->getOrigScheduleAdjustmentInactiveMerchants(
				$this->__status,
				$this->__date,
				$this->__merchantActive);
		if (!empty($trans)) {
			$this->_query = $this->CustomerTransaction->getUpdateOrigSchDateQuery($trans);
		}
		array_push($this->_query, $this->__getEODUpdateQuery($this->__date));
		if (!empty($this->_query)) {
			$this->_atomicDbOperation();
		} else {
			throw new Exception('No query to be processed');
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