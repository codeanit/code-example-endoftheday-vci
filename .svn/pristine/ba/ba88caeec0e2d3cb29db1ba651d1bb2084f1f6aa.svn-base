<?php
/**
 * RoutingNumbersBlackListFixture
 *
 */
class RoutingNumbersBlackListFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'routing_numbers_black_list';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'routing_number' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 32, 'key' => 'primary', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'reason' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'routing_number', 'unique' => 1)
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
			'routing_number' => 'Lorem ipsum dolor sit amet',
			'reason' => 'Lorem ipsum dolor sit amet'
		),
	);

}
