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

App::uses('OriginationBatchesCustomerTransaction', 'Model');
App::uses('Step', 'Model');
App::uses('EodWorkflow', 'Model');

/**
 *  Insert row in 'warehouse'.'current_status_end_of_day' and
 *  Update 'eod_workflow'.'current_status_eod_insertion' as sucess or
 *  failure.
 */
class CurrentStatusEodInsertionStep extends Step {

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
	 * Idempotent property
	 * @var boolean Format True 
	 */
	private $__idempotent;

	/**
	 * 
	 * @param Date $___date Format: 'Y-m-d'
	 */
	public function __construct($date) {
		$this->__date = $date;
		$this->_idempotent = false;
		$this->_query = array();
		$this->_stepField = 'current_status_eod_insertion';
		$this->OriginationBatchesCustomerTransaction = 
				new OriginationBatchesCustomerTransaction();
		$this->EodWorkflow = new EodWorkflow();

		parent::__construct();
	}

	/**
	 *  Insert row in 'warehouse'.'current_status_end_of_day' and
	 *  Update 'eod_workflow'.'current_status_eod_insertion' as sucess or
	 *  failure.
	 */
	public function executeInternal() {
		$this->useDbConfig = 'warehouseWrite';

		$transData = $this->OriginationBatchesCustomerTransaction->getStartEndCustomerTransactions($this->__date);
		if ($transData['startTransId'] != null && $transData['endTransId'] != null) {
			$this->_query[] = $this->__getInsertCurrentStatusEOD(
					$transData,
					$this->__date);
			array_push($this->_query,$this->__getEodUpdateQuery($this->__date));

			$this->_atomicDbOperation();
		} else {
			throw new Exception('Failure Inserting Data in warehouse.current_status_end_of_day');
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

	/**
	 * Get query for inserting data in 'warehouse'.'current_status_end_of_day'
	 * 
	 * @param array $transData Data from 'warehouse'.'customer_transactions'
	 * @param date $date Format 'Y-m-d'
	 * @return array query Querystring that insert a row into
	 *  'warehouse'.'current_status_end_of_day'
	 */
	private function __getInsertCurrentStatusEOD($transData, $date) {
		$date = date('Y-m-d h:i:s', strtotime($date));
		$query = "INSERT INTO warehouse.current_status_end_of_day (start_id,end_id,posted) " .
				"VALUES (" .
				$transData['startTransId'] . "," .
				$transData['endTransId'] . "," .
				"'" . $date . "'" .
				" )";
		return $query;
	}

	/**
	 * Get query to update 'workflow_eod'.'current_status_eod_insertion' to success 
	 * 
	 * @param Date $date Format ('Y-m-d')
	 * @return array $eodQuery Query string to update 
	 */
	private function __getEODUpdateQuery($date) {
		if (!empty($date)) {
			$eodQuery = "UPDATE warehouse.workflow_eod SET "
					. "current_status_eod_insertion = 'success' "
					. "WHERE id = '" . $date . "'";
			return $eodQuery;
		}
	}

}