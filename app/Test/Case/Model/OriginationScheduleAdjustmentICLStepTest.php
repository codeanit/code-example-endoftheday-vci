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

App::uses('OriginationScheduleAdjustmentICLStep', 'Model');
App::uses('CustomerTransaction', 'Model');

class OriginationScheduleAdjustmentICLStepTest extends CakeTestCase {

	/**
	 * setUp method
	 * 
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$date = '2013-12-02';
		$this->OriginationScheduleAdjustmentICLStep = new OriginationScheduleAdjustmentICLStep($date);
		$this->CustomerTransaction = new CustomerTransaction();
	}

	/**
	 * Good test case to list customer transactions in A status and
	 *  merchant OrigTransHold = 1 of today 
	 * and set origination_scheduled_date to null
	 * 
	 * @return null if set origination_scheduled_date to null
	 */
	public function testExecuteInternal_Good() {
		$expected = null;
		$expResultEOD = array(
				array(
				'workflow_eod' => array(
				'id' => '2013-12-02',
				'origination_schedule_adjustment_icl' => 'success')));
		
		$actual = $this->OriginationScheduleAdjustmentICLStep->executeInternal();
		$this->assertEquals($actual, $expected);

		$actResultEOD = $this->OriginationScheduleAdjustmentICLStep->query(
							"Select id,origination_schedule_adjustment_icl "
						. "FROM warehouse.workflow_eod where id = '2013-12-02'");
		$this->assertNotEmpty($actResultEOD);
		$this->assertEquals($actResultEOD,$expResultEOD);

		$expResultCustTrans = array(
			'CustomerTransaction' =>array(
				'id' => '1001946',
				'origination_scheduled_date' => null)
			);
		$actResultCustTrans = $this->CustomerTransaction->find('first',
				array('fields' => array('id','origination_scheduled_date'),
					'conditions' => array('id' => '1001946'))
				);
		$this->assertNotEmpty($actResultCustTrans);
		$this->assertEquals($expResultCustTrans,$actResultCustTrans);
	}

	/**
	 * Bad test case to list customer transactions in A status and
	 *  merchant OrigTransHold = 1 of today 
	 * and set origination_scheduled_date to null
	 * 
	 * @return null if set origination_scheduled_date to null
	 */
	public function testExecuteInternal_Bad() {
		$expected = null;
		$expResultEOD = array(
				array(
				'workflow_eod' => array(
				'id' => '2013-12-02',
				'origination_schedule_adjustment_icl' => 'success')));
		
		$actual = $this->OriginationScheduleAdjustmentICLStep->executeInternal();
		$this->assertEquals($actual, $expected);

		$actResultEOD = $this->OriginationScheduleAdjustmentICLStep->query(
							"Select id,origination_schedule_adjustment_icl "
						. "FROM warehouse.workflow_eod where id = '2013-12-02'");
		$this->assertNotEmpty($actResultEOD);
		$this->assertNotEquals($actResultEOD,$expResultEOD);

		$expResultCustTrans = array(
			'CustomerTransaction' =>array(
				'id' => '1001946',
				'origination_scheduled_date' => '2013-12-02')
			);
		$actResultCustTrans = $this->CustomerTransaction->find('first',
				array('fields' => array('id','origination_scheduled_date'),
					'conditions' => array('id' => '1001946'))
				);
		$this->assertNotEmpty($actResultCustTrans);
		$this->assertEquals($expResultCustTrans,$actResultCustTrans);
	}

	/**
	 * Bad test case to list customer transactions in A status and
	 *  merchant OrigTransHold = 1 of today 
	 * and set origination_scheduled_date to null
	 * 
	 * @return true or emtpy if set origination_scheduled_date not set to null
	 */
	public function ptestExecuteInternal_Bad() {
		$expected = null;
		$actual = $this->OriginationScheduleAdjustmentICLStep->executeInternal();
		$this->assertEquals($actual, $expected);
	}

	public function ptestExecutedSuccessfully() {
		$result = $this->OriginationScheduleAdjustmentICLStep->executedSuccessfully();
		$this->assertFalse($result);
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->OriginationScheduleAdjustmentICLStep);
		parent::tearDown();
	}

}

?>
