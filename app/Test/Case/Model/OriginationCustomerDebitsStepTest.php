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
 * @version $$Id$$
 */

	App::uses('OriginationCustomerDebitsStep', 'Model');
	App::uses('Origination', 'Model');
	App::uses('BackendTransaction', 'Model');
	App::uses('CustomerAchTransaction', 'Model');

class OriginationCustomerDebitsStepTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->OriginationCustomerDebitsStep = new OriginationCustomerDebitsStep('2013-11-29');
		$this->Origination = new Origination();
		$this->BackendTransaction = new BackendTransaction();
		$this->CustomerAchTransaction = new CustomerAchTransaction();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OriginationCustomerDebitsStep);
		unset($this->Origination);
		unset($this->BackendTransaction);
		unset($this->CustomerAchTransaction);
		parent::tearDown();
	}

/**
 * Test whether warehouse.workflow_eod's 'origination_customer_debits' has been updated or not And
 * Data in warehouse.backend_transactions, warehouse_originations, 
 * warehouse.customer_ach_transactions has been inserted or not.
 * Good Case Scenario
 */
	public function testExecuteInternal() {
		//$this->OriginationCustomerDebitsStep->executeInternal();
		$expResultEOD = array(
				array(
				'workflow_eod' => array(
				'id' => '2013-11-29',
				'is_business_day' => 'yes',
				'origination_customer_debits' => 'success')));
		
		$actResultEOD = $this->OriginationCustomerDebitsStep->query(
							"Select id, is_business_day , origination_customer_debits "
						. "FROM warehouse.workflow_eod where id = '2013-11-29'");
		
		$this->assertEquals($actResultEOD,$expResultEOD);
		
		$expBackendTrans = array(
				array(
				'BackendTransaction' => array(
				'id' => '34',
				'subtype'=> 'customer_ach_transactions'))
				);
		
		$actBackendTrans = $this->BackendTransaction->find('all',
						array(
								'conditions' => array (
										'BackendTransaction.id' => '34'
								)));
		
		$this->assertEquals($actBackendTrans,$expBackendTrans);

		$expOrig = array(
				array(
				'Origination' => array(
				'id' => '9',
				'backend_transactions_id' => '34',
				'origination_batches_customer_transaction_id'=> '21',
				'notes' => 'EOD Debit Process')));
		
		$actOrig = $this->Origination->find('all',
						array(
								'conditions' => array (
										'Origination.id' => '9'
								)));
		
		$this->assertEquals($actOrig,$expOrig);
		
		$expCustTrans = array(
				array(
				'CustomerAchTransaction' => array(
				'id' => '1',
				'customer_transactions_id' => '1001935',
				'backend_transactions_id' => '34',
				'transaction_type' => 'debit',
				'amount' => '242.98',
				'status' => 'pending',
				'processing_scheduled_date' => '2013-11-29',
				'processing_actual_date' => '0000-00-00')));
		
		$actCustTrans = $this->CustomerAchTransaction->find('all',
						array(
								'conditions' => array (
										'CustomerAchTransaction.id' => '1'
								)));
		
		$this->assertEquals($actCustTrans,$expCustTrans);
		
	}

/**
 * Test whether OriginationCustomerDebitsStep has executed Succesfully
 */
	public function ptestExecutedSuccessfully() {
		$actual = $this->OriginationCustomerDebitsStep->executedSuccessfully();
		$this->assertTrue($actual);
	}

}
