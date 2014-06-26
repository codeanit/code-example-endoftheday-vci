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
 * @version $$Id$$
 */
App::uses('AtomicModel', 'Atomicity.Model');
App::uses('EodWorkflow', 'Model');
App::uses('SettlementWarehouse', 'Model');
App::uses('AchTransaction', 'Model');
App::uses('Merchant', 'Model');

class AtomicModelTest extends CakeTestCase {

	public function setUp() {
		parent::setUp();
		$this->AtomicModel = new AtomicModel();
		$this->EodWorkflow = new EodWorkflow();
		$this->SettlementWarehouse = new SettlementWarehouse();
		$this->AchTransaction = new AchTransaction();
		$this->Merchant = new Merchant();
	}

	public function tearDown() {
		unset($this->AtomicModel);
		parent::tearDown();
	}

	/**
	 * Test case to Test exception
	 */
	public function ptestExceptionHasRightMessage() {
		$dataArray = array(
			'AchTransaction' => array(
				'creation_date' => '2015-03-09',
				'submission_scheduled_date' => '2015-03-09',
				'status' => 'pending',
				'creator' => 'deena',
				'amount' => '100'),
			'Merchant' => array(
				'merchantID' => '1005',
				'isoNumber' => '2001'
			)
		);
		$actual = $this->AtomicModel->saveAllData($dataArray);
        throw new InvalidArgumentException('Different Datasource used');
	}

	/**
	 * Test case to test if the database is updated 
	 * Counts the number of rows in table's to check if a row is inserted
	 */
	public function ptestsaveAllData_GoodTest() {
		$dataArray = array(
			'EodWorkflow' => array(
				'id' => '2015-03-18',
				'is_business_day' => 'yes'
			),
			'SettlementWarehouse' => array('customer_transactions_id' => '1000005',
				'settlement_ideal_date' => '0000-00-00',
				'settlement_scheduled_date' => null,
				'settlement_actual_date' => null,
				'settlement_merchantId' => 12,
				'settlement_odfi' => 'BC',
				'settlement_amount' => '121.10'),
			'AchTransaction' => array(
				'creation_date' => '2015-03-09',
				'submission_scheduled_date' => '2015-03-09',
				'status' => 'pending',
				'creator' => 'deena',
				'amount' => '100'),
		);

		$expExpectedEOD = array(
			'EodWorkflow' => array(
				'is_business_day' => 'yes'
			));
		$eodBeforeCount = $this->EodWorkflow->find('count');

		$expSettleWarehouse = array(
			'SettlementWarehouse' => array(
				'settlement_ideal_date' => '0000-00-00',
				'settlement_scheduled_date' => null,
				'settlement_actual_date' => null,
				'settlement_merchantId' => 12,
				'settlement_odfi' => 'BC',
				'settlement_amount' => '121.10'),
		);

		$expAchTrans = array(
			'AchTransaction' => array(
				'submission_scheduled_date' => '2015-03-09',
				'status' => 'pending',
				'creator' => 'deena',
				'amount' => '100'),
		);

		$actual = $this->AtomicModel->saveAllData($dataArray);

		$settlementWarehouseActualData = $this->SettlementWarehouse->find('all', array('fields' => array(
				'settlement_ideal_date', 'settlement_scheduled_date',
				'settlement_actual_date', 'settlement_merchantId',
				'settlement_odfi', 'settlement_amount'
			),
			'conditions' => array('customer_transactions_id' => '1000005')));

		$achTransactionActualData = $this->AchTransaction->find('all', array('fields' => array(
				'submission_scheduled_date', 'status',
				'creator', 'amount',
			),
			'conditions' => array('creation_date' => '2015-03-09')));

		$eodActualData = $this->EodWorkflow->find('all', array('fields' => array('is_business_day'),
			'conditions' => array('id' => '2015-03-18')));

		$eodAfterCount = $this->EodWorkflow->find('count');

		$this->assertNull($actual);
		$this->assertTrue($eodAfterCount == $eodBeforeCount + 1);
		$this->assertTrue($eodAfterCount == $eodBeforeCount + 1);
		$this->assertTrue($eodAfterCount == $eodBeforeCount + 1);
		$this->assertEquals($expExpectedEOD,$eodActualData[0]);
		$this->assertEquals($expSettleWarehouse,$settlementWarehouseActualData[0]);
		$this->assertEquals($expAchTrans,$achTransactionActualData[0]);
	}

	/**
	 * Test case to test if the database is updated when wrong data is intered
	 * Counts the number of rows in table's to check if a row is inserted
	 */
	public function ptestsaveAllData_BadTest() {
		$dataArray = array(
			'EodWorkflow' => array(
				'id' => '2015-03-18',
				'is_business_day' => 'yes'
			),
			'SettlementWarehouse' => array('customer_transactions_id' => '1000',
				'settlement_ideal_date' => '0000-00-00',
				'settlement_scheduled_date' => null,
				'settlement_actual_date' => null,
				'settlement_merchantId' => 12,
				'settlement_odfi' => 'BC',
				'settlement_amount' => '121.10'),
			'AchTransaction' => array(
				'creation_date' => '2015-03-09',
				'submission_scheduled_date' => '2015-03-09',
				'status' => 'pending',
				'creator' => 'deena',
				'amount' => '100'),
		);

		$expExpectedEOD = array(
			'EodWorkflow' => array(
				'is_business_day' => 'yes'
			));
		$eodBeforeCount = $this->EodWorkflow->find('count');

		$expSettleWarehouse = array(
			'SettlementWarehouse' => array(
				'settlement_ideal_date' => '0000-00-00',
				'settlement_scheduled_date' => null,
				'settlement_actual_date' => null,
				'settlement_merchantId' => 12,
				'settlement_odfi' => 'BC',
				'settlement_amount' => '121.10'),
		);

		$expAchTrans = array(
			'AchTransaction' => array(
				'submission_scheduled_date' => '2015-03-09',
				'status' => 'pending',
				'creator' => 'deena',
				'amount' => '100'),
		);

		$actual = $this->AtomicModel->saveAllData($dataArray);

		$settlementWarehouseActualData = $this->SettlementWarehouse->find('all', array('fields' => array(
				'settlement_ideal_date', 'settlement_scheduled_date',
				'settlement_actual_date', 'settlement_merchantId',
				'settlement_odfi', 'settlement_amount'
			),
			'conditions' => array('customer_transactions_id' => '1000005')));

		$achTransactionActualData = $this->AchTransaction->find('all', array('fields' => array(
				'submission_scheduled_date', 'status',
				'creator', 'amount',
			),
			'conditions' => array('creation_date' => '2015-03-09')));

		$eodActualData = $this->EodWorkflow->find('all', array('fields' => array('is_business_day'),
			'conditions' => array('id' => '2015-03-18')));

		$eodAfterCount = $this->EodWorkflow->find('count');

		
		$this->assertTrue($eodAfterCount == $eodBeforeCount);
		$this->assertTrue($eodAfterCount == $eodBeforeCount);
		$this->assertTrue($eodAfterCount == $eodBeforeCount);
		$this->assertNotEquals($expExpectedEOD,$eodActualData[0]);
		$this->assertNotEquals($expSettleWarehouse,$settlementWarehouseActualData[0]);
		$this->assertNotEquals($expAchTrans,$achTransactionActualData[0]);
	}
}

?>
