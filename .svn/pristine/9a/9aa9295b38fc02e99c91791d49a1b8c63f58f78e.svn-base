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

App::uses('MerchantAchTransactionMergeStep', 'Model');
App::uses('BackendTransaction', 'Model');
App::uses('MerchantAchTransaction', 'Model');

/**
 * Test warehouse.workflow_eod's field 'merchant_ach_transactions_merge'
 */
class MerchantAchTransactionMergeStepTest extends CakeTestCase {

	
/**
 * setUp methodPopulateSettlementWarehouseCreditsStepTest
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MerchantAchTransactionMergeStep =  new MerchantAchTransactionMergeStep('2013-12-09');
		$this->BackendTransaction = new BackendTransaction();
		$this->MerchantAchTransaction = new MerchantAchTransaction();
	}

	
/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MerchantAchTransactionMergeStep);
		unset($this->BackendTransaction);
		unset($this->MerchantAchTransaction);
		parent::tearDown();
	}

/**
 * Test whether Data has been updated and inserted into warehouse.merchant_ach_transaction and 
 * warehouse.workflow_eod has been updated
 */
	public function testExecuteInternal() {
		//$this->MerchantAchTransactionMergeStep->executeInternal();
		$expResultEOD = array(
				array(
				'workflow_eod' => array(
				'id' => '2013-12-09',
				'is_business_day' => 'yes',
				'merchant_ach_transactions_merge' => 'success')));
		
		$actResultEOD = $this->MerchantAchTransactionMergeStep->query(
							"Select id, is_business_day , merchant_ach_transactions_merge "
						. "FROM warehouse.workflow_eod where id = '2013-12-09'");
		
		$this->assertEquals($actResultEOD,$expResultEOD);

		$expBackendTrans = array(
				array(
				'BackendTransaction' => array(
				'id' => '6',
				'subtype'=> 'merchant_ach_transactions'))
				);
		
		$actBackendTrans = $this->BackendTransaction->find('all',
						array(
								'conditions' => array (
										'BackendTransaction.id' => '6'
								)));
		
		$this->assertEquals($actBackendTrans,$expBackendTrans);
		$expMerchTrans = array(
						array(
						'MerchantAchTransaction' => array(
						'id' => '10',
						'backend_transactions_id' => '6',
						'merchant_id' => '1085',
						'account_type' => 'operation',
						'transaction_type' => 'credit',
						'amount' => '9309.02',
						'mergeability' => 'all',
						'status' => 'pending',
						'merged_into_id' => null,
						'processing_scheduled_date' => '2013-12-09',
						'processing_actual_date' => null)));

		$actMerchTrans = $this->MerchantAchTransaction->find('all',
						array(
								'conditions' => array (
										'MerchantAchTransaction.id' => '10'
								)));
		$this->assertEquals($actMerchTrans,$expMerchTrans);
	}

	/**
 * Test whether MerchantAchTransactionMergeStep has executed Succesfully
 */
	public function ptestExecutedSuccessfully() {
		$actual = $this->MerchantAchTransactionMergeStep->executedSuccessfully();
		$this->assertTrue($actual);
		
	}
}