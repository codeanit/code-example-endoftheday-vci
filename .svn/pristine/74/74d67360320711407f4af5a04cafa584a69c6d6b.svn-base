<?php
/**
 * CustomerTransactionFixture
 *
 */
class CustomerTransactionFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'key' => 'primary'),
		'transaction_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'comment' => 'Marked for deprecation. Use id'),
		'original_transaction_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'customer_name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'customer_name_mphone' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'merchant_name_mphone' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'comment' => 'Marked for deprecation. Use warehouse.merchant_auxiliaries', 'charset' => 'utf8'),
		'routing_number' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 9, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'account_number' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'account_number_last_four_digits' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'amount' => array('type' => 'float', 'null' => false, 'default' => null, 'length' => '15,2'),
		'originated_date' => array('type' => 'date', 'null' => true, 'default' => null, 'comment' => 'Marked for deprecation. Use origination_actual_date'),
		'creation_date' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'effective_entry_date' => array('type' => 'date', 'null' => true, 'default' => null),
		'origination_ideal_date' => array('type' => 'date', 'null' => true, 'default' => null),
		'origination_scheduled_date' => array('type' => 'date', 'null' => true, 'default' => null),
		'origination_actual_date' => array('type' => 'date', 'null' => true, 'default' => null),
		'origination_odfi' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 2, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'return_date' => array('type' => 'date', 'null' => true, 'default' => null),
		'return_code' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 3, 'comment' => 'Numeric part of R01, R02, etc'),
		'return_reason' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'merchant_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 8),
		'standard_entry_class_code' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 3, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'company_entry_description' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'company_discretionary_data' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'authorization_code' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 6, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'expiration_date' => array('type' => 'date', 'null' => true, 'default' => null),
		'status' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 1, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'gateway' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 8),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'transaction_id' => 1,
			'original_transaction_id' => 1,
			'customer_name' => 'Model test case is testcase',
			'customer_name_mphone' => 'Model test case is testcase',
			'merchant_name_mphone' => 'Model test case is testcase',
			'routing_number' => 'Model i',
			'account_number' => 'Model test case ',
			'account_number_last_four_digits' => 'Model test case is testcase',
			'amount' => 1.00,
			'originated_date' => '2013-11-20',
			'creation_date' => '2013-11-20 09:51:02',
			'effective_entry_date' => '2013-11-20',
			'origination_ideal_date' => '2013-11-20',
			'origination_scheduled_date' => '2013-11-21',
			'origination_actual_date' => '2013-11-20',
			'origination_odfi' => '',
			'return_date' => '2013-11-20',
			'return_code' => 1,
			'return_reason' => 'Model test case is testcase',
			'merchant_id' => 1,
			'standard_entry_class_code' => 'ICL',
			'company_entry_description' => 'Model ip',
			'company_discretionary_data' => 'Model test case ',
			'authorization_code' => 'Lore',
			'expiration_date' => '2013-11-20',
			'status' => 'A',
			'gateway' => 1
		),
		array(
			'id' => 2,
			'transaction_id' => 2,
			'original_transaction_id' => 2,
			'customer_name' => 'Model test case is testcase',
			'customer_name_mphone' => 'Model test case is testcase',
			'merchant_name_mphone' => 'Model test case is testcase',
			'routing_number' => 'Model i',
			'account_number' => 'Model test case ',
			'account_number_last_four_digits' => 'Model test case is testcase',
			'amount' => 1.00,
			'originated_date' => '2013-11-20',
			'creation_date' => '2013-11-20 09:51:02',
			'effective_entry_date' => '2013-11-20',
			'origination_ideal_date' => '2013-11-20',
			'origination_scheduled_date' => '2013-11-21',
			'origination_actual_date' => '2013-11-20',
			'origination_odfi' => '',
			'return_date' => '2013-11-20',
			'return_code' => 1,
			'return_reason' => 'Model test case is testcase',
			'merchant_id' => 1,
			'standard_entry_class_code' => 'ICL',
			'company_entry_description' => 'Model ip',
			'company_discretionary_data' => 'Model test case ',
			'authorization_code' => 'Lore',
			'expiration_date' => '2013-11-20',
			'status' => 'A',
			'gateway' => 1
		),
			array(
			'id' => 1000001,
			'transaction_id' => 1,
			'original_transaction_id' => 1,
			'customer_name' => 'Model test case is testcase',
			'customer_name_mphone' => 'Model test case is testcase',
			'merchant_name_mphone' => 'Model test case is testcase',
			'routing_number' => 'Model i',
			'account_number' => 'Model test case ',
			'account_number_last_four_digits' => 'Model test case is testcase',
			'amount' => 1.00,
			'originated_date' => '2013-11-20',
			'creation_date' => '2013-11-21 17:51:02',
			'effective_entry_date' => '2013-11-20',
			'origination_ideal_date' => '2013-11-20',
			'origination_scheduled_date' => '2013-11-21',
			'origination_actual_date' => '2013-11-21',
			'origination_odfi' => '',
			'return_date' => '2013-11-20',
			'return_code' => 1,
			'return_reason' => 'Model test case is testcase',
			'merchant_id' => 1029,
			'standard_entry_class_code' => 'ICL',
			'company_entry_description' => 'Model ip',
			'company_discretionary_data' => 'Model test case ',
			'authorization_code' => 'Lore',
			'expiration_date' => '2013-11-20',
			'status' => 'A',
			'gateway' => 1
		),
		array(
			'id' => 1000002,
			'transaction_id' => 2,
			'original_transaction_id' => 2,
			'customer_name' => 'Model test case is testcase',
			'customer_name_mphone' => 'Model test case is testcase',
			'merchant_name_mphone' => 'Model test case is testcase',
			'routing_number' => 'Model i',
			'account_number' => 'Model test case ',
			'account_number_last_four_digits' => 'Model test case is testcase',
			'amount' => 1.00,
			'originated_date' => '2013-11-20',
			'creation_date' => '2013-11-21 18:51:02',
			'effective_entry_date' => '2013-11-20',
			'origination_ideal_date' => '2013-11-20',
			'origination_scheduled_date' => '2013-11-21',
			'origination_actual_date' => '2013-11-21',
			'origination_odfi' => '',
			'return_date' => '2013-11-20',
			'return_code' => 1,
			'return_reason' => 'Model test case is testcase',
			'merchant_id' => 1029,
			'standard_entry_class_code' => 'ICL',
			'company_entry_description' => 'Model ip',
			'company_discretionary_data' => 'Model test case ',
			'authorization_code' => 'Lore',
			'expiration_date' => '2013-11-20',
			'status' => 'A',
			'gateway' => 1
		),
//		array(
//			'id' => '1000003',
//			'origination_scheduled_date' => '2013-11-21',
//			'status' => 'A',
//			'merchantId' => '1005',
//			'origTranHold' => '1'
//		)
	);

//	public function init() {
//		$this->records = array(
//			array(
//				'id' => '1',
//				'status' => 'A',
//				'origination_scheduled_date' => date('Y-m-d'),
//				),
//		);
//		
//	}
}
