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

App::uses('OriginationScheduleAdjustmentInactiveMerchantsStep', 'Model');
App::uses('CustomerTransaction', 'Model');

class OriginationScheduleAdjustmentInactiveMerchantsStepTest  extends CakeTestCase {
	
	/**
	 * setUp method
	 * 
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$date = '2013-12-02';
		$this->OriginationScheduleAdjustmentInactiveMerchantsStep = new OriginationScheduleAdjustmentInactiveMerchantsStep($date);
		$this->CustomerTransaction = new CustomerTransaction();
	}

	/**
	 * Test to set 'customer'.'origination_scheduled_date' to null for all transactions
	 * with status A and origination_scheduled_date = given and 'merchants'.'active'
	 * <> 1.
	 * Also Update 'eod_workflow'.origination_schedule_adjustment_inactive_merchants
	 * to sucess
	 * 
	 */
	public function testExecuteInternal_Good() {
		$expected = null;
		$expResultEOD = array(
				array(
				'workflow_eod' => array(
				'id' => '2013-12-02',
				'origination_schedule_adjustment_inactive_merchants' => 'success')));

		$actual = $this->OriginationScheduleAdjustmentInactiveMerchantsStep->executeInternal();
		$this->assertEquals($actual, $expected);

		$actResultEOD = $this->OriginationScheduleAdjustmentInactiveMerchantsStep->query(
			"Select id,origination_schedule_adjustment_inactive_merchants "
			. "FROM warehouse.workflow_eod where id = '2013-12-02'");
		$this->assertNotEmpty($actResultEOD);
		$this->assertEquals($actResultEOD,$expResultEOD);

		$expResultCustTrans = array(
			'CustomerTransaction' =>array(
				'id' => '1001943',
				'origination_scheduled_date' => null)
			);
		$actResultCustTrans = $this->CustomerTransaction->find('first',
				array('fields' => array('id','origination_scheduled_date'),
					'conditions' => array('id' => '1001943'))
				);
		$this->assertNotEmpty($actResultCustTrans);
		$this->assertEquals($expResultCustTrans,$actResultCustTrans);
	}

	/**
	 * Bad test case to list customer transactions in A status and
	 *  merchant OrigTransHold = 1 of today 
	 * and set origination_scheduled_date to null
	 * 
	 */
	public function ptestExecuteInternal_Bad() {
		$expected = null;
		$actual = $this->OriginationScheduleAdjustmentInactiveMerchantsStep->executeInternal();
		$this->assertFalse($actual, $expected);
		
		$expResultEOD = array(
				array(
				'workflow_eod' => array(
				'id' => '2013-12-02',
				'origination_schedule_adjustment_inactive_merchants' => 'success')));

		$actual = $this->OriginationScheduleAdjustmentInactiveMerchantsStep->executeInternal();
		$this->assertEquals($actual, $expected);

		$actResultEOD = $this->OriginationScheduleAdjustmentInactiveMerchantsStep->query(
			"Select id,origination_schedule_adjustment_inactive_merchants "
			. "FROM warehouse.workflow_eod where id = '2013-12-02'");
		$this->assertNotEquals($actResultEOD,$expResultEOD);

		$expResultCustTrans = array(
			'CustomerTransaction' =>array(
				'id' => '1001943',
				'origination_scheduled_date' => null)
			);
		$actResultCustTrans = $this->CustomerTransaction->find('first',
				array('fields' => array('id','origination_scheduled_date'),
					'conditions' => array('id' => '1001943'))
				);
		$this->assertNotEquals($expResultCustTrans,$actResultCustTrans);
	}

	/**
	 * Test if the step has been executed sucessfully
	 */
	public function ptestExecutedSuccessfully() {
		$result = $this->OriginationScheduleAdjustmentInactiveMerchantsStep->executedSuccessfully();
		$this->assertFalse($result);
	}

	/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OriginationScheduleAdjustmentInactiveMerchantsStep);
		parent::tearDown();
	}
}

?>
