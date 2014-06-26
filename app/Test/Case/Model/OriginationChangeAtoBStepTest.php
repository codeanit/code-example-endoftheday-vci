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

App::uses('OriginationChangeAToBStep', 'Model');
App::uses('CustomerTransaction', 'Model');

class OriginationChangeAToB extends CakeTestCase {

	
/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->OriginationChangeAToBStep =  new OriginationChangeAToBStep('2013-11-25');
		$this->CustomerTransaction =  new CustomerTransaction();
	}

	
/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OriginationChangeAToBStep);
		unset($this->CustomerTransaction);
		parent::tearDown();
	}

	/**
	 * Test whether method doesnt return anything
	 * Good Case Scenario
	 */
	public function testExecuteInternal() {
		$expected = null;
		$actual = $this->OriginationChangeAToBStep->executeInternal();
		$this->assertEquals($actual,$expected);
	}

	/**
	 * Test whether warehouse.workflow_eod's field 'customer_trans_populated_check' 
	 * and warehouse.customer_transactions's field 'effective_entry_date'
	 * 'origination_scheduled_date' and 'status' has been updated
	 * Good Case Scenario
	 */
	public function ptestAtomicDbOperation_Good() {
		$expected = null;
		$method = new ReflectionMethod(
					'OriginationChangeAToBStep', '__atomicDbOperation'
				);
		$method->setAccessible(true);
		$query = array("UPDATE warehouse.customer_transactions SET customer_transactions.status = 'B', "
							."customer_transactions.origination_actual_date = '2013-11-27', "
							."customer_transactions.effective_entry_date = '2013-12-02' "
							."where customer_transactions.id = '1001678'");
		
		$actual = $method->invoke($this->OriginationChangeAToBStep,$query);
		$this->assertEquals($actual,$expected);
		
		$expResultEOD = array(
				array(
				'workflow_eod' => array(
				'id' => '2013-11-27',
				'is_business_day' => 'yes',
				'origination_change_a_to_b' => 'success')));
		
		$actResultEOD = $this->OriginationChangeAToBStep->query(
							"Select id, is_business_day , origination_change_a_to_b "
						. "FROM warehouse.workflow_eod where id = '2013-11-27'");
		
		$this->assertEquals($actResultEOD,$expResultEOD);
		
		$expCusTrans = array(
				array(
				'CustomerTransaction' => array(
				'id' => '1001678',
				'effective_entry_date' => '2013-12-02',
				'origination_actual_date' => '2013-11-27',
				'status' => 'B')));
		
		$actCusTrans = $this->CustomerTransaction->find('all',
						array('fields' =>  array(
								'id',
								'effective_entry_date',
								'origination_actual_date',
								'status'),
								
								'conditions' => array (
										'id' => '1001678'
								)));
		
		$this->assertEquals($actCusTrans,$expCusTrans);
	}
	
/**
 * Test whether BusinessDayStep has executed Succesfully
 */
	public function ptestExecutedSuccessfully() {
		$actual = $this->OriginationChangeAToBStep->executedSuccessfully();
		$this->assertTrue($actual);
	}
	
}
