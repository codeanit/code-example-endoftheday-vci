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

App::uses('BusinessDayStep', 'Model');

Class BusinessDayStepTest extends CakeTestCase {

	/**
	 *
	 * @var string 
	 */
	private $__date;

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$this->__date = '2013-11-21';

		$this->BusinessDayStep = new BusinessDayStep($this->__date);

	}

	/**
	 * tearDown method
	 * 
	 *  @return void
	 */
	public function tearDown() {
		unset($this->BusinessDayStep);
		parent::tearDown();
	}

	/**
	 * Good test case to check if 'workflow_eod'.'is_business_day' is updated 
	 * 
	 */
	public function testExecuteInternal() {
		$this->BusinessDayStep->executeInternal();

		$result = $this->BusinessDayStep->find('first',
						array('conditions' => array('id' => $this->__date)));

		$actual = $result['BusinessDayStep']['is_business_day'];
		$this->assertEqual($actual, 'yes');
	}


	/**
	 * Test BusinessDayStep->executedSuccesfully()
	 */
	public function ptestExecutedSuccessfully() {
		$actual = $this->BusinessDayStep->executedSuccessfully();
		$this->assertTrue($actual);
	}
}

?>
