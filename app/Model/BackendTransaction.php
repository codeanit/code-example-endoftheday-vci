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
 * @version $$Id$$
 */

App::uses('AppModel', 'Model');

/**
 * BackendTransaction Model
 *
 */
class BackendTransaction extends AppModel {

	public $useDbConfig = 'warehouseWrite';
	
	public $useTable = 'backend_transactions';

	public $actsAs = array('Containable');

	public $validate = false;

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
	}

	/**
	 * Get the Maximum id in BackendTransactions table
	 * @return : array BackendTransaction.id
	 */
	public function getMaxId() {
		$backendId = $this->find('first' , array ('fields' => array('Max(BackendTransaction.id) as id')));
		return $backendId;
	}

}