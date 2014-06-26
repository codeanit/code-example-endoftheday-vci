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
	App::uses('Origination', 'Model');
	App::uses('EodWorkflow', 'Model');

/**
 * Fetch Query to update warehouse.workflow_eod's field 'origination_customer_debits'
 */
class OriginationCustomerDebitsStep extends Step {

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
		$this->__date = $date;
		$this->_idempotent = false;
		$this->_stepField = 'origination_customer_debits';
		$this->Origination = new Origination();
		$this->EodWorkflow = new EodWorkflow();
		$this->_query = array();

		parent::__construct();
	}


/**
 * Fetch the Query to Insert into warehouse.backend_transactions, warehouse_originations, 
 * warehouse.customer_ach_transactions
 */
	public function executeInternal() {
		$this->useDbConfig = 'warehouseWrite';
		$this->_query = $this->Origination->originateCustomerDebitsForDay($this->__date);
		$this->_atomicDbOperation();
	}

/**
 * Check if the step is executed succesfully
 * @return boolean
 */
	public function executedSuccessfully() {
		$return = $this->EodWorkflow->getTableFieldContent(
					$this->_stepField, $this->__date);

		return $return;
	}

}