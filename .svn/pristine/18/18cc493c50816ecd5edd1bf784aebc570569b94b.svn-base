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

App::uses('PopulateSettlementWarehouseCreditsStep', 'Model');
App::uses('SettlementWarehouse', 'Model');

/**
 * Test warehouse.workflow_eod's field 'populate_settlement_warehouse_credits'
 */
class PopulateSettlementWarehouseCreditsStepTest extends CakeTestCase {

	
/**
 * setUp methodPopulateSettlementWarehouseCreditsStepTest
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->PopulateSettlementWarehouseCreditsStep =  new PopulateSettlementWarehouseCreditsStep('2013-12-03');
		$this->SettlementWarehouse =  new SettlementWarehouse();
	}

	
/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->PopulateSettlementWarehouseCreditsStep);
		unset($this->SettlementWarehouse);
		parent::tearDown();
	}

/**
 * Test whether Data has been inserted into warehouse.settlement_warehouse and 
 * warehouse.workflow_eod has been updated
 */
	public function testExecuteInternal() {
		$this->PopulateSettlementWarehouseCreditsStep->executeInternal();
		$expResultEOD = array(
				array(
				'workflow_eod' => array(
				'id' => '2013-12-03',
				'is_business_day' => 'yes',
				'populate_settlement_warehouse_credits' => 'success')));
		
		$actResultEOD = $this->PopulateSettlementWarehouseCreditsStep->query(
							"Select id, is_business_day , populate_settlement_warehouse_credits "
						. "FROM warehouse.workflow_eod where id = '2013-12-03'");
		
		$this->assertEquals($actResultEOD,$expResultEOD);
		
		$expSetWh = array(
				array(
				'SettlementWarehouse' => array(
				'id' => '27',
				'customer_transactions_id' => '1001998',
				'settlement_ideal_date' => null,
				'settlement_scheduled_date' => null,
				'settlement_actual_date' => null,
				'settlement_merchantId' => '1131',
				'settlement_odfi' => 'CO',
				'settlement_amount' => '3.67')));
		
		$actSetWh = $this->SettlementWarehouse->find('all',
						array(
								'conditions' => array (
										'id' => '27'
								)));
		
		$this->assertEquals($actSetWh,$expSetWh);
	}

	/**
 * Test whether PopulateSettlementWarehouseCreditsStep has executed Succesfully
 */
	public function ptestExecutedSuccessfully() {
		$actual = $this->PopulateSettlementWarehouseCreditsStep->executedSuccessfully();
		$this->assertTrue($actual);
		
	}
}