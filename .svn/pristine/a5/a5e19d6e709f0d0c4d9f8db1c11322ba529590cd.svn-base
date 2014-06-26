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
App::uses('CustomerAchTransaction', 'Model');
App::uses('EodWorkflow', 'Model');

/**
 * Fetch Query to update warehouse.workflow_eod's field 'csv_customer_ach_transactions'
 */
class CSVCustomerAchTransactionsStep extends Step {

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
	private $__date ;

	
/**
 * @param Date $date Format 'Y-m-d'
 */
	public function __construct($date) {
		$this->__date = $date;
		$this->_idempotent = false;
		$this->_stepField = 'csv_customer_ach_transactions';
		$this->_query = array();
		$this->CustomerAchTransaction = new CustomerAchTransaction();
		$this->EodWorkflow = new EodWorkflow();

		parent::__construct();
	}

/**
 * Fetch the Query to create a CSV file for all pending transactions in 
 * warehouse.customer_ach_transactions for given date. 
 */
	public function executeInternal() {
		$this->useDbConfig = 'warehouseWrite';

		$this->_query = $this->CustomerAchTransaction->createCSVTransactionsQuery(
						$this->__date);
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