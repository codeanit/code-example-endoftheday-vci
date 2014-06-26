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

App::uses('Origination', 'Model');

class OriginationTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Origination =  new Origination();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Origination);
		parent::tearDown();
	}

/**
 * Test whether the required query has been fetched or not 
 * Good Case Scenario
 */
	public function ptestOriginateCustomerCreditsForDay_Good() {
		$expected = array(
				
						"INSERT INTO warehouse.backend_transactions VALUES ('' , 'merchant_ach_transactions')",
						'INSERT INTO warehouse.originations (backend_transactions_id,origination_batches_customer_transaction_id,notes)'
					 .'SELECT max(id),"20","EOD Process" FROM  warehouse.backend_transactions',
						'INSERT INTO warehouse.merchant_ach_transactions '
					 .'(backend_transactions_id,merchant_id,account_type,transaction_type,amount,mergeability,status,merged_into_id,processing_scheduled_date,processing_actual_date)'
					 .'SELECT max(id),1154,"operation","debit",4032.10,"all",'
					 .'"pending", "null","2013-11-29","null" FROM warehouse.backend_transactions',
						"UPDATE warehouse.workflow_eod SET "
								. "origination_customer_credits = 'success' "
								. "where id = '2013-11-29'"
			
		);
		$data = $this->Origination->originateCustomerCreditsForDay('2013-11-29');
		$count = count($data)-1;
		$this->assertNotEmpty($data);
		$this->assertEqual($data[0], $expected[0]);
		$this->assertEqual($data[1], $expected[1]);
		$this->assertEqual($data[2], $expected[2]);
		$this->assertEqual($data[$count], $expected[3]);
	}

/**
 * Test whether the required query has been fetched or not 
 * Bad Case Scenario
 */
	public function ptestOriginateCustomerCreditsForDay_Bad() {
		$data = $this->Origination->originateCustomerCreditsForDay('2013-12-30');
		$this->assertNotEmpty($data);
	}

/**
 * Test whether the required query has been fetched or not 
 * Good Case Scenario
 */
	public function ptestOriginateCustomerDebitsForDay_Good() {
		$expected = array(
				
						"INSERT INTO warehouse.backend_transactions VALUES ('' , 'customer_ach_transactions')",
						'INSERT INTO warehouse.originations (backend_transactions_id,origination_batches_customer_transaction_id,notes)'
					 .'SELECT max(id),"21","EOD Debit Process" FROM  warehouse.backend_transactions',
						'INSERT INTO warehouse.customer_ach_transactions '
					 .'(customer_transactions_id,backend_transactions_id,transaction_type,amount,status,processing_scheduled_date,processing_actual_date)'
					 .'SELECT 1001935,max(id),"debit",242.98,'
					 .'"pending","2013-11-29","null" FROM warehouse.backend_transactions',
						"UPDATE warehouse.workflow_eod SET "
								. "origination_customer_debits = 'success' "
								. "where id = '2013-11-29'"
			
		);
		$data = $this->Origination->originateCustomerDebitsForDay('2013-11-29');
		$count = count($data)-1;
		$this->assertNotEmpty($data);
		$this->assertEqual($data[0], $expected[0]);
		$this->assertEqual($data[1], $expected[1]);
		$this->assertEqual($data[2], $expected[2]);
		$this->assertEqual($data[$count], $expected[3]);
	}

/**
 * Test whether the required query has been fetched or not 
 * Bad Case Scenario
 */
	public function ptestOriginateCustomerDebitsForDay_Bad() {
		$data = $this->Origination->originateCustomerDebitsForDay('2013-12-30');
		$this->assertNotEmpty($data);
	}

/**
 * Test whether the required query has been fetched or not 
 * Good Case Scenario
 */
	public function ptestOriginateCustomerCreditsOrDebits_Good() {
		$expected = array(
				
							"INSERT INTO warehouse.backend_transactions VALUES ('' , 'customer_ach_transactions')",
						'INSERT INTO warehouse.originations (backend_transactions_id,origination_batches_customer_transaction_id,notes)'
					 .'SELECT max(id),"21","EOD Debit Process" FROM  warehouse.backend_transactions',
						'INSERT INTO warehouse.customer_ach_transactions '
					 .'(customer_transactions_id,backend_transactions_id,transaction_type,amount,status,processing_scheduled_date,processing_actual_date)'
					 .'SELECT 1001935,max(id),"debit",242.98,'
					 .'"pending","2013-11-29","null" FROM warehouse.backend_transactions',
						"UPDATE warehouse.workflow_eod SET "
								. "origination_customer_debits = 'success' "
								. "where id = '2013-11-29'"
			
		);
		$data = $this->Origination->originateCustomerCreditsOrDebits(array('1001935'),'2013-11-29','EOD Debit Process', false);
	$count = count($data)-1;
		$this->assertNotEmpty($data);
		$this->assertEqual($data[0], $expected[0]);
		$this->assertEqual($data[1], $expected[1]);
		$this->assertEqual($data[2], $expected[2]);
		$this->assertEqual($data[$count], $expected[3]);
	}

/**
 * Test whether the required query has been fetched or not 
 * Bad Case Scenario
 */
	public function ptestOriginateCustomerCreditsOrDebits_Bad() {
		$data = $this->Origination->originateCustomerCreditsOrDebits('1001678','2013-11-29','EOD Debit Process', false);
		$this->assertNotEmpty($data);
		$this->assertEqual($data[0], $expected);
	}

/**
 * Test whether the required query has been fetched or not 
 * Bad Case Scenario
 */
	public function testManageData_Good() {
		$expected = array(
			"INSERT INTO warehouse.backend_transactions VALUES ('' , 'customer_ach_transactions')",
			'INSERT INTO warehouse.originations (customer_transaction_id,backend_transactions_id,origination_batches_customer_transaction_id,notes)'
			. 'SELECT 1001935, max(id),"21","EOD Debit Process" FROM  warehouse.backend_transactions',
			'INSERT INTO warehouse.customer_ach_transactions '
			. '(customer_transactions_id,backend_transactions_id,transaction_type,amount,status,processing_scheduled_date,processing_actual_date)'
			. 'SELECT 1001935,max(id),"debit",242.98,'
			. '"pending","2013-11-29",null FROM warehouse.backend_transactions',
			"UPDATE warehouse.workflow_eod SET "
			. "origination_customer_debits = 'success' "
			. "where id = '2013-11-29'"
		);
		$method = new ReflectionMethod(
					'Origination', '__manageData'
				);
		$method->setAccessible(true);
		$data = array(
				array(
					'CustomerTransaction' => array (
							'id' => '1001935',
							'transaction_type' => 'debit',
							'amount' => 242.98,
							'status' => 'B',
							
					),
					'OrigBatchCustTrans' => array(
							'id' => '21'
					)
				));
		$actual = $method->invoke($this->Origination,$data,'2013-11-29','EOD Debit Process');
		$this->assertNotEmpty($actual);
		$this->assertEquals($actual,$expected);
	}

	/**
	 * Test whether the required query has been fetched or not 
	 * Bad Case Scenario
	 */
	public function ptestManageData_Bad() {
		
		$method = new ReflectionMethod(
					'Origination', '__manageData'
				);
		$method->setAccessible(true);
		$data = array();

		$actual = $method->invoke($this->Origination,$data,'2013-12-29','EOD Debit Process');
		$this->assertEmpty($actual);
	}
}