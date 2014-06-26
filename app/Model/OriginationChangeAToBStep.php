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
App::uses('EodWorkflow', 'Model');

/**
 * Update warehouse.customer_transactions Table
 * 
 */
class OriginationChangeAToBStep extends Step {

	/**
 * Use database config
 *
 * @var string
 */
	public $useDbConfig = 'warehouseWrite';

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
	private $__date ;

	
/**
 * @param Date $date Format 'Y-m-d'
 */
	public function __construct($date) {
		parent::__construct();
		$this->__date = $date;
		$this->_idempotent = true;
		$this->_stepField = 'origination_change_a_to_b';
		$this->CustomerTransaction = new CustomerTransaction();
		$this->EodWorkflow = new EodWorkflow();
	}

/**
 * Updates warehouse.workflow_eod's 'customer_trans_populated_check' and
 * updates warehouse.customer_transactions's 'origination_actual_date',
 * 'effective_entry_date' and 'status' for all transaction with 'status = A' and
 * 'origination_scheduled_date = passed Date'.
 * 
 * @param array $sql Query to update customer_transactions
 */
	private function __atomicDbOperation($sql) {
		$resultAction = true;
		$db = $this->getDataSource($this->useDbConfig);

		$eodQuery =  "UPDATE warehouse.workflow_eod SET origination_change_a_to_b = 'success' where id = '".$this->__date."'";

		$db->begin($this);

		foreach ($sql as $query) {
				$result = $this->query($query);
				if (!empty($result)) {
				$resultAction = false;
			} 
		}
		$resultEodQuery = $this->query($eodQuery);

		if (empty($resultEodQuery) && $resultAction) {
			$db->commit($this);
		} else {
			$db->rollback($this);
		}
	}

/**
 * Fetch the Query to update table customer_transactions's fields
 * (status, origination_actual_date, effective_entry_date) . 
 */
	public function executeInternal() {
		$data = $this->CustomerTransaction->getOrigTransWithStatusA($this->__date);
		if(!empty($data)) {
			$query = $this->CustomerTransaction->updateOrigTransWithStatusAToB($data,$this->__date);
			$result = $this->__atomicDbOperation($query);
		} 
	}

	/**
	 * Check if the step is executed succesfully
	 * @return boolean
	 */
	public function executedSuccessfully() {
	$return = false;

		$result = $this->EodWorkflow->getTableFieldContent(
						$this->_stepField, $this->__date);

		return $return;
	}
	
	
}