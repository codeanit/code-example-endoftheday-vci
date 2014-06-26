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
 * @version $$Id: Holiday.php 1382 2013-08-22 05:54:56Z anit $$
 */

App::uses('AppModel', 'Model');
/**
 * Holiday Model
 *
 */
class Holiday extends AppModel {

/**
 * Use database config
 *
 * @var string
 */
	public $useDbConfig = 'echecksRead';
	///*4/26/2012 deena:
	//*get Holiday data as per the conditions passed
	// *
	// *
	//	public function getHolidayData($conditions=null) {
	//		return $this->find('all',
	//			array('conditions'=>$conditions));
	//	}

	/**
	 * Retrieve a list of all holidays.
	 * 
	 * @return array List of holidays: (('YYYY-MM-DD', ...))
	 */
	public function getHolidays() {
		$data = $this->find('list', array('fields' => 'holiday'));
		return array_values($data);
	}
	
}
