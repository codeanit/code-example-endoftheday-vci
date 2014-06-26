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

App::uses('SettlementScheduleAdjInactiveMerchantsStep', 'Model');
App::uses('SettlementWarehouse', 'Model');

/**
 * EOD class to set 'settlement_scheduled_date' to null to those data with
 * 'settlement_warehouse'.'settlement_scheduled_date' >= today 
 * and 'settlement_warehouse'.'settlement_actual_date' = null and 'merchants'.'active' = ‘0’
 * 
 * Upadte 'settlement_schedule_adjustment_inactive_merchants'
 */
class SettlementScheduleAdjInactiveMerchantsStepTest  extends CakeTestCase {
	
	/**
	 * setUp method
	 * 
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$date = '2013-12-05';
		$this->SettlementScheduleAdjInactiveMerchantsStep = new SettlementScheduleAdjInactiveMerchantsStep($date);
		$this->SettlementWarehouse = new SettlementWarehouse();
	}

	/**
	 * Test to set 'settlement_warehouse'.'settlement_scheduled_date ' to null for all transactions
	 * with 'settlement_warehouse'.'settlement_scheduled_date' = given and 'merchants'.'active'
	 * = 0 and 'settlement_warehouse'.'settlement_actual_date' = null .
	 * Also Update 'eod_workflow'.settlement_schedule_adjustment_inactive_merchants
	 * to sucess
	 */
	public function testExecuteInternal_Good() {
		$expected = null;
		$expResultEOD = array(
				array(
				'workflow_eod' => array(
				'id' => '2013-12-05',
				'settlement_schedule_adjustment_inactive_merchants' => 'success')));

		$actual = $this->SettlementScheduleAdjInactiveMerchantsStep->executeInternal();
		$this->assertEquals($actual, $expected);

		$actResultEOD = $this->SettlementScheduleAdjInactiveMerchantsStep->query(
			"Select id,settlement_schedule_adjustment_inactive_merchants "
			. "FROM warehouse.workflow_eod where id = '2013-12-05'");
		$this->assertNotEmpty($actResultEOD);
		$this->assertEquals($actResultEOD,$expResultEOD);

		$expResultCustTrans = array(
			'SettlementWarehouse' =>array(
				'id' => '1',
				'settlement_scheduled_date' => null)
			);
		$actResultCustTrans = $this->SettlementWarehouse->find('first',
				array('fields' => array('id','settlement_scheduled_date'),
					'conditions' => array('id' => '1'))
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
	public function testExecuteInternal_Bad() {
		$expected = null;
		$actual = $this->SettlementScheduleAdjInactiveMerchantsStep->executeInternal();
		$this->assertFalse($actual, $expected);

		$expResultEOD = array(
				array(
				'workflow_eod' => array(
				'id' => '2013-12-05',
				'settlement_schedule_adjustment_inactive_merchants' => 'success')));

		$actual = $this->SettlementScheduleAdjInactiveMerchantsStep->executeInternal();
		$this->assertEquals($actual, $expected);

		$actResultEOD = $this->SettlementScheduleAdjInactiveMerchantsStep->query(
			"Select id,settlement_schedule_adjustment_inactive_merchants "
			. "FROM warehouse.workflow_eod where id = '2013-12-05'");
		$this->assertNotEquals($actResultEOD,$expResultEOD);

		$expResultCustTrans = array(
			'SettlementWarehouse' =>array(
				'id' => '1',
				'settlement_scheduled_date' => null)
			);
		$actResultCustTrans = $this->SettlementWarehouse->find('first',
				array('fields' => array('id','settlement_scheduled_date'),
					'conditions' => array('id' => '1'))
				);
		$this->assertNotEquals($expResultCustTrans,$actResultCustTrans);
	}

	/**
	 * Test if the step has been executed sucessfully
	 */
	public function testExecutedSuccessfully() {
		$result = $this->SettlementScheduleAdjInactiveMerchantsStep->executedSuccessfully();
		$this->assertTrue($result);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SettlementScheduleAdjInactiveMerchantsStep);
		parent::tearDown();
	}
}

?>
