<?php
/**
 * OdfiFixture
 *
 */
class OdfiFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'odfi';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'ODFI_Code' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 2, 'key' => 'primary', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'ODFI_Name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'ABA' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'available' => array('type' => 'integer', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'ODFI_Code', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'ODFI_Code' => '',
			'ODFI_Name' => 'Lorem ipsum dolor sit amet',
			'ABA' => 'Lorem ip',
			'available' => 1
		),
	);

}
