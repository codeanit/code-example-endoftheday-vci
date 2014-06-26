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

App::uses('SettlementScheduleAdjMerchantSettleHoldStep', 'Model');
App::uses('SettlementWarehouse', 'Model');

class SettlementScheduleAdjMerchantSettleHoldStepTest extends CakeTestCase {

	/**
	 * setUp method
	 * 
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$date = '2013-12-05';
		$this->SettlementScheduleAdjMerchantSettleHoldStep = new SettlementScheduleAdjMerchantSettleHoldStep($date);
		$this->SettlementWarehouse = new SettlementWarehouse();
	}

	/**
	 * Good test to set 'settlement_warehouse.settlement_scheduled_date' to null for all
	 * 'settlement_warehouse'.'settlement_scheduled_date' >= today and 
	 * 'settlement_warehouse'.'settlement_actual_date' = null 
	 * and 'merchants'.'funding_time' = ‘HOLD’ and update workflow_eod table
	 * 
	 */
	public function testExecuteInternal_Good() {
		$expected = null;
		$expResultEOD = array(
				array(
				'workflow_eod' => array(
				'id' => '2013-12-05',
				'settlement_schedule_adjustment_merchant_settle_hold' => 'success')));
		
		$actual = $this->SettlementScheduleAdjMerchantSettleHoldStep->executeInternal();
		$this->assertEquals($actual, $expected);

		$actResultEOD = $this->SettlementScheduleAdjMerchantSettleHoldStep->query(
							"Select id,settlement_schedule_adjustment_merchant_settle_hold "
						. "FROM warehouse.workflow_eod where id = '2013-12-05'");
		$this->assertNotEmpty($actResultEOD);
		$this->assertEquals($actResultEOD,$expResultEOD);

		$expResultCustTrans = array(
			'SettlementWarehouse' =>array(
				'id' => '1',
				'settlement_scheduled_date' => null)
			);
		$actResultCustTrans = $this->SettlementWarehouse->find('first',
				array('fields' => array('id','settlement_scheduled_date '),
					'conditions' => array('id' => '1'))
				);
		$this->assertNotEmpty($actResultCustTrans);
		$this->assertEquals($expResultCustTrans,$actResultCustTrans);
	}

	/**
	 * Bad test to set 'settlement_warehouse.settlement_scheduled_date' to null for all
	 * 'settlement_warehouse'.'settlement_scheduled_date' >= today and 
	 * 'settlement_warehouse'.'settlement_actual_date' = null 
	 * and 'merchants'.'funding_time' = ‘HOLD’ and update workflow_eod table
	 * 
	 */
	public function testExecuteInternal_Bad() {
		$expected = null;
		$expResultEOD = array(
				array(
				'workflow_eod' => array(
				'id' => '2013-12-05',
				'settlement_schedule_adjustment_merchant_settle_hold' => 'success')));
		
		$actual = $this->SettlementScheduleAdjMerchantSettleHoldStep->executeInternal();
		$this->assertEquals($actual, $expected);

		$actResultEOD = $this->SettlementScheduleAdjMerchantSettleHoldStep->query(
							"Select id,settlement_schedule_adjustment_merchant_settle_hold "
						. "FROM warehouse.workflow_eod where id = '2013-12-05'");
		$this->assertNotEmpty($actResultEOD);
		$this->assertNotEquals($actResultEOD,$expResultEOD);

		$expResultCustTrans = array(
			'SettlementWarehouse' =>array(
				'id' => '1',
				'settlement_scheduled_date ' => '2013-12-05')
			);
		$actResultCustTrans = $this->SettlementWarehouse->find('first',
				array('fields' => array('id','settlement_scheduled_date '),
					'conditions' => array('id' => '1'))
				);
		$this->assertNotEmpty($actResultCustTrans);
		$this->assertEquals($expResultCustTrans,$actResultCustTrans);
	}

	/**
	 * to test if the 'workflow_eod'.'settlement_schedule_adjustment_merchant_settle_hold'
	 * is updated succesfully
	 */
	public function testExecutedSuccessfully() {
		$result = $this->SettlementScheduleAdjMerchantSettleHoldStep->executedSuccessfully();
		$this->assertTrue($result);
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->SettlementScheduleAdjMerchantSettleHoldStep);
		parent::tearDown();
	}
}
?>
