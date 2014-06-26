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
App::uses('EodWorkflow', 'Model');
App::uses('VciDate', 'Lib');

/**
 * Check if the provided date is business day.
 * and updates 'eod_workflow'.'is_business_day' = 'success'.
 */
Class BusinessDayStep extends Step {

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
	 * Date passed from abstract class
	 * @param Date $date Format: 'Y-m-d'
	 */
	public function __construct($date) {
		$this->__date = $date;
		$this->_idempotent = true;
		$this->VciDate = new VciDate();
		$this->_query = array();
		$this->_stepField = 'is_business_day';
		$this->EodWorkflow = new EodWorkflow();

		parent::__construct();
	}

	/**
	 * Test if a passed date is business date.
	 * Update 'workflow_eod'.'is_business_day' to 'yes' if business day else 'no'
	 */
	public function executeInternal() {
		$this->useDbConfig = 'warehouseWrite';
		$date = strtotime($this->__date);
		$businessDay = $this->VciDate->isBusinessDate($date);

		if ($businessDay == true) {
			$this->_query[] = "UPDATE  warehouse.workflow_eod "
							. "SET is_business_day  = 'yes'"
							. " WHERE id = '".$this->__date."'";

			$this->_atomicDbOperation();
		}
	}

	/**
	 * Check if the 'workflow_eod'.'is_business_day' is yes or no
	 * 
	 * @return boolean true/false  Returns true when
	 * 'workflow_eod'.'is_business_day' = 'yes'. Returns flase when
	 * 'workflow_eod'.'is_business_day' = 'no'.
	 */
	public function executedSuccessfully() {
		$result = $this->EodWorkflow->getTableFieldContent(
				$this->_stepField, $this->__date);

		return $result;
	}

}