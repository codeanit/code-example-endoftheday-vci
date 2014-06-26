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
 * @version $$Id: CurrentStatusEndOfDay.php 1424 2013-08-29 09:37:56Z deena $$
 */

App::uses('AppModel', 'Model');

/**
 * CurrentStatusEndOfDay Model
 *
 * @property Start $Start
 * @property End $End
 */
class CurrentStatusEndOfDay extends AppModel {

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
	public $useTable = 'current_status_end_of_day';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';

	public $actsAs = array(
		'Containable', 
		//'Autocache.Autocache' => array('default_cache' => 'daily')
	);

}
