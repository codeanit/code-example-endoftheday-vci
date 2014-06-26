<?php
/**
 * MerchantStatusFixture
 *
 */
class MerchantStatusFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'merchant_status';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 9, 'key' => 'primary'),
		'merchantId' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'apply_date' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'bank_statement' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 9),
		'drivers_license' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 9),
		'business_license' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 9),
		'other' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 9),
		'other_details' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'approved' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 9),
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
			'merchantId' => 'Lorem ipsum dolor sit amet',
			'apply_date' => '2013-04-04 09:30:12',
			'bank_statement' => 1,
			'drivers_license' => 1,
			'business_license' => 1,
			'other' => 1,
			'other_details' => 'Lorem ipsum dolor sit amet',
			'approved' => 1
		),
	);

}
