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

App::uses('SettlementWarehouseCredit', 'Model');

class SettlementWarehouseCreditTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SettlementWarehouseCredit =  new SettlementWarehouseCredit();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SettlementWarehouseCredit);
		parent::tearDown();
	}
	
	/**
	 * Test whether query to insert warehouse.settlement_warehouse for Credit Transaction
	 * and to Update warehouse.workflow_eod Tables is fetched correctly or not.
	 * Good Case Scenario
	 */
	public function ptestCreateCreditsQuery_Good() {
		$expected = array(
				"INSERT INTO warehouse.settlement_warehouse "
				. "(customer_transactions_id,settlement_ideal_date,"
						. "settlement_scheduled_date,settlement_actual_date,"
						. "settlement_merchantId,settlement_odfi,settlement_amount)"
				. "VALUES (1002012,'2013-12-09','2013-12-09',"
								. "null,'1097','BC','4554.18')",

				"UPDATE warehouse.workflow_eod SET "
								. "populate_settlement_warehouse_credits = 'success' "
								. "where id = '2013-12-03'"
		);
	
		$data = array('1002012');
		$actual = $this->SettlementWarehouseCredit->createCreditsQuery($data, '2013-12-03');
		$this->assertNotEmpty($actual);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * Test whether query to insert warehouse.settlement_warehouse for Credit Transaction
	 * and to Update warehouse.workflow_eod Tables is fetched correctly or not.
	 * Bad Case Scenario
	 */
	public function testCreateCreditsQuery_Bad() {
		$expected = array(
				"INSERT INTO warehouse.settlement_warehouse "
				. "(customer_transactions_id,settlement_ideal_date,"
						. "settlement_scheduled_date,settlement_actual_date,"
						. "settlement_merchantId,settlement_odfi,settlement_amount)"
				. "VALUES (1002012,'2013-12-09','2013-12-09',"
								. "null,'1097','BC','4554.18')",

				"UPDATE warehouse.workflow_eod SET "
								. "populate_settlement_warehouse_credits = 'success' "
								. "where id = '2013-12-03'"
		);
	
		$data = array();
		$actual = $this->SettlementWarehouseCredit->createCreditsQuery($data, '2013-12-07');
		$this->assertNotEmpty($actual);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * Test whether query to insert warehouse.settlement_warehouse for Credit Transaction
	 * and to Update warehouse.workflow_eod Tables is fetched correctly or not.
	 * Good Case Scenario
	 */
	public function testManageCreditsQuery_good() {
		$expected = 
				"INSERT INTO warehouse.settlement_warehouse "
				. "(customer_transactions_id,settlement_ideal_date,"
						. "settlement_scheduled_date,settlement_actual_date,"
						. "settlement_merchantId,settlement_odfi,settlement_amount)"
				. "VALUES (1002012,'2013-12-09','2013-12-09',"
								. "null,'1097','BC','4554.18')";
		$method = new ReflectionMethod(
					'SettlementWarehouseCredit', '__manageCreditsQuery'
				);
		$method->setAccessible(true);
		
		$data = 
					array(
						'CustomerTransaction' =>  array(
								'id' => '1002012',
								'transaction_type' => 'credit',
								'amount' => '4554.18',
								'origination_ideal_date' => '2013-12-03',
								'merchant_id' => '1097',
								'original_transaction_id' => null,
								'origination_actual_date' => '2013-12-03'
						),
						'Merchant' => array(
								'feeposttrans' => '0',
								'ODFI' => 'BC',
								'feePostAmt' => '0',
								'feePostDiscount' => '0',
								'funding_time' => '60 Day',
								'prefundcr' => '1'
						),
						'MerchantFee' => array(
								'merchantId' => null,
								'ODFI' => null
						)
				);
			
			
		$actual = $method->invoke($this->SettlementWarehouseCredit,$data,'2013-12-03');
		$this->assertNotEmpty($actual);
		$this->assertEquals($actual,$expected);
	}

		/**
	 * Test whether query to insert warehouse.settlement_warehouse for Credit Transaction
	 * and to Update warehouse.workflow_eod Tables is fetched correctly or not.
	 * Bad Case Scenario
	 */
	public function testManageCreditsQuery_Bad() {
		$expected = 
				$expected = 
				"INSERT INTO warehouse.settlement_warehouse "
				. "(customer_transactions_id,settlement_ideal_date,"
						. "settlement_scheduled_date,settlement_actual_date,"
						. "settlement_merchantId,settlement_odfi,settlement_amount)"
				. "VALUES (1002012,'2013-12-09','2013-12-09',"
								. "null,'1097','BC','4554.18')";
		$method = new ReflectionMethod(
					'SettlementWarehouseCredit', '__manageCreditsQuery'
				);
		$method->setAccessible(true);
		
		$data = array(
					'CustomerTransaction' =>  array(
								'id' => '2002008',
								'transaction_type' => 'debit',
								'amount' => '8187.30',
								'origination_ideal_date' => '2013-12-03',
								'merchant_id' => '1043',
								'original_transaction_id' => null,
								'origination_actual_date' => '2013-12-03'
						),
						'Merchant' => array(
								'feeposttrans' => '0',
								'ODFI' => 'BC',
								'feePostAmt' => '0',
								'feePostDiscount' => '0',
								'funding_time' => 'HOLD',
								'prefundcr' => '1'
						),
						'MerchantFee' => array(
								'merchantId' => 'null',
								'ODFI' => 'null'
						)
			
			);
		$actual = $method->invoke($this->SettlementWarehouseCredit,$data);
		$this->assertNotEmpty($actual);
		$this->assertEquals($actual,$expected);
	}
	
	
	
}