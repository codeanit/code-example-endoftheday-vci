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

App::uses('CurrentStatusEodInsertionStep', 'Model');
App::uses('CurrentStatusEndOfDay','Model');

/**
 *  Test case to Insert row in 'echecks'.'current_status_end_of_day' and
 *  Update 'eod_workflow'.'current_status_eod_insertion' as sucess or
 *  failure.
 */
class CurrentStatusEodInsertionStepTest extends CakeTestCase {

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$date = '2014-01-02';
		$this->CurrentStatusEodInsertionStep = new CurrentStatusEodInsertionStep($date);
		$this->CurrentStatusEndOfDay = new CurrentStatusEndOfDay();
	}

		/**
	 * Good test Test if the function have been executed sucessfully
	 */
	public function ptestExecutedSuccessfully() {
		$result = $this->CurrentStatusEodInsertionStep->executedSuccessfully();
		$this->assertFalse($result);
	}

	/**
	 * Bad test Test if the function have been executed sucessfully
	 */
	public function ptestExecutedSuccessfully_Bad() {
		$result = $this->CurrentStatusEodInsertionStep->executedSuccessfully();
		$this->assertTrue($result);
	}


	/**
	 * Set customer_transactions.origination_scheduled_date to null for all
	 * customer_transactions with status A,origination_scheduled_date = today and
	 * customer_transactions.standard_entry_class_code == ICL
	 * 
	 * @return boolean True if update sucessful else False
	 */
	public function testExecuteInternal_Good() {
		$expected = null;
		$numRecordsBefore = $this->CurrentStatusEndOfDay->find('count');

		$result = $this->CurrentStatusEodInsertionStep->executeInternal();
		$this->assertEquals($result, $expected);

		$expResultEOD = array(
			array(
				'workflow_eod' => array(
					'id' => '2014-01-02',
					'current_status_eod_insertion' => 'success'))
		);
		$actResultEOD = $this->CurrentStatusEodInsertionStep->query(
				"Select id,current_status_eod_insertion "
				. "FROM warehouse.workflow_eod where id = '2014-01-02'");
		$this->assertNotEmpty($actResultEOD);
		$this->assertEquals($actResultEOD, $expResultEOD);

		$numRecordsAfter = $this->CurrentStatusEndOfDay->find('count');
		$numRecordsBeforeAdded = $numRecordsBefore + 1;
		$this->assertTrue($numRecordsAfter == ($numRecordsBefore + 1));

		$readInserted= $this->CurrentStatusEndOfDay->find('first', array(
			'fields' => array('start_id','end_id','posted',
				),
			'conditions' => array('posted' =>'2014-01-02 00:00:00',
				'start_id' =>'100'))
			);
		$readExpected = array('CurrentStatusEndOfDay' =>array(
			'start_id' =>'100',
			'end_id' =>'200',
			'posted' =>'2014-01-02 00:00:00'
		));
		$this->assertNotNull($readInserted);
		$this->assertEqual($readExpected, $readInserted);
	}

	/**
	 * Good test to Test the Query to update 'warehouse'.'workflow_eod' table
	 */
	public function ptestGetEODUpdateQuery() {
		$date = '2013-11-26';
		$expected = "UPDATE warehouse.workflow_eod SET current_status_eod_insertion = 'success' WHERE id = '2013-11-26'";

		$method = new ReflectionMethod(
				'CurrentStatusEodInsertionStep', '__getEODUpdateQuery');
		$method->setAccessible(true);
		$actual = $method->invoke($this->CurrentStatusEodInsertionStep, $date);
		$this->assertEquals($actual, $expected);
		$this->assertNotNull($date);
	}

	/**
	 * Bad test to Test the Query to update 'warehouse'.'workflow_eod' table
	 * When the date is empty
	 */
	public function testGetEODUpdateQuery_Bad() {
		$date = '';
		$expected = null;
		$method = new ReflectionMethod(
				'CurrentStatusEodInsertionStep', '__getEODUpdateQuery');
		$method->setAccessible(true);
		$actual = $method->invoke($this->CurrentStatusEodInsertionStep, $date);
		$this->assertEquals($actual, $expected);
		$this->assertNotNull($date);
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->CurrentStatusEodInsertionStep);
		parent::tearDown();
	}

}

?>
