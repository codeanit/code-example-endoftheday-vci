<?php
/**
 * DeletedRoutingNumberFixture
 *
 */
class DeletedRoutingNumberFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'routing_number' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 9),
		'comment' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'comment' => 'Contains reason why the routing_number has been deleted example - Blacklist, Does not exists in warehouse.fed_ach_dir', 'charset' => 'latin1'),
		'delete_status' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'comment' => 'Determines whether the the row is deleted or not. 1=DELETED, 0=NOT DELETED'),
		'creation_date' => array('type' => 'timestamp', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'routing_number' => 1,
			'comment' => 'deleted',
			'delete_status' => 0,
			'creation_date' => 1379577034
		),
	);

}
