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

App::uses('EodWorkflow', 'Model');

/**
 * EodWorkflow Test Case
 *
 */
class EodWorkflowTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.eod_workflow'
	);

	/**
	 *
	 * @var boolean 
	 */
	public $autoFixtures = false;

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();	$this->EodWorkflow = ClassRegistry::init(
						array(
								'class' => 'EodWorkflow',
								'table' => 'workflow_eod'));

		$this->EodWorkflow->useDbConfig = 'warehouseRead';
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->EodWorkflow);

		parent::tearDown();
	}

	/**
	 * Test EodWorkflow->getFieldContent()
	 */
	public function ptestGetFieldContent() {
		$actual = $this->EodWorkflow->getFieldContent('origination', '2013-11-17');
		$this->assertFalse(empty($actual));

		$expected = 'failure';
		$actual = $this->EodWorkflow->getFieldContent('originations', '2013-11-17');
		$this->assertEqual($actual['EodWorkflow']['origination'], $expected);
	}

	/**
	 * Test EodWorkflow->updateTableFieldStatusToSuccess()
	 */
	public function ptestUpdateTableFieldStatusToSuccess() {
		$date = '2013-11-21';
		$tableField = 'origination';
		
//		$data = $this->EodWorkflow->find('all');
//		debug($data);

		$this->EodWorkflow->updateTableFieldStatusToSuccess($date, $tableField);
		$expected = 'success';
		$actual = $this->EodWorkflow->getTableFieldContent('origination',
						'2013-11-21');
		$this->assertEqual($actual['EodWorkflow']['origination'], $expected);
	}

	public function ptestInsertNewEOD() {
		$expected = '2013-11-30';
		$this->EodWorkflow->insertNewEOD($expected);
		$lastInsertID = $this->EodWorkflow->getLastInsertID();
		$invalidFields = $this->EodWorkflow->invalidFields();

		$this->assertEmpty($invalidFields);
		$this->assertTrue(!empty($lastInsertID));
		$this->assertEqual($lastInsertID, $expected);
	}

	public function testInsertNewEOD_BAD() {
		$expectedFailure = array('20131230', '2013-1230', 'asdfa');

		foreach ($expectedFailure as $expected) {
			$this->EodWorkflow->insertNewEOD($expected);
			$lastInsertID = $this->EodWorkflow->getLastInsertID();
			$invalidFields = $this->EodWorkflow->invalidFields();

			$this->assertNotEmpty($invalidFields);
			$this->assertFalse(!empty($lastInsertID));
			$this->assertNotEqual($lastInsertID, $expected);
		}
	}

}