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

App::uses('PopulateSettlementWarehouseDebitsStep', 'Model');
App::uses('SettlementWarehouse', 'Model');

/**
 * Test warehouse.workflow_eod's field 'populate_settlement_warehouse'
 */
class PopulateSettlementWarehouseDebitsStepTest extends CakeTestCase {

	
/**
 * setUp methodPopulateSettlementWarehouseCreditsStepTest
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->PopulateSettlementWarehouseDebitsStep =  new PopulateSettlementWarehouseDebitsStep('2013-12-09');
		$this->SettlementWarehouse =  new SettlementWarehouse();
	}

	
/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->PopulateSettlementWarehouseDebitsStep);
		unset($this->SettlementWarehouse);
		parent::tearDown();
	}

/**
 * Test whether Data has been inserted into warehouse.settlement_warehouse and 
 * warehouse.workflow_eod has been updated
 */
	public function testExecuteInternal() {
		$this->PopulateSettlementWarehouseDebitsStep->executeInternal();
		$expResultEOD = array(
				array(
				'workflow_eod' => array(
				'id' => '2013-12-03',
				'is_business_day' => 'yes',
				'populate_settlement_warehouse_debits' => 'success')));
		
		$actResultEOD = $this->PopulateSettlementWarehouseDebitsStep->query(
							"Select id, is_business_day , populate_settlement_warehouse_debits "
						. "FROM warehouse.workflow_eod where id = '2013-12-03'");
		
		$this->assertEquals($actResultEOD,$expResultEOD);
		
		$expSetWh = array(
				array(
				'SettlementWarehouse' => array(
				'id' => '25',
				'customer_transactions_id' => '1002001',
				'settlement_ideal_date' => '2013-12-09',
				'settlement_scheduled_date' => '2013-12-09',
				'settlement_actual_date' => null,
				'settlement_merchantId' => '1109',
				'settlement_odfi' => 'BC',
				'settlement_amount' => '7680.91')));
		
		$actSetWh = $this->SettlementWarehouse->find('all',
						array(
								'conditions' => array (
										'id' => '25'
								)));
		$this->assertEquals($actSetWh,$expSetWh);
	}
	/**
 * Test whether PopulateSettlementWarehouseDebitsStep has executed Succesfully
 */
	public function testExecutedSuccessfully() {
		$actual = $this->PopulateSettlementWarehouseDebitsStep->executedSuccessfully();
		$this->assertTrue($actual);
		
	}
}