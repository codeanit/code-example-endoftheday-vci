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

App::uses('EODStepTest', 'Test/Case/Lib/EOD');

class EODStepExampleTest extends CakeTestCase {

		public $import = array('table' => 'workflow_eod',
				'records' => true);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->EODStepExample = new EODStepExample();
	}

	
/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->EODStepExample);
		parent::tearDown();
	}

	/**
	 * Test EODStepTest->executedSuccessfully()
	 */
	public function ptestExecutedSuccessfully() {
		$actual = $this->EODStepExample->executedSuccessfully();

		$this->assertFalse(empty($actual));
		$this->assertEqual($actual, 'success');
	}

	/**
	 * Test EODStepExample->execute();
	 */
	public function pTestExecute() {
		$actual = $this->EODStepExample->execute();

		$this->assertEmpty($actual);
	}

	/**
	 * Test EODStepExample->executeInternal();
	 */
	public function ptestExecuteInternal() {
		$actual = $this->EODStepExample->executeInternal();

		$this->assertEmpty($actual);
		$this->assertEqual(false, $actual);
	}
}