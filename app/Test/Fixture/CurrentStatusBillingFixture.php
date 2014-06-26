<?php
/**
 * CurrentStatusBillingFixture
 *
 */
class CurrentStatusBillingFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'current_status_billing';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 9, 'key' => 'primary'),
		'processed_id' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'date_processed' => array('type' => 'timestamp', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'),
		'billing_category' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
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
			'id' => 1,
			'processed_id' => 'Lorem ipsum dolor sit amet',
			'date_processed' => 1364808981,
			'billing_category' => 'Lorem ipsum dolor sit amet'
		),
	);

}
