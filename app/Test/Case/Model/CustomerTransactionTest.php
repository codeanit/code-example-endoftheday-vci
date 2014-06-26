<?php

App::uses('CustomerTransaction', 'Model');

/**
 * CustomerTransaction Test Case
 *
 */
class CustomerTransactionTest extends CakeTestCase {

	/**
	 * Fixtures
	 *
	 * @var array
	 */
//	public $fixtures = array(
//		'app.customer_transaction'
//	);
//	public $autoFixtures = true;

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
//		$this->CustomerTransaction = ClassRegistry::init('CustomerTransaction');

		$this->CustomerTransaction = ClassRegistry::init(
						array(
							'ds' => 'warehouseRead',
							'class' => 'CustomerTransaction',
							'table' => 'customer_transactions'));
		
		$this->CustomerTransaction->useDbConfig = 'warehouseRead';
	}

	
	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->CustomerTransaction);

		parent::tearDown();
	}

	

//-------------------------------------EOD STEP TEST------------------------------------------------------------//


	/**
	 * Test whether the required Transaction data  from warehouse.customer_transactions table has been fetched or not
	 * Good Case Scenario
	 */
	public function ptestGetOriginationTransactionData_Good() {
		$expectedId = '1001935';
		$expectedTransType = 'debit';
		$data = $this->CustomerTransaction->getOriginationTransactionData('1001935');
		$this->assertNotEmpty($data);
		$this->assertEquals($expectedId, $data[0]['CustomerTransaction']['id']);
		$this->assertEquals($expectedTransType, $data[0]['CustomerTransaction']['transaction_type']);
	}
	
	/**
	 * Test whether the required Transaction data  from warehouse.customer_transactions table has been fetched or not
	 * Bad Case Scenario
	 */
	public function ptestGetOriginationTransactionData_Bad() {
		$expectedId = '1001677';
		$expectedTransType = 'credit';
		$data = $this->CustomerTransaction->getOriginationTransactionData('2001677');
		$this->assertNotEmpty($data);
		$this->assertEquals($expectedId, $data[0]['CustomerTransaction']['id']);
		$this->assertEquals($expectedTransType, $data[0]['CustomerTransaction']['transaction_type']);
	}
	
	/**
	 * Test whether the transaction with status A after 6pm exist or not for the given date and 
	 * if exist returns the query .
	 * Good Case Scenario
	 */
	public function ptestAcceptedTransactionsExistsAfterCutOff_Good() {
		$expected = array("UPDATE warehouse.workflow_eod SET "
						. "customer_trans_populated_check = 'success' "
						. "where id = '2013-11-29'");
		$data = $this->CustomerTransaction->acceptedTransactionsExistsAfterCutOff('2013-11-29');
		
		$this->assertEqual($expected,$data);
	}

	/**
	 * Test whether the transaction with status A after 6pm exist or not for the given date.
	 * if exist returns the query .
	 * Bad Case Scenario
	 */
	public function ptestAcceptedTransactionsExistsAfterCutOff_Bad() {
		$data = $this->CustomerTransaction->acceptedTransactionsExistsAfterCutOff('2013-15-03');
		$this->assertNotEmpty($data);
	}

	/**
	 * Test whether the transaction with status A after 6pm exist or not for the given date.
	 * if exist returns the query .
	 * Corner Case Scenario
	 */
	public function ptestAcceptedTransactionsExistsAfterCutOff_Corner() {
		$data = $this->CustomerTransaction->acceptedTransactionsExistsAfterCutOff('2013-12-27');
		$this->assertNotEmpty($data);
		
	}

	/**
	 * Test whether the required data from warehouse.customer_transactions table has been fetched or not
	 * Good Case Scenario
	 */
	public function ptestGetOrigTransWithStatusA_Good() {
		$expectedData = array(
			array(
				'CustomerTransaction' => array('id' => '1001677'),
				'Merchant' => array('funding_time' => '2 Day')
			),
			array(
				'CustomerTransaction' => array('id' => '1001678'),
				'Merchant' => array('funding_time' => 'HOLD')
			),
				array(
				'CustomerTransaction' => array('id' => '1001679'),
				'Merchant' => array('funding_time' => '10 Day')
			)
		);
		$data = $this->CustomerTransaction->getOrigTransWithStatusA('2013-11-27');
		$this->assertNotEmpty($data);
		$this->assertArrayHasKey('funding_time', $data[0]['Merchant']);
		$this->assertEquals($expectedData, $data);
	}

	/**
	 * Test whether the required data from warehouse.customer_transactions table has been fetched or not
	 * Bad Case Scenario
	 */
	public function ptestGetOrigTransWithStatusA_Bad() {
		$data = $this->CustomerTransaction->getOrigTransWithStatusA('2013-11-30');
		$this->assertNotEmpty($data);
	}

	/**
	 * Test whether the data in warehouse.customer_transactions table has been updated or not
	 * Good Case Scenario
	 */
	public function ptestupdateOrigTransWithStatusAToB_Good() {
		$expectedData = array(
				"UPDATE warehouse.customer_transactions SET customer_transactions.status = 'B', "
							."customer_transactions.origination_actual_date = '2013-11-27', "
							."customer_transactions.effective_entry_date = '2013-12-02' "
							."where customer_transactions.id = '1001677'"
			
		);
		$testData = array(
			array(
				'CustomerTransaction' => array('id' => '1001677'),
				'Merchant' => array('funding_time' => '2 Day')
			),
		);
		$data = $this->CustomerTransaction->updateOrigTransWithStatusAToB($testData,'2013-11-27');
		
		$this->assertNotEmpty($data);
		$this->assertEquals($expectedData, $data);
	}

	/**
	 * Test whether the data in warehouse.customer_transactions table has been updated or not
	 * Bad Case Scenario
	 */
	public function ptestupdateOrigTransWithStatusAToB_Bad() {
		$expectedData = array(
				"UPDATE warehouse.customer_transactions SET customer_transactions.status = 'B', "
							."customer_transactions.origination_actual_date = '2013-11-27', "
							."customer_transactions.effective_entry_date = '2013-12-02' "
							."where customer_transactions.id = '1001677'"
		);
		$testData = array();

		$data = $this->CustomerTransaction->updateOrigTransWithStatusAToB($testData,'2013-15-30');

		$this->assertNotEmpty($data);
		$this->assertEquals($expectedData, $data);
	}

	/**
	 * Test whether the data in warehouse.customer_transactions table has been updated or not
	 * Corner case Scenario
	 */
	public function ptestupdateOrigTransWithStatusAToB_Corner() {
		$expectedData = array(
				"UPDATE warehouse.customer_transactions SET customer_transactions.status = 'A', "
							."customer_transactions.origination_actual_date = '0000-11-27', "
							."customer_transactions.effective_entry_date = '2013-11-20' "
							."where customer_transactions.id = '1001677'"
		);

		$testData = array();

		$data = $this->CustomerTransaction->updateOrigTransWithStatusAToB($testData,'2013-11-30');

		$this->assertEquals($expectedData, $data);
	}

	/**
	 * Test Returns all customerTransactions to originate ICL transactions
	 * i.e customer transactions of date '2013-11-21',
	 * standard entry class => 'ICL',  and status => "A"
	 */
	public function ptestgetOriginationScheduleAdjustmentICL() {
		$expectedResult = array(
			'id' => 1,
			'origination_scheduled_date' => '2013-11-21',
			'standard_entry_class_code' => 'ICL',
			'status' => 'A',
		);
		$date = '2013-11-21';
		$result = $this->CustomerTransaction->getOriginationScheduleAdjustmentICL(
				$date, 'ICL', 'A');
		$this->assertEquals($result[0]['CustomerTransaction'], $expectedResult);
	}

	/**
	 * Test Update CustomerTransaction.origination_scheduled_date to null
	 * to originate ICL transactions
	 */
	public function ptestupdateOrigScheduledDateforAdjICL() {
		$trans[]['CustomerTransaction'] = array(
			'id' => 1,
		);
		$trans[]['CustomerTransaction'] = array(
			'id' => 2,
		);

		$expectedResult[0]['CustomerTransaction'] = array(
					'id' => 1,
					'origination_scheduled_date' => '0000-00-00',
					'standard_entry_class_code' => 'ICL',
					'status' => 'A',
		);
		$expectedResult[1]['CustomerTransaction'] = array(
			'id' => 2,
			'origination_scheduled_date' => '0000-00-00',
			'standard_entry_class_code' => 'ICL',
			'status' => 'A',
		);

		$readBefore = $this->CustomerTransaction->find('all', array(
			'fields' => array('id', 'origination_scheduled_date',
				'standard_entry_class_code', 'status')));
		$numRecordsBefore = $this->CustomerTransaction->find('count');
		$result = $this->CustomerTransaction->updateOrigScheduledDate($readBefore);
		$numRecordsAfter = $this->CustomerTransaction->find('count');
		$readAfter = $this->CustomerTransaction->find('all', array(
			'fields' => array('id', 'origination_scheduled_date',
				'standard_entry_class_code', 'status')));
		$this->assertEquals($expectedResult, $readAfter);
		$this->assertTrue($numRecordsBefore == $numRecordsAfter);

		$recordCompare = array_diff(
				$readBefore[0]['CustomerTransaction'], $readAfter[0]['CustomerTransaction']);
		$expectedArrayDiffResult = array('origination_scheduled_date' => '2013-11-21');
		$this->assertEquals($expectedArrayDiffResult, $recordCompare);
	}

	/**
	 * Good case test to get customer transactions if the merchant is on 'Origination hold'
	 */
	public function ptestgetOrigScheduleMerchantOrigHoldTrans_Good() {
		$expectedResult = array(
			'CustomerTransaction' => array(
				'id' => '1000001',
				'origination_scheduled_date' => '2013-11-21',
				'status' => 'A'),
			'Merchant' => array(
				'merchantId' => '1029'
			)
		);
		$date = '2013-11-21';
		$result = $this->CustomerTransaction->getOrigScheduleMerchantOrigHoldTrans(
				$date, 'A', '1');
//		debug($result);
		$this->assertEquals($result[0], $expectedResult);
	}

	/**
	 * Bad case test to get customer transactions 
	 * if the merchant is on 'Origination hold'
	 */
	public function ptestgetOrigScheduleMerchantOrigHoldTrans_Bad() {
		$expectedResult = array(
			'CustomerTransaction' => array(
				'id' => '1000001',
				'origination_scheduled_date' => '2013-11-21',
				'status' => 'A'),
			'Merchant' => array(
				'merchantId' => '1029'
			)
		);
		$date = '2013-11-21';
		$result = $this->CustomerTransaction->getOrigScheduleMerchantOrigHoldTrans(
				$date, 'B', '0');
		$this->assertEquals($result[0], $expectedResult);
	}

	/**
	 * Good test case to set origination_scheduled_date to null
	 * if the merchant is on ‘origination hold’.
	 */
	public function ptestupdateOrigScheDateforOrigHold_Good() {
		$date = '2013-11-21';
		$expectedResult = array(
			'CustomerTransaction' => array(
				'id' => '1000001',
				'origination_scheduled_date' => '0000-00-00',
				'status' => 'A'),
			'Merchant' => array(
				'merchantId' => '1029'
			)
		);
		$readBefore = $this->CustomerTransaction->getOrigScheduleMerchantOrigHoldTrans(
				$date, 'A', '1');
		$numRecordsBefore = $this->CustomerTransaction->find('count');
		$result = $this->CustomerTransaction->updateOrigScheduledDate($readBefore);
		$numRecordsAfter = $this->CustomerTransaction->find('count');
		$readAfter = $this->CustomerTransaction->getOrigScheduleMerchantOrigHoldTrans(
				'0000-00-00', 'A', '1');
		$this->assertEquals($expectedResult, $readAfter[0]);
		$this->assertTrue($numRecordsBefore == $numRecordsAfter);
		$this->assertTrue($result);
	}

	/**
	 * Bad test case to set origination_scheduled_date to null
	 * if the merchant is on ‘origination hold’.
	 */
	public function ptestupdateOrigScheDateforOrigHold_Bad() {
		$expectedResult = array(
			'CustomerTransaction' => array(
				'id' => '1000001',
				'origination_scheduled_date' => '0000-00-00',
				'status' => 'A'),
			'Merchant' => array(
				'merchantId' => '1029'
			)
		);
		$readBefore = array();
		$numRecordsBefore = $this->CustomerTransaction->find('count');
		$result = $this->CustomerTransaction->updateOrigScheduledDate($expectedResult);
		$numRecordsAfter = $this->CustomerTransaction->find('count');
		$readAfter = $this->CustomerTransaction->getOrigScheduleMerchantOrigHoldTrans(
				'0000-00-00', 'A', '1');
		$this->assertEquals($expectedResult, $readAfter[0]);
		$this->assertFalse($numRecordsBefore == $numRecordsAfter);
		$this->assertGreaterThan($numRecordsBefore, $numRecordsAfter);
		$this->assertFalse($result);
	}

	public function ptestUpdateOrigScheDateForOrigHold_corner() {
		
	}

	/**
	 * Good Test function to return all customer_transactions in A status 
	 * and origination_scheduled_date = today and merchants.active != 1
	 */
	public function ptestgetOrigSchedAdjInactiveMerchants_Good() {
		$expected = array('CustomerTransaction' => array(
				'id' => '1000001',
				'origination_scheduled_date' => '2013-11-21',
				'status' => 'A'
			),
			'Merchant' => array(
				'active' => '0'
		));
		$output = $this->CustomerTransaction->getOrigScheduleAdjustmentInactiveMerchants(
				'A', '2013-11-21', '0'
		);
		$this->assertEquals($output[0], $expected);
	}

	/**
	 * Bad Test function to return all customer_transactions in A status 
	 * and origination_scheduled_date = today and merchants.active != 1
	 */
	public function ptestgetOrigSchedAdjInactiveMerchants_Bad() {
		$expected = array('CustomerTransaction' => array(
			'id' => '1000001',
				'origination_scheduled_date' => '2013-11-21',
				'status' => 'A'
			),
			'Merchant' => array(
				'active' => '0'
		));
		$output = $this->CustomerTransaction->getOrigScheduleAdjustmentInactiveMerchants(
				'B', '2013-11-21', '1'
		);
		$this->assertEmpty($output);
	}

	/**
	 * Good test case to set  origination_scheduled_date to null
	 * for all customer_transactions in A status and 
	 * origination_scheduled_date = today and merchants.active != 1,
	 */
	public function ptestupdateOrigSchDateforInactiveMerch_Good() {
		$expectedResult = array(
			'CustomerTransaction' => array(
				'id' => '1000001',
				'origination_scheduled_date' => '2013-11-21',
				'status' => 'A'),
			'Merchant' => array(
				'active' => '0'
			)
		);
		$readBefore = $this->CustomerTransaction->getOrigScheduleAdjustmentInactiveMerchants(
				'A', '2013-11-21', '0'
		);
//		debug($readBefore);
//		die;
		$numRecordsBefore = $this->CustomerTransaction->find('count');
		$result = $this->CustomerTransaction->updateOrigScheduledDate($readBefore[0]);
		$numRecordsAfter = $this->CustomerTransaction->find('count');
		$readAfter = $this->CustomerTransaction->getOrigScheduleAdjustmentInactiveMerchants(
				'A', '0000-00-00', '0'
		);
//		debug($readAfter);
		$this->assertEquals($expectedResult, $readAfter[0]);
		$this->assertTrue($numRecordsBefore == $numRecordsAfter);
		$this->assertTrue($result);
	}

	/**
	 * Bad test case to set  origination_scheduled_date to null
	 * for all customer_transactions in A status and 
	 * origination_scheduled_date = today and merchants.active != 1,
	 */
	public function ptestupdateOrigSchDateforInactiveMerch_Bad() {
		$expectedResult = array(
			'CustomerTransaction' => array(
				'id' => '1000001',
				'origination_scheduled_date' => '2013-11-21',
				'status' => 'A'),
			'Merchant' => array(
				'active' => '0'
			)
		);
		$readBefore = $this->CustomerTransaction->getOrigScheduleAdjustmentInactiveMerchants(
				'B', '2013-11-21', '1'
		);
		$result = $this->CustomerTransaction->updateOrigScheduledDate($readBefore[0]);
		$readAfter = $this->CustomerTransaction->getOrigScheduleAdjustmentInactiveMerchants(
				'A', '0000-00-00', '0'
		);
		$this->assertEquals($expectedResult, $readAfter[0]);
		$this->assertFalse($result);
	}
	
	/**
	 * Test whether the queries for inserting into warehouse.origination_batches and 
	 * warehouse.origination_batches_customer_transactions and updating warehouse.customer_transactions
	 * and warehouse.workflow_eod is generated or not
	 * Good case Scenario
	 */
	public function ptestGetOriginationsQuery_Good() {
		$expected = array(
				"INSERT INTO warehouse.origination_batches "
						. "(process_date, effective_date) "
						. "VALUES ('2013-11-29','2013-12-02')",
				"UPDATE warehouse.customer_transactions SET customer_transactions.status = 'B', "
							."customer_transactions.origination_actual_date = '2013-11-29', "
							."customer_transactions.effective_entry_date = '2013-12-16'"
				. " where customer_transactions.id = '1001934'",
				"INSERT INTO warehouse.origination_batches_customer_transactions "
							. "(origination_batches_id,customer_transactions_id)"
							. "SELECT max(id),'1001934'FROM warehouse.origination_batches",
				"UPDATE warehouse.workflow_eod SET "
						. "origination_batch_creation = 'success' "
						. "where id = '2013-11-29'"
		);
		$actual = $this->CustomerTransaction->getOriginationsQuery('2013-11-29');
		
		$count = count($actual)-1;
		$this->assertEquals($expected[0], $actual[0]);
		$this->assertEquals($expected[1], $actual[1]);
		$this->assertEquals($expected[2], $actual[2]);
		$this->assertEquals($expected[3], $actual[$count]);
	}
	
	/**
	 * Test whether the queries for inserting into warehouse.origination_batches and 
	 * warehouse.origination_batches_customer_transactions and updating warehouse.customer_transactions
	 * and warehouse.workflow_eod is generated or not
	 * Good case Scenario
	 */
	public function testGetCustTransQuery_Good() {
//		$expected = array(
//				"INSERT INTO warehouse.origination_batches "
//						. "(process_date, effective_date) "
//						. "VALUES ('2013-11-29','2013-12-02')",
//				"UPDATE warehouse.customer_transactions SET customer_transactions.status = 'B', "
//							."customer_transactions.origination_actual_date = '2013-11-29', "
//							."customer_transactions.effective_entry_date = '2013-12-16'"
//				. " where customer_transactions.id = '1001934'",
//				"INSERT INTO warehouse.origination_batches_customer_transactions "
//							. "(origination_batches_id,customer_transactions_id)"
//							. "SELECT max(id),'1001934'FROM warehouse.origination_batches",
//				"UPDATE warehouse.workflow_eod SET "
//						. "origination_batch_creation = 'success' "
//						. "where id = '2013-11-29'"
//		);
		$method = new ReflectionMethod(
					'CustomerTransaction', '__getCustTransQuery'
				);
		$method->setAccessible(true);
		$query = array();
		
		$actual = $method->invoke($this->CustomerTransaction, '2013-12-09', $query);
		
//		$count = count($actual)-1;
//		$this->assertEquals($expected[0], $actual[0]);
//		$this->assertEquals($expected[1], $actual[1]);
//		$this->assertEquals($expected[2], $actual[2]);
//		$this->assertEquals($expected[3], $actual[$count]);
	}
	
	/**
	 * Test Whether Required Transaction Data for Given Transaction Id is fetche or not.
	 * Good Case Scenario
	 */
	public function ptestGetSettlementTransactionData_Good() {
		$expected = array(
				array(
						'CustomerTransaction' =>  array(
								'id' => '1001995',
								'transaction_type' => 'credit',
								'amount' => '9052.45',
								'origination_ideal_date' => '2013-12-03',
								'merchant_id' => '1109',
								'original_transaction_id' => '745240',
								'origination_actual_date' => '2013-12-03'
						),
						'Merchant' => array(
								'feeposttrans' => '0',
								'ODFI' => 'BC',
								'feePostAmt' => '0',
								'feePostDiscount' => '0',
								'funding_time' => '4 Day',
								'prefundcr' => '1'
						),
						'MerchantFee' => array(
								'merchantId' => null,
								'ODFI' => null
						)
				)
		);
		$inputData = array('1001995');
		$actual = $this->CustomerTransaction->getSettlementTransactionData($inputData,'credit');
		$this->assertEquals($expected, $actual);
		
	}
	
	/**
	 * Test Whether Required Transaction Data for Given Transaction Id is fetche or not.
	 * Good Case Scenario
	 */
	public function ptestGetSettlementTransactionData_Bad() {
$expected = array(
				array(
						'CustomerTransaction' =>  array(
								'id' => '1001995',
								'transaction_type' => 'credit',
								'amount' => '9052.45',
								'origination_ideal_date' => '2013-12-03',
								'merchant_id' => '1109',
								'original_transaction_id' => '745240',
								'origination_actual_date' => '2013-12-03'
						),
						'Merchant' => array(
								'feeposttrans' => '0',
								'ODFI' => 'BC',
								'feePostAmt' => '0',
								'feePostDiscount' => '0',
								'funding_time' => '4 Day',
								'prefundcr' => '1'
						),
						'MerchantFee' => array(
								'merchantId' => null,
								'ODFI' => null
						)
				)
		);
		$inputData = array('10019951');
		$actual = $this->CustomerTransaction->getSettlementTransactionData($inputData,'credit');
		$this->assertNotEmpty($actual);
		$this->assertEquals($expected, $actual);
		
	}
}