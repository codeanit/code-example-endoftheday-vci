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
App::uses('BackendTransaction', 'Model');
App::uses('SettlementCustomerCreditsStep', 'Model');
App::uses('CustomerTransaction', 'Model');
App::uses('SettlementWarehouse', 'Model');
App::uses('CustomerAchTransaction', 'Model');
App::uses('Settlement', 'Model');

class SettlementCustomerCreditsStepTest extends CakeTestCase {

	public function setUp() {
		parent::setUp();
		$date = '2013-12-09';
		$this->SettlementCustomerCreditsStep = new SettlementCustomerCreditsStep($date);
		$this->BackendTransaction = new BackendTransaction();
		$this->CustomerAchTransaction = new CustomerAchTransaction();
		$this->CustomerTransaction = new CustomerTransaction();
		$this->SettlementWarehouse = new SettlementWarehouse();
		$this->Settlement = new Settlement();
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->SettlementCustomerCreditsStep);
		unset($this->CustomerTransaction);
		parent::tearDown();
	}

	/**
	 * Test to insert a row in 'customer_ach_transactions','settlements' and 'backend_transactions '
	 * for each 'settlement_warehouse ' where 'settlement_sheduled_date ' = today
	 * 'settlement_actual_date' = null and 'customer_transaction.trans_type' = ‘credit’
	 * Also update 'settlement_customer_credits' field from 'workflow_eod' table 
	 */
	public function testExecuteInternal_Good() {
		$expected = null;
		$expResultEOD = array(
				array(
				'workflow_eod' => array(
				'id' => '2013-12-02',
				'settlement_customer_credits' => 'success')));

		$settlementWarehouseCreditData = $this->SettlementCustomerCreditsStep->SettlementWarehouse->getSettlementID(
				'2013-12-09', 'credit'
		);

		$rowsCount = count($settlementWarehouseCreditData);

		$beforeCntBackendTrans = $this->BackendTransaction->find('count');
		$beforeCntCustAchTrans = $this->CustomerAchTransaction->find('count');
		$beforeCntSettlements = $this->Settlement->find('count');
		$actual = $this->SettlementCustomerCreditsStep->executeInternal();
		$this->assertEquals($actual, $expected);

		$afterCntBackendTrans = $this->BackendTransaction->find('count');
		$afterCntCustAchTrans = $this->CustomerAchTransaction->find('count');
		$afterCntSettlements = $this->Settlement->find('count');

		$this->assertTrue($afterCntBackendTrans == $beforeCntBackendTrans + $rowsCount);
		$this->assertTrue($afterCntCustAchTrans == $beforeCntCustAchTrans + $rowsCount);
		$this->assertTrue($afterCntSettlements == $beforeCntSettlements + $rowsCount);

		$insertedBackendTrans = $this->BackendTransaction->find('first',
				array('fields' => array('subtype'),
					'order' => "id DESC"));
		$expectedBackendTrans = array(
			'BackendTransaction' => array('subtype' => 'customer_ach_transactions'));
		$this->assertEquals($insertedBackendTrans, $expectedBackendTrans);

		$backendID = $this->BackendTransaction->find('first',
				array('fields' => array('id'),
					'order' => "id DESC")
				);
		$insertSettlements = $this->Settlement->find('first',
				array('fields' => array(
					'backend_transaction_id',
					'notes'),
					'order' => "id DESC"));
		$expectedSettlements = array(
			'Settlement' => array(
				'backend_transaction_id' => $backendID['BackendTransaction']['id'],
				'notes' => 'EOD Settlement Customer Credit'));
		$this->assertEquals($insertSettlements, $expectedSettlements);

		$insertedCustomerAchTrans = $this->CustomerAchTransaction->find('first',
				array('fields' => array('backend_transactions_id',
					'transaction_type','status',
					'processing_actual_date'),
					'order' => "id DESC"));
		$expectedCustomerAchTrans = array(
			'CustomerAchTransaction' => array(
				'backend_transactions_id' => $backendID['BackendTransaction']['id'],
				'transaction_type' => 'credit',
				'status' =>'pending',
				'processing_actual_date' => null));
		$this->assertEquals($insertedCustomerAchTrans, $expectedCustomerAchTrans);
	}

	/**
	 * Test if the SettlementCustomerCreditsStep has been executed succesfully
	 */
	public function testExecutedSuccessfully() {
		$result = $this->SettlementCustomerCreditsStep->executedSuccessfully();
		$this->assertTrue($result);
	}
}