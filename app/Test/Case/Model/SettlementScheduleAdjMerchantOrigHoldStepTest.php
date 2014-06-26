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
App::uses('SettlementScheduleAdjMerchantOrigHoldStep', 'Model');
App::uses('SettlementWarehouse', 'Model');

class SettlementScheduleAdjMerchantOrigHoldStepTest extends CakeTestCase {

	/**
	 * setUp method
	 * 
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$date = '2013-12-05';
		$this->SettlementScheduleAdjMerchantOrigHoldStep = new SettlementScheduleAdjMerchantOrigHoldStep($date);
		$this->SettlementWarehouse = new SettlementWarehouse();
	}

	/**
	 * Test function to list SettlementWarehouse's data with 
	 * 'settlement_warehouse'.'settlement_schecduled_date' >= today and 
	 * 'settlement_warehouse'.'settlement_actual_date' = null and
	 * 'merchants'.'origTransHold' == 1
	 */
	public function testScheduleMerchantOrigHoldTransData() {
		$expected = array(
			'SettlementWarehouse' => array(
				'id' => '1',
				'settlement_scheduled_date' => '2013-12-06',
				'settlement_actual_date' => null
			)
		);
		$result = $this->SettlementWarehouse->getSettlementScheduleMerchantOrigHoldTrans(
				'2013-12-05',
				'1');
		$this->assertEquals($result[0], $expected);
	}

	/**
	 * Good test fucntion to list SettlementWarehouse's data with 
	 * 'settlement_warehouse'.'settlement_schecduled_date' >= today and 
	 * 'settlement_warehouse'.'settlement_actual_date' = null and
	 *  'merchants'.'origTransHold' == 1  and set 'settlement_scheduled_date'  to null
	 * Update 'eod_workflow'.'settlement_schedule_adjustment_merchant_orig_hold'
	 * to success
	 * 
	 */
	public function testExecuteInternal_Good() {
		$expected = null;
		$actual = $this->SettlementScheduleAdjMerchantOrigHoldStep->executeInternal();
		$this->assertEquals($actual, $expected);

		$expResultEOD = array(
			array(
				'workflow_eod' => array(
					'id' => '2013-12-05',
					'settlement_schedule_adjustment_merchant_orig_hold' => 'success')
				));

		$actResultEOD = $this->SettlementScheduleAdjMerchantOrigHoldStep->query(
				"Select id,settlement_schedule_adjustment_merchant_orig_hold "
				. "FROM warehouse.workflow_eod where id = '2013-12-05'");
		$this->assertNotEmpty($actResultEOD);
		$this->assertEquals($actResultEOD, $expResultEOD);

		$expResultCustTrans = array(
			'SettlementWarehouse' => array(
				'id' => '1',
				'settlement_scheduled_date' => null)
		);
		$actResultCustTrans = $this->SettlementWarehouse->find('first',
				array('fields' => array('id', 'settlement_scheduled_date '),
			'conditions' => array('id' => '1'))
		);
		$this->assertNotEmpty($actResultCustTrans);
		$this->assertEquals($actResultCustTrans, $expResultCustTrans);
	}

	/**
	 * Bad test fucntion to list SettlementWarehouse's data with 
	 * 'settlement_warehouse'.'settlement_schecduled_date' >= today and 
	 * 'settlement_warehouse'.'settlement_actual_date' = null and
	 *  'merchants'.'origTransHold' == 1  and set 'settlement_scheduled_date'  to null
	 * Update 'eod_workflow'.'settlement_schedule_adjustment_merchant_orig_hold'
	 * to success
	 * 
	 */
	public function ptestExecuteInternal_Bad() {
		$expected = null;
		$result = $this->SettlementScheduleAdjMerchantOrigHoldStep->executeInternal();
		$this->assertEquals($result, $expected);

		$expResultEOD = array(
			array(
				'workflow_eod' => array(
					'id' => '2013-12-05',
					'origination_schedule_adjustment_merchant_orig_hold' => 'success')
				));

		$actResultEOD = $this->SettlementScheduleAdjMerchantOrigHoldStep->query(
				"Select id,origination_schedule_adjustment_merchant_orig_hold "
				. "FROM warehouse.workflow_eod where id = '2013-12-05'");
		$this->assertNotEmpty($actResultEOD);
		$this->assertEquals($actResultEOD, $expResultEOD);

		$expResultCustTrans = array(
			'SettlementWarehouse' => array(
				'id' => '1',
				'settlement_scheduled_date' => null)
		);
		$actResultCustTrans = $this->SettlementWarehouse->find('first',
				array('fields' => array('id', 'settlement_scheduled_date'),
			'conditions' => array('id' => '1'))
		);
		$this->assertNotEmpty($actResultCustTrans);
		$this->assertNotEquals($actResultCustTrans, $expResultCustTrans);
	}

	/**
	 * Good test Test if the function have been executed sucessfully
	 */
	public function testExecutedSuccessfully() {
		$result = $this->SettlementScheduleAdjMerchantOrigHoldStep->executedSuccessfully();
		$this->assertFalse($result);
	}

	/**
	 * Good test Test if the function have been executed sucessfully
	 */
	public function ptestExecutedSuccessfully_Bad() {
		$result = $this->SettlementScheduleAdjMerchantOrigHoldStep->executedSuccessfully();
		$this->assertFalse($result);
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->SettlementScheduleAdjMerchantOrigHoldStep);
		parent::tearDown();
	}

}

?>
