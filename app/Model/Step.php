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
 * @version $$ $$
 */

App::uses('AppModel', 'Model');

/**
 * 
 */
abstract class Step extends AppModel {

	/**
	 * @var string
	 */
	protected $_idempotent;

	/**
	 * 
	 * @var string
	 */
	protected $_stepField;

	/**
	 * Contains array of SQL statements.
	 * @var array 
	 */
	protected $_query;

	/**
	 * Executes queries in a Transaction.
	 * Must be overloaded in the subclasses.
	 */
	protected function _atomicDbOperation() {
		$rollback = false;

		$db = $this->getDataSource($this->useDbConfig);
		$db->begin($this);

		if ((!empty($this->_query)) && (count($this->_query) > 0 )) {

			foreach ($this->_query as $query) {
				$results = $this->query ($query);
				if (!empty($results)) {
					$rollback = true;
					break;
				}
			}
		}

		if ($rollback == false) {
			$db->commit($this);
		} else {
			$db->rollback($this);
		}
	}

	/**
	 * Determines whether the function is idempotent and has been executed 
	 * previously and executes executeInternal()
	 */
	public function execute() {
		if (($this->_idempotent == false) &&
			($this->executedSuccessfully() != true)) {

			$this->executeInternal();

		} elseif ($this->_idempotent == true) {
			$this->executeInternal();
		}
	}

	/**
	 * Collects data in the form of SQL query.
	 */
	abstract function executeInternal();

	/**
	 * Checks the Step was executed successfully or not.
	 */
	abstract function executedSuccessfully();

}