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
App::uses('Merchant', 'Model');
App::uses('Step', 'Model');
App::uses('EodWorkflow', 'Model');

/**
* Set 'settlement_warehouse'.'settlement_scheduled_date ' to null for all transactions
* with 'settlement_warehouse'.'settlement_scheduled_date' = given and 'merchants'.'active'
* = 0 and 'settlement_warehouse'.'settlement_actual_date' = null .
* Also Update 'eod_workflow'.settlement_schedule_adjustment_inactive_merchants
* to sucess
*/
class SettlementScheduleAdjInactiveMerchantsStep extends Step {

	/**
	 * Date Property
	 * @var Date Format 'Y-m-d'
	 */
	private $__date;

	/**
	 * Status Property of 'echecks'.'settlement_warehouse'
	 * @var string Format "A"
	 */
	private $__active;


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
	 * 
	 * @param Date $date Format ('Y-m-d')
	 */
	public function __construct($date) {
		$this->__date = $date;
		$this->__active = '0';
		$this->_idempotent = true;
		$this->_query = array();
		$this->_stepField = 'settlement_schedule_adjustment_inactive_merchants';
		$this->SettlementWarehouse = new SettlementWarehouse();
		$this->EodWorkflow = new EodWorkflow();

		parent::__construct();
	}

	/**
	 * Prepare Query to update 
	 * 'warehouse'.'workflow_eod SET settlement_schedule_adjustment_inactive_merchants'
	 * 
	 * @param Date $date Format 'Y-m-d"
	 * @return array querystring
	 */
	private function __getEODUpdateQuery($date) {
		$eodQuery = "UPDATE  warehouse.workflow_eod SET "
				. "settlement_schedule_adjustment_inactive_merchants = 'success' "
				. "WHERE id = '" . $date . "'";
		return $eodQuery;
	}

	/**
	 * Set 'settlement_warehouse.settlement_scheduled_date' to null for all
	 * 'settlement_warehouse' 'settlement_scheduled_date >' = $date and
	 * 'settlement_warehouse.settlement_actual_date' == null and
	 * 'merchants'.'active' = 0
	 */
	public function executeInternal() {
		$this->useDbConfig = 'warehouseWrite';
		$trans = $this->SettlementWarehouse->getSettlementScheduleMerchantTrans(
			$this->__date,
			$this->__active,
			'MerchActive'
		);

		if (!empty($trans)) {
			$this->_query = $this->SettlementWarehouse->updateSettlementSchDateQuery($trans);
		} 
		array_push($this->_query, $this->__getEODUpdateQuery($this->__date));
		if(!empty($this->_query)) {
			$this->_atomicDbOperation();
		}
	}

	/**
	 * Check if the step is executed succesfully
	 * 
	 * @return boolean True if sucesfull else false
	 */
	public function executedSuccessfully() {
		$result = $this->EodWorkflow->getTableFieldContent(
						$this->_stepField, $this->__date);

		return $result;
	}
}