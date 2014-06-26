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

App::uses('AppModel', 'Model');

/**
 * EodWorkflow Model
 *
 */

class EodWorkflow extends AppModel {

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

	public $validate = array(
	'id' => array(
			'date' => array(
					'rule' => 'date',
					'format' => 'y-m-d',
					'allowEmpty' => false,
					'message' => 'Required date format Y-m-d'
			)
	));

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
	}

	/**
	 * Get the content of workflow_eod field.
	 * 
	 * @param string $fieldName
	 * @param string $date date(Y-m-d)
	 * @return array ['key'][$tableField]
	 */
	public function getTableFieldContent($tableField, $date) {
		$result = $this->find(
						'first', array(
								'fields' => array($tableField),
								'conditions' => array(
								'id' => $date)));
		
		if ($result['EodWorkflow'][$tableField] == 'success' ||
						$result['EodWorkflow'][$tableField] == 'yes') {
			return true;
		}

		return false;
	}

	/**
	 * Updates table field status to success.
	 * 
	 * @param string $date Format[Y-m-d]
	 * @param string $tableField Fields of workflow_eod,
	 * except id and is_business_day.
	 */
	public function updateTableFieldStatusToSuccess($date, $tableField) {
		$this->id = $date;
		$updateData = array('EodWorkflow' => array(
				'id'=> $date,
				$tableField => 'success'));

		$this->save($updateData);
	}

	/**
	 * Inserts new row in 'warehouse'.'workflow_eod'
	 * 
	 * @param string $date Format 'Y-m-d'
	 */
	public function insertNewEOD($date) {
		$data = array('EodWorkflow' => array(
				'id' => $date
		));

		if ($this->validates()) {
			$this->create();
			$this->save($data);
		}
	}

}