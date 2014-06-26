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

App::uses('CustomerTransPopulatedCheckStep', 'Model');

/**
 * Test warehouse.workflow_eod's field 'customer_trans_populated_check'
 */
class CustomerTransPopulatedCheckStepTest extends CakeTestCase {

	
/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CustomerTransPopulatedCheckStep =  new CustomerTransPopulatedCheckStep('2013-11-29');
		
	}

	
/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CustomerTransPopulatedCheckStep);
		parent::tearDown();
	}

	/**
	 * Test whether warehouse.workflow_eod's field 'customer_trans_populated_check' has been updated
	 * Good Case Scenario
	 */
	public function ptestExecuteInternal_Good() {
		$expected = null;
		$actual = $this->CustomerTransPopulatedCheckStep->executeInternal();
		$this->assertEquals($actual,$expected);

		$expResultEOD = array(
				array(
				'workflow_eod' => array(
				'id' => '2013-11-29',
				'is_business_day' => 'yes',
				'customer_trans_populated_check' => 'success')));

		$actResultEOD = $this->CustomerTransPopulatedCheckStep->query(
							"Select id, is_business_day , customer_trans_populated_check "
						. "FROM warehouse.workflow_eod where id = '2013-11-29'");

		$this->assertNotEmpty($actResultEOD);
		$this->assertEquals($actResultEOD,$expResultEOD);
		
	}
	
	/**
	 * Test whether method doesnt return anything
	 * Bad Case Scenario
	 */
	public function ptestExecuteInternal_Bad() {
		$expected = array();
		$actual = $this->CustomerTransPopulatedCheckStep->executeInternal();
		$this->assertEquals($actual,$expected);
	}
	
	
	
		/**
	 * Test whether warehouse.workflow_eod's field 'customer_trans_populated_check' has been updated
	 * Bad Case Scenario
	 */
//		public function ptestAtomicDbOperation_Bad() {
//		$method = new ReflectionMethod(
//					'CustomerTransPopulatedCheckStep', '__atomicDbOperation'
//				);
//		$method->setAccessible(true);
//		$actual = $method->invoke($this->CustomerTransPopulatedCheckStep);
//		$expResultEOD = array(
//				array(
//				'workflow_eod' => array(
//				'id' => '2013-11-30',
//				'is_business_day' => 'yes',
//				'customer_trans_populated_check' => 'success')));
//		
//		$actResultEOD = $this->CustomerTransPopulatedCheckStep->query(
//							"Select id, is_business_day , customer_trans_populated_check "
//						. "FROM warehouse.workflow_eod where id = '2013-11-30'");
//		
//		$this->assertNotEmpty($actResultEOD);
//		$this->assertEquals($actResultEOD,$expResultEOD);
//		
//	}
	
		/**
	 * Test whether CustomerTransPopulatedCheckStep has executed Succesfully
	 */
	public function testExecutedSuccessfully() {
		$actual = $this->CustomerTransPopulatedCheckStep->executedSuccessfully();

		$this->assertTrue($actual);
	}


	
}
