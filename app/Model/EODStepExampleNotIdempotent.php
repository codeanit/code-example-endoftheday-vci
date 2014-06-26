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

class EODStepExampleNotIdempotent extends Step {

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
	 * Date of End Of Day process execution.
	 * @var string 
	 */
	private $__date;

	public function __construct($date) {
		$this->__date = $date;
		$this->_idempotent = true;
		$this->_stepField = 'is_business_day';

		parent::__construct();
	}

	private function __atomicDbOperation() {
		$db =& ConnectionManager::getDataSource($this->useDbConfig);

		$db->begin($this);

//		$updateEODStatus = $this->_atomicDbOperationModel->setIsBusinessDayToYes($date);

		$statusBL = $this->query('Update');
		if($statusBL == true)
			$statusBL = $this->query('Statment');

		if ($statusBL == true && $statusQuery) {
			$db->commit($this);
		} else {
			$db->rollback($this);
			debug($db);
		}
	}

	public function executeInternal() {
		//Call function that will execute BL
		if (true) {
			$this->atomicDbOperation();
		}
	}

	/**
	 * Check if the FIELDNAME content is success
	 * 
	 * @return boolean
	 */
	public function executedSuccessfully() {
		$objWorkflowEod = new EodWorkflow();
		$contentOfEodWorkflowField = 
						$objWorkflowEod->getFieldContent(
										$this->_implementatedClassTableMappingField);

		if($stepExecutionStatus['EodWorkflow'][
				$this->_implementatedClassTableMappingField] == 'yes') {
			return true;
		}

		return false;
	}

}