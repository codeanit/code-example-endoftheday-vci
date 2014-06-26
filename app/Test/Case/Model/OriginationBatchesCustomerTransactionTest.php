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

App::uses('OriginationBatchesCustomerTransaction', 'Model');

class OriginationBatchesCustomerTransactionTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->OriginationBatchesCustomerTransaction =  new OriginationBatchesCustomerTransaction();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OriginationBatchesCustomerTransaction);
		parent::tearDown();
	}
	
	/**
	 * Test whether the required Transaction Ids  from warehouse.customer_transactions table has been fetched or not
	 * Good Case Scenario
	 */
	public function ptestGetScheduledOriginationsForDay_Good() {
		$expectedData = array('1001934', '1001938', '1001939', '1001940');
		$data = $this->OriginationBatchesCustomerTransaction->getScheduledOriginationsForDay('2013-11-29','credit');
		$this->assertNotEmpty($data);
		$this->assertEquals($expectedData, $data);
	}
	
	/**
	 * Test whether the required Transaction Ids from warehouse.customer_transactions table has been fetched or not
	 * Bad Case Scenario
	 */
	public function ptestGetScheduledOriginationsForDay_Bad() {
		$expectedData = array('1001677', '1001679');
		$data = $this->OriginationBatchesCustomerTransaction->getScheduledOriginationsForDay('2013-11-27','creditsdad');
		$this->assertNotEmpty($data);
		$this->assertEquals($expectedData, $data);
	}

	/**
	 * Test the result of max and min customer_transactions_id in provided Date
	 */
	public function ptestGetStartEndCustomerTransactions() {
		$expectedData = array('startTransId' => '1001934',
				'endTransId' => '1001942');
		$outputData = $this->OriginationBatchesCustomerTransaction->getStartEndCustomerTransactions('2013-11-29');
		$this->assertNotEmpty($CustTrans);
		$this->assertEquals($expectedData, $outputData);
	}
	
	/**
	 * Test whether TransactionIds from Todays Batch has been fetched or not
	 * Good Case scenario
	 */
	public function testGetSettlementTransactionsIdsForDay_Good() {
		$expectedData = array('1001995','1001996','1001997','1001998','1001999',
				'1002001','1002002','1002008','1002009','1002012');
		$actualData = $this->OriginationBatchesCustomerTransaction->getSettlementTransactionsIdsForDay('2013-12-03');
		$this->assertNotEmpty($actualData);
		$this->assertEquals($expectedData, $actualData);
	}
	
	/**
	 * Test whether TransactionIds from Todays Batch has been fetched or not
	 * Good Case scenario
	 */
	public function testGetSettlementTransactionsIdsForDay_Bad() {
		$expectedData = array('10019951111','1001996','1001997','1001998','1001999324',
				'10020014324','100200232','10020084444','1002009','10020124234');
		$actualData = $this->OriginationBatchesCustomerTransaction->getSettlementTransactionsIdsForDay('2013-15-03');
		$this->assertNotEmpty($actualData);
		$this->assertEquals($expectedData, $actualData);
	}
}