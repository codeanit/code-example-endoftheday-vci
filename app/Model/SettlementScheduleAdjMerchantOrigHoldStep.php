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

App::uses('EodWorkflow', 'Model');
App::uses('Merchant', 'Model');
App::uses('SettlementWarehouse', 'Model');
App::uses('Step', 'Model');

/**
 * Set 'settlement_warehouse'.'settlement_actual_date to null.'
 * and update 'EodWorkflow'.'settlement_schedule_adjustment_merchant_orig_hold' 
 * to 'sucess'
 */
class SettlementScheduleAdjMerchantOrigHoldStep extends Step {

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
	 * Initialises 
	 * 
	 * @param Date $date Format 'Y-m-d'
	 */
	public function __construct($date) {
		$this->__date = $date;
		$this->__merchOrigTransHold = 1;
		$this->_query = array();
		$this->_idempotent = true;
		$this->_stepField = 'settlement_schedule_adjustment_merchant_orig_hold';
		$this->EodWorkflow = new EodWorkflow();
		$this->SettlementWarehouse = new SettlementWarehouse();

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
				. " settlement_schedule_adjustment_merchant_orig_hold = 'success'"
				. " WHERE id = '" . $date . "'";
		return $eodQuery;
	}

	/**
	 * List SettlementWarehouse's data with 
	 * 'settlement_warehouse'.'settlement_schecduled_date' >= today and 
	 * 'settlement_warehouse'.'settlement_actual_date' = null and
	 *  'merchants'.'origTransHold' == 1  and set 'settlement_scheduled_date'  to null
	 * Update 'eod_workflow'.'settlement_schedule_adjustment_merchant_orig_hold'
	 * to success
	 * 
	 * @exception: If query to be processed is empty
	 */
	public function executeInternal() {
		$this->useDbConfig = 'warehouseWrite';
		$trans = $this->SettlementWarehouse->getSettlementScheduleMerchantTrans(
			$this->__date,$this->__merchOrigTransHold,'OrigTranHold'
		);

		if (!empty($trans)) {
			$this->_query = $this->SettlementWarehouse->updateSettlementSchDateQuery($trans);
		} 
		
		array_push($this->_query,$this->__getEodUpdateQuery($this->__date));
		if(!empty($this->_query)) {
			$this->_atomicDbOperation();
		} else {
			throw new Exception("No Query to be processed");
		}
	}

	/**
	 * Check if 'EodWorkflow'.'settlement_schedule_adjustment_merchant_orig_hold'
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