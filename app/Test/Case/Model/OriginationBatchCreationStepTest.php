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

App::uses('OriginationBatchCreationStep', 'Model');
App::uses('CustomerTransaction', 'Model');
App::uses('OriginationBatchesCustomerTransaction', 'Model');
App::uses('OriginationBatch', 'Model');

class OriginationBatchCreationStepTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->OriginationBatchCreationStep =  new OriginationBatchCreationStep('2013-11-29');
		$this->CustomerTransaction =  new CustomerTransaction();
		$this->OriginationBatchesCustomerTransaction =  new OriginationBatchesCustomerTransaction();
		$this->OriginationBatch =  new OriginationBatch();
	}

	
/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OriginationBatchCreationStep);
		unset($this->CustomerTransaction);
		unset($this->OriginationBatchesCustomerTransaction);
		unset($this->OriginationBatch);
		parent::tearDown();
	}

	/**
	 * Test whether warehouse.workflow_eod's field 'origination_batch_creation' 
	 * and warehouse.customer_transactions's field 'effective_entry_date'
	 * 'origination_scheduled_date' and 'status' has been updated
	 * And check new data are inserted in warehouse.origination_batches_customer_transactions and 
	 * warehouse.origination_batches
	 * Good Case Scenario
	 */
	public function testExecuteInternal_Good() {
		$expected = null;
		$actual = $this->OriginationBatchCreationStep->executeInternal();
		$this->assertEquals($actual,$expected);
		$expResultEOD = array(
				array(
				'workflow_eod' => array(
				'id' => '2013-11-29',
				'is_business_day' => 'yes',
				'origination_batch_creation' => 'success')));
		
		$actResultEOD = $this->OriginationBatchCreationStep->query(
							"Select id, is_business_day , origination_batch_creation "
						. "FROM warehouse.workflow_eod where id = '2013-11-29'");
		
		$this->assertEquals($actResultEOD,$expResultEOD);
//		
		$expCusTrans = array(
				array(
				'CustomerTransaction' => array(
				'id' => '1001934',
				'effective_entry_date' => '2013-12-16',
				'origination_actual_date' => '2013-11-29',
				'status' => 'B')));
		
		$actCusTrans = $this->CustomerTransaction->find('all',
						array('fields' =>  array(
								'id',
								'effective_entry_date',
								'origination_actual_date',
								'status'),
								
								'conditions' => array (
										'id' => '1001934'
								)));
		
		$this->assertEquals($actCusTrans,$expCusTrans);
		
		$expOrigBatch = array(
				array(
				'OriginationBatch' => array(
				'id' => '5',
				'process_date' => '2013-11-29',
				'effective_date' => '2013-12-02',
				)));
		
		$actOrigBatch = $this->OriginationBatch->find('all',
						array('fields' =>  array(
								'id',
								'process_date',
								'effective_date',
								),
								
								'conditions' => array (
										'id' => '5'
								)));
		
		$this->assertEquals($actOrigBatch,$expOrigBatch);
		
		$expOrigBatchCustTrans = array(
				array(
				'OriginationBatchesCustomerTransaction' => array(
				'origination_batches_id' => '5',
				'customer_transactions_id' => '1001934',
				
				)));
		
		$actOrigBatchCustTrans = $this->OriginationBatchesCustomerTransaction->find('all',
						array('fields' =>  array(
								'origination_batches_id',
								'customer_transactions_id',
								),
								
								'conditions' => array (
										'customer_transactions_id' => '1001934'
								)));
		
		$this->assertEquals($actOrigBatchCustTrans,$expOrigBatchCustTrans);
	
	}
	
/**
 * Test whether OriginationBatchCreationStep has executed Succesfully
 */
	public function ptestExecutedSuccessfully() {
		$actual = $this->OriginationBatchCreationStep->executedSuccessfully();
		$this->assertTrue($actual);
		
	}

}