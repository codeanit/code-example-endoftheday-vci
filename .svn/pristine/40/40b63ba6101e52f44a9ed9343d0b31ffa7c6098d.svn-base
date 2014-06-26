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

App::uses('CSVMerchantAchTransactionsStep', 'Model');

/**
 * Test warehouse.workflow_eod's field 'csv_merchant_ach_transactions'
 */
class CSVMerchantAchTransactionStepTest extends CakeTestCase {

	
/**
 * setUp methodCSVMerchantAchTransactionStepTest
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CSVMerchantAchTransactionsStep =  new CSVMerchantAchTransactionsStep('2013-12-09');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CSVMerchantAchTransactionsStep);
		parent::tearDown();
	}

/**
 * Test whether warehouse.workflow_eod has been updated
 */
	public function testExecuteInternal() {
		$this->CSVMerchantAchTransactionsStep->executeInternal();
	}

	/**
 * Test whether CSVMerchantAchTransactionsStep has executed Succesfully
 */
	public function ptestExecutedSuccessfully() {
		$actual = $this->CSVMerchantAchTransactionsStep->executedSuccessfully();
		$this->assertTrue($actual);
		
	}
}