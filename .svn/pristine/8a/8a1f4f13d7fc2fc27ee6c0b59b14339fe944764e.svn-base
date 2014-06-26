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

	App::uses('OriginationCustomerCreditsStep', 'Model');
	App::uses('Origination', 'Model');
	App::uses('BackendTransaction', 'Model');
	App::uses('MerchantAchTransaction', 'Model');

class OriginationCustomerCreditsStepTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->OriginationCustomerCreditsStep = new OriginationCustomerCreditsStep('2013-11-29');
		$this->Origination = new Origination();
		$this->BackendTransaction = new BackendTransaction();
		$this->MerchantAchTransaction = new MerchantAchTransaction();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OriginationCustomerCreditsStep);
		unset($this->Origination);
		unset($this->BackendTransaction);
		unset($this->MerchantAchTransaction);
		parent::tearDown();
	}

/**
	 * Test whether warehouse.workflow_eod's 'origination_customer_credits' has been updated or not And
	 * Data in warehouse.backend_transactions, warehouse_originations, 
	 * warehouse.merchant_ach_transactions has been inserted or not.
	 * Good Case Scenario
	 */
	public function testExecuteInternal() {
		$this->OriginationCustomerCreditsStep->executeInternal();
			$expResultEOD = array(
				array(
				'workflow_eod' => array(
				'id' => '2013-11-29',
				'is_business_day' => 'yes',
				'origination_customer_credits' => 'success')));
		
		$actResultEOD = $this->OriginationCustomerCreditsStep->query(
							"Select id, is_business_day , origination_customer_credits "
						. "FROM warehouse.workflow_eod where id = '2013-11-29'");
		
		$this->assertEquals($actResultEOD,$expResultEOD);
		
		$expBackendTrans = array(
				array(
				'BackendTransaction' => array(
				'id' => '3',
				'subtype'=> 'merchant_ach_transactions'))
				);
		
		$actBackendTrans = $this->BackendTransaction->find('all',
						array(
								'conditions' => array (
										'BackendTransaction.id' => '3'
								)));
		
		$this->assertEquals($actBackendTrans,$expBackendTrans);
//
//		$expOrig = array(
//				array(
//				'Origination' => array(
//				'id' => '5',
//				'backend_transactions_id' => '30',
//				'origination_batches_customer_transaction_id'=> '20',
//				'notes' => 'EOD Process')));
//		
//		$actOrig = $this->Origination->find('all',
//						array(
//								'conditions' => array (
//										'Origination.id' => '5'
//								)));
//		
//		$this->assertEquals($actOrig,$expOrig);
//		
//		$expMerchTrans = array(
//				array(
//				'MerchantAchTransaction' => array(
//				'id' => '5',
//				'backend_transactions_id' => '30',
//				'merchant_id' => '1154',
//				'account_type' => 'operation',
//				'transaction_type' => 'debit',
//				'amount' => '4032.10',
//				'mergeability' => 'all',
//				'status' => 'pending',
//				'merged_into_id' => '0',
//				'processing_scheduled_date' => '2013-11-29',
//				'processing_actual_date' => '0000-00-00')));
//		
//		$actMerchTrans = $this->MerchantAchTransaction->find('all',
//						array(
//								'conditions' => array (
//										'MerchantAchTransaction.id' => '5'
//								)));
//		
//		$this->assertEquals($actMerchTrans,$expMerchTrans);
		//$this->assertEquals($actual,$expected);
	}

	/**
 * Test whether OriginationCustomerCreditsStep has executed Succesfully
 */
	public function ptestExecutedSuccessfully() {
		$actual = $this->OriginationCustomerCreditsStep->executedSuccessfully();
		$this->assertTrue($actual);
	}
	

}
