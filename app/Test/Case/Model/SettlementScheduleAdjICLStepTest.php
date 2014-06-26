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

App::uses('CustomerTransaction', 'Model');
App::uses('SettlementScheduleAdjICLStep', 'Model');
App::uses('SettlementWarehouse', 'Model');

class SettlementScheduleAdjICLStepTest extends CakeTestCase {

	/**
	 * setUp method
	 * 
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$date = '2013-12-05';
		$this->CustomerTransaction = new CustomerTransaction();
		$this->SettlementScheduleAdjICLStep = new SettlementScheduleAdjICLStep($date);
		$this->SettlementWarehouse = new SettlementWarehouse();
	}

	/**
	 * Good test to set 'settlement_warehouse.settlement_scheduled_date' to null for all
	 * 'settlement_warehouse'.'settlement_scheduled_date' >= today and 
	 * 'customer_transactions'.'standard_entry_class_code' = ICL  
	 * and update 'workflow_eod'.'settlement_schedule_adjustment_icl' to success
	 * 
	 */
	public function testExecuteInternal_Good() {
		$expected = null;
		$expResultEOD = array(
				array(
				'workflow_eod' => array(
				'id' => '2013-12-05',
				'settlement_schedule_adjustment_icl' => 'success')));
		
		$actual = $this->SettlementScheduleAdjICLStep->executeInternal();
		$this->assertEquals($actual, $expected);

		$actResultEOD = $this->SettlementScheduleAdjICLStep->query(
							"Select id,settlement_schedule_adjustment_icl "
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
	 * 'customer_transactions'.'standard_entry_class_code' = ICL  
	 *  and update 'workflow_eod'.'settlement_schedule_adjustment_icl' to success
	 * 
	 */
	public function testExecuteInternal_Bad() {
		$expected = null;
		$expResultEOD = array(
				array(
				'workflow_eod' => array(
				'id' => '2013-12-05',
				'settlement_schedule_adjustment_icl' => 'success')));
		
		$actual = $this->SettlementScheduleAdjICLStep->executeInternal();
		$this->assertEquals($actual, $expected);

		$actResultEOD = $this->SettlementScheduleAdjICLStep->query(
							"Select id,settlement_schedule_adjustment_icl "
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
	 * to test if the 'workflow_eod'.'settlement_schedule_adjustment_icl'
	 * is updated succesfully
	 */
	public function testExecutedSuccessfully() {
		$result = $this->SettlementScheduleAdjICLStep->executedSuccessfully();
		$this->assertTrue($result);
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->SettlementScheduleAdjICLStep);
		parent::tearDown();
	}
}
?>
