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
App::uses('OriginationScheduleMerchantOrigHoldStep', 'Model');
App::uses('CustomerTransaction', 'Model');

class OriginationScheduleMerchantOrigHoldStepTest extends CakeTestCase {

	/**
	 * setUp method
	 * 
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$date = '2013-12-02';
		$this->OriginationScheduleMerchantOrigHoldStep = new OriginationScheduleMerchantOrigHoldStep($date);
		$this->CustomerTransaction = new CustomerTransaction();
	}

	/**
	 * Good test case to list customer transactions in A status and
	 *  merchant OrigTransHold = 1 of today 
	 * Test to set 'customer_transactions'.'origination_scheduled_date to null.'
	 * to null and 
	 * update 'EodWorkflow'.'origination_schedule_adjustment_merchant_orig_hold' 
	 * to 'sucess'
	 */
	public function testExecuteInternal_Good() {
		$expected = null;
		$result = $this->OriginationScheduleMerchantOrigHoldStep->executeInternal();
		$this->assertEquals($result, $expected);

		$expResultEOD = array(
			array(
				'workflow_eod' => array(
					'id' => '2013-12-02',
					'origination_schedule_adjustment_merchant_orig_hold' => 'success')
				));

		$actResultEOD = $this->OriginationScheduleMerchantOrigHoldStep->query(
				"Select id,origination_schedule_adjustment_merchant_orig_hold "
				. "FROM warehouse.workflow_eod where id = '2013-12-02'");
		$this->assertNotEmpty($actResultEOD);
		$this->assertEquals($actResultEOD, $expResultEOD);

		$expResultCustTrans = array(
			'CustomerTransaction' => array(
				'id' => '1001944',
				'origination_scheduled_date' => null)
		);
		$actResultCustTrans = $this->CustomerTransaction->find('first',
				array('fields' => array('id', 'origination_scheduled_date'),
			'conditions' => array('id' => '1001944'))
		);
		$this->assertNotEmpty($actResultCustTrans);
		$this->assertEquals($actResultCustTrans, $expResultCustTrans);
	}

	/**
	 * Bad test case to list customer transactions in A status and
	 *  merchant OrigTransHold = 1 of today 
	 * Test to set 'customer_transactions'.'origination_scheduled_date to null.'
	 * to null and 
	 * update 'EodWorkflow'.'origination_schedule_adjustment_merchant_orig_hold' 
	 * to 'sucess'
	 */
	public function ptestExecuteInternal_Bad() {
		$expected = null;
		$result = $this->OriginationScheduleMerchantOrigHoldStep->executeInternal();
		$this->assertEquals($result, $expected);

		$expResultEOD = array(
			array(
				'workflow_eod' => array(
					'id' => '2013-12-02',
					'origination_schedule_adjustment_merchant_orig_hold' => 'success')
				));

		$actResultEOD = $this->OriginationScheduleMerchantOrigHoldStep->query(
				"Select id,origination_schedule_adjustment_merchant_orig_hold "
				. "FROM warehouse.workflow_eod where id = '2013-12-02'");
		$this->assertNotEmpty($actResultEOD);
		$this->assertEquals($actResultEOD, $expResultEOD);

		$expResultCustTrans = array(
			'CustomerTransaction' => array(
				'id' => '1001944',
				'origination_scheduled_date' => null)
		);
		$actResultCustTrans = $this->CustomerTransaction->find('first',
				array('fields' => array('id', 'origination_scheduled_date'),
			'conditions' => array('id' => '1001944'))
		);
		$this->assertNotEmpty($actResultCustTrans);
		$this->assertNotEquals($actResultCustTrans, $expResultCustTrans);
	}

	/**
	 * Good test Test if the function have been executed sucessfully
	 */
	public function testExecutedSuccessfully() {
		$result = $this->OriginationScheduleMerchantOrigHoldStep->executedSuccessfully();
		$this->assertFalse($result);
	}

	/**
	 * Good test Test if the function have been executed sucessfully
	 */
	public function ptestExecutedSuccessfully_Bad() {
		$result = $this->OriginationScheduleMerchantOrigHoldStep->executedSuccessfully();
		$this->assertTrue($result);
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->OriginationScheduleMerchantOrigHold);
		parent::tearDown();
	}

}

?>
