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

App::uses('AddlateReturnsToMerchantAchTransactionsStep', 'Model');
App::uses('LateReturn', 'Model');
App::uses('MerchantAchTransaction', 'Model');

class AddlateReturnsToMerchantAchTransactionsStepTest extends CakeTestCase {

	/**
	 * setUp method
	 * 
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$this->date = '2013-12-09';
		$this->LateReturn = new LateReturn();
		$this->MerchantAchTransaction = new MerchantAchTransaction();
		$this->AddlateReturnsToMerchantAchTransactionsStep = new AddlateReturnsToMerchantAchTransactionsStep($this->date);
	}


	/**
	 * Check if Late returns are added in MerchantAchTransactions Table
	 * Check if the number of late returns and Rows Added in MerchantAchTransactions Table are equal
	 * Check if the LateReturn table is updated with recovered = 'yes' and recovered_date = today
	 * Check if the late return table is updated or inserted with number of rows effected
	 * Check id the field add_late_returns_to_merchant_ach_transactions is updated to success in 
	 * 'Warehosue'.'workflow_eod'
	 */
	public function testExecuteInternal_Good() {
		$expected = null;
		$expResultEOD = array(
				array(
				'workflow_eod' => array(
				'id' => '2013-12-09',
				'add_late_returns_to_merchant_ach_transactions' => 'success')));

		$lateReturnsData = $this->LateReturn->getLateReturnsToMerchAchQuery(
				'2013-12-09', 'no'
		);

		$expLateReturnData = 
			array('late_returns' => array(
				'recovered' => 'yes',
				'recovered_date' => '2013-12-09'
			)
		);

		$rowsCount = count($lateReturnsData);
		$beforeCntMerchantAch = $this->MerchantAchTransaction->find('count');
		$beforeCntLateReturns = $this->LateReturn->find('count');

		$actual = $this->AddlateReturnsToMerchantAchTransactionsStep->executeInternal();
		$this->assertEquals($actual, $expected);
		$afterCntMerchantAch = $this->MerchantAchTransaction->find('count');
		$afterCntLateReturns = $this->LateReturn->find('count');
		$this->assertTrue($afterCntMerchantAch == $beforeCntMerchantAch + $rowsCount);
		$this->assertTrue($afterCntLateReturns == $beforeCntLateReturns);

		$actResultEOD = $this->AddlateReturnsToMerchantAchTransactionsStep->query(
							"Select id,add_late_returns_to_merchant_ach_transactions "
						. "FROM warehouse.workflow_eod where id = '2013-12-09'");
		$this->assertNotEmpty($actResultEOD);
		$this->assertEquals($actResultEOD,$expResultEOD);
		
		$actResultLateReturns = $this->LateReturn->query(
				"Select recovered,recovered_date from warehouse.late_returns where return_date = '2013-12-09' "
				);
		$this->assertEquals($expLateReturnData,$actResultLateReturns[0]);
	}

	/**
	 * 
	 */
	public function ptestExecuteInternal_Bad() {
		$expected = null;
		$expResultEOD = array(
				array(
				'workflow_eod' => array(
				'id' => '2013-12-09',
				'add_late_returns_to_merchant_ach_transactions' => 'failure')));

		$lateReturnsData = $this->LateReturn->getLateReturnsToMerchAchQuery(
				'2013-12-09', 'no'
		);

		$expLateReturnData = array(
			array('late_returns' => array(
				'recovered' => 'no',
				'recovered_date' => '2013-12-09'
			))
		);

		$rowsCount = count($lateReturnsData);
		$beforeCntMerchantAch = $this->MerchantAchTransaction->find('count');
		$beforeCntLateReturns = $this->LateReturn->find('count');

		$actual = $this->AddlateReturnsToMerchantAchTransactionsStep->executeInternal();
		$this->assertNotEquals($actual, $expected);
		$afterCntMerchantAch = $this->MerchantAchTransaction->find('count');
		$afterCntLateReturns = $this->LateReturn->find('count');
		$this->assertFalse($afterCntMerchantAch == $beforeCntMerchantAch + $rowsCount);
		$this->assertFalse($afterCntLateReturns == $beforeCntLateReturns);

		$actResultEOD = $this->AddlateReturnsToMerchantAchTransactionsStep->query(
							"Select id,add_late_returns_to_merchant_ach_transactions "
						. "FROM warehouse.workflow_eod where id = '2013-12-09'");
		$this->assertNotEmpty($actResultEOD);
		$this->assertNotEquals($actResultEOD,$expResultEOD);
		
		$actResultLateReturns = $this->LateReturn->query(
				"Select recovered,recovered_date from warehouse.late_returns where return_date = '2013-12-09' "
				);
		$this->assertNotEquals($expLateReturnData,$actResultLateReturns[0]);
	}

	/**
	 * to test if the 'workflow_eod'.'add_late_returns_to_merchant_ach_transactions'
	 * is updated succesfully
	 */
	public function ptestExecutedSuccessfully() {
		$result = $this->AddlateReturnsToMerchantAchTransactionsStep->executedSuccessfully();
		$this->assertTrue($result);
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->AddlateReturnsToMerchantAchTransactionsStep);
		parent::tearDown();
	}
}
?>
