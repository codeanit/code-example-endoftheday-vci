<?php

App::import('Shell', 'EODWorkflow'); 
App::uses('EodWorkflow', 'Model');
App::uses('CustomerTransaction', 'Model');
App::uses('Origination', 'Model');
App::uses('MerchantAchTransaction', 'Model');
App::uses('OriginationBatchesCustomerTransaction', 'Model');
App::uses('OriginationBatch', 'Model');
App::uses('CurrentStatusEndOfDay', 'Model');
App::uses('SettlementWarehouse', 'Model');

class EODWorkflowShellTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->day = array('day' => '2013-12-09');
		$this->EODWorkflowShell = new EODWorkflowShell();
		$this->EodWorkflow = new EodWorkflow();
		$this->date = '2013-12-09';
		$this->CustomerTransaction = new CustomerTransaction();
		$this->OriginationBatchesCustomerTransaction = new OriginationBatchesCustomerTransaction();
		$this->OriginationBatch = new OriginationBatch();
		$this->Origination = new Origination();
		$this->MerchantAchTransaction = new MerchantAchTransaction();
		$this->CurrentStatusEndOfDay = new CurrentStatusEndOfDay();
		$this->SettlementWarehouse = new SettlementWarehouse();
	}

	public function testmain() {
//		$fundingZeroData = $this->CustomerTransaction->find('all',
//				array(
//					'fields' => array('Merchant.funding_time',
//						'CustomerTransaction.id'
//						),
//					'joins' => array(
//					array(
//						'table' => 'echecks.merchants',
//						'alias' => 'Merchant',
//						'type' => 'LEFT',
//						'conditions' => 'CustomerTransaction.merchant_id = Merchant.merchantId'
//					)),
//					'conditions' => array(
//						'CustomerTransaction.creation_date > ' => '2013-12-09',
//						'CustomerTransaction.creation_date < ' => '2013-12-10',
//						'Merchant.funding_time' => '0 Day'
//					),
//					'order' => 'Merchant.funding_time'
//		));
//		debug($fundingZeroData);
//		die;
		$this->EODWorkflowShell->main();
		$eodData = $this->EodWorkflow->find('all', array('conditions' => array('id' => $this->date)));
		debug($eodData);
	}

	/**
	 * 
	 */
	public function testCustomerTransPopulatedCheckStep() {
		$actual = $this->CustomerTransaction->find('first', array(
			'conditions' => array(
				'creation_date >' => '2013-12-09 18:00:00'
		)));
		$this->assertNotEmpty($actual);
	}

	/**
	 * Test if origination_scheduled_date is converted to null
	 * Test if the id is originated
	 */
	public function testOriginationScheduleMerchantOrigHold() {
		$query['fields'] = array('id',
			'merchant_id',
			'origination_scheduled_date',
			);
		$query['joins'] = array(
			array(
				'table' => 'echecks.merchants',
				'alias' => 'Merchant',
				'type' => 'LEFT',
				'conditions' => 'CustomerTransaction.merchant_id = Merchant.merchantId'
			),
		);
		$query['conditions'] = array(
			'status' => 'A',
			'Merchant.OrigTranHold'  => 1
		);
		$trans = $this->CustomerTransaction->find('all', $query);

		foreach ($trans as $origHoldData) {
			$origSchDate = $origHoldData['CustomerTransaction']['origination_scheduled_date'];
			$this->assertNull($origSchDate);
		}
	}

	/**
	 * 
	 */
	public function testOriginationScheduleAdjustmentInactiveMerchantsStep() {
		$query['fields'] = array('id',
			'merchant_id',
			'origination_scheduled_date',
			);
		$query['joins'] = array(
			array(
				'table' => 'echecks.merchants',
				'alias' => 'Merchant',
				'type' => 'LEFT',
				'conditions' => 'CustomerTransaction.merchant_id = Merchant.merchantId'
			),
		);
		$query['conditions'] = array(
			'status' => 'A',
			'Merchant.OrigTranHold'  => 1
		);
		$trans = $this->CustomerTransaction->find('all', $query);

		foreach ($trans as $origInactiveMerchants) {
			$origSchDate = $origInactiveMerchants['CustomerTransaction']['origination_scheduled_date'];
			$this->assertNull($origSchDate);
		}
	}

	/**
	 * 
	 */
	public function testOriginationScheduleAdjustmentICLStep() {
		$trans = $this->CustomerTransaction->find('all', array(
			'fields' => array('id',
				'origination_scheduled_date',
				'standard_entry_class_code', 'status'),
			'conditions' => array(
				array(
					'standard_entry_class_code' => 'ICL',
					'status' => 'A'
				)
		)));
		foreach ($trans as $origAdjICL) {
			$origSchDate = $origAdjICL['CustomerTransaction']['origination_scheduled_date'];
			$this->assertNull($origSchDate);
		}
	}

	/**
	 * Test there should be one row in origination_batches with the process_date = this->date 
	 */
	public function testOriginationBatchCreation() {
		$actOrigBatch = $this->OriginationBatch->find('all',
				array('conditions' => array('process_date' => '2013-12-09')));
		$this->assertNotEmpty($actOrigBatch);
		$actCusTrans = $this->CustomerTransaction->find('all', array('fields' => array(
				'id',
				'effective_entry_date',
				'origination_actual_date',
				'status'),
			'conditions' => array(
				'CustomerTransaction' => array(
					'effective_entry_date' => '2013-12-10',
					'origination_actual_date' => '2013-12-09',
					'status' => 'B'
//					'id' => '1002066'
					))));
		$this->assertNotEmpty($actCusTrans);
		
	}

	public function testMerchantAchTransactionInserted() {
//		$origBatchCustomTrans = $this->
		$merchAchData = $this->MerchantAchTransaction->find('all',
				array('conditions' => array(
					'processing_scheduled_date ' => $this->date
				)));
		$this->assertNotEmpty($merchAchData);

		
	}

	public function testOriginationInserted(){
		$origData = $this->Origination->find('all',
				array('customer_transactions_id >=' => '1002053'));
		$this->assertNotEmpty($origData);
	}

	
	public function testOriginationBatchesCustomerTrans(){
		$actOrigBatchCustTrans = $this->OriginationBatchesCustomerTransaction->find(
				'all',
				array(
					'conditions' => array(
					'customer_transactions_id >=' => '1002053'
		)));
		$this->assertNotEmpty($actOrigBatchCustTrans);
	}

	public function current_status_eod_insertion() {
		$currentStatusEOD = $this->CurrentStatusEndOfDay->find('first',
				array('conditions' => array(
					'posted >' => '2013-12-09'
				)));
		$this->assertNotEmpty($currentStatusEOD);
	}

	/**
	 * test if the settlementWarehouse table has been updated with settlement_ideal_date set to today + funding day
	 */
	public function testSettlementWarehouseUpdated() {
		$expSettleData = array('SettlementWarehouse' => array(
			'settlement_ideal_date' => '2013-12-13',
			'settlement_scheduled_date' => '2013-12-13'
		));
		$settleData = $this->SettlementWarehouse->find('first',
				array('fields' => array('settlement_ideal_date',
					'settlement_scheduled_date'),
					'conditions' => array(
						'settlement_merchantId' => '1109'
					)));
		debug($settleData);
		$this->assertEquals($expSettleData,$settleData);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->EODWorkflowShell);
		unset($this->EodWorkflow);
		unset($this->CustomerTransaction);
		unset($this->OriginationBatchesCustomerTransaction);
		unset($this->OriginationBatch);
		unset($this->Origination);
		unset($this->MerchantAchTransaction);
		parent::tearDown();
	}
}
