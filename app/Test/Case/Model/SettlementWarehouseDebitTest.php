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

App::uses('SettlementWarehouseDebit', 'Model');

class SettlementWarehouseDebitTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SettlementWarehouseDebit =  new SettlementWarehouseDebit();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SettlementWarehouseDebit);
		parent::tearDown();
	}
	
	/**
	 * Test whether query to insert warehouse.settlement_warehouse for Debit Transaction
	 * and to Update warehouse.workflow_eod Tables is fetched correctly or not.
	 * Good Case Scenario
	 */
	public function ptestCreateDebitsQuery_Good() {
		$expected = array(
				"INSERT INTO warehouse.settlement_warehouse "
				. "(customer_transactions_id,settlement_ideal_date,"
						. "settlement_scheduled_date,settlement_actual_date,"
						. "settlement_merchantId,settlement_odfi,settlement_amount)"
				. "VALUES (1002008,null,null,"
								. "null,'1043','BC','8187.30')",

				"UPDATE warehouse.workflow_eod SET "
								. "populate_settlement_warehouse_debits = 'success' "
								. "where id = '2013-12-03'"
		);
	
		$data = array('1002008');
		$actual = $this->SettlementWarehouseDebit->createDebitsQuery($data, '2013-12-03');
		$this->assertNotEmpty($actual);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * Test whether query to insert warehouse.settlement_warehouse for Debit Transaction
	 * and to Update warehouse.workflow_eod Tables is fetched correctly or not.
	 * Bad Case Scenario
	 */
	public function ptestCreateDebitsQuery_Bad() {
		$expected = array(
				"INSERT INTO warehouse.settlement_warehouse "
				. "(customer_transactions_id,settlement_ideal_date,"
						. "settlement_scheduled_date,settlement_actual_date,"
						. "settlement_merchantId,settlement_odfi,settlement_amount)"
				. "VALUES (1002008,null,null,"
								. "null,'1043','BC','8187.30')",

				"UPDATE warehouse.workflow_eod SET "
								. "populate_settlement_warehouse_debits = 'success' "
								. "where id = '2013-12-03'"
		);
	
		$data = array();
		$actual = $this->SettlementWarehouseDebit->createDebitsQuery($data, '2013-12-07');
		$this->assertNotEmpty($actual);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * Test whether query to insert warehouse.settlement_warehouse for Debit Transaction
	 * and to Update warehouse.workflow_eod Tables is fetched correctly or not.
	 * Good Case Scenario
	 */
	public function ptestManageDebitsQuery_good() {
		$expected = 
				"INSERT INTO warehouse.settlement_warehouse "
				. "(customer_transactions_id,settlement_ideal_date,"
						. "settlement_scheduled_date,settlement_actual_date,"
						. "settlement_merchantId,settlement_odfi,settlement_amount)"
				. "VALUES (1002008,null,null,"
								. "null,'1043','BC','8187.30')";
		$method = new ReflectionMethod(
					'SettlementWarehouseDebit', '__manageDebitsQuery'
				);
		$method->setAccessible(true);
		
		$data = array(
					'CustomerTransaction' =>  array(
								'id' => '1002008',
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
		$actual = $method->invoke($this->SettlementWarehouseDebit,$data,'2013-12-03');
		$this->assertNotEmpty($actual);
		$this->assertEquals($actual,$expected);
	}

		/**
	 * Test whether query to insert warehouse.settlement_warehouse for Debit Transaction
	 * and to Update warehouse.workflow_eod Tables is fetched correctly or not.
	 * Bad Case Scenario
	 */
	public function ptestManageDebitsQuery_Bad() {
		$expected = 
				"INSERT INTO warehouse.settlement_warehouse "
				. "(customer_transactions_id,settlement_ideal_date,"
						. "settlement_scheduled_date,settlement_actual_date,"
						. "settlement_merchantId,settlement_odfi,settlement_amount)"
				. "VALUES (1002008,null,null,"
								. "null,'1043','BC','8187.30')";
		$method = new ReflectionMethod(
					'SettlementWarehouseDebit', '__manageDebitsQuery'
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
		$actual = $method->invoke($this->SettlementWarehouseDebit,$data);
		$this->assertNotEmpty($actual);
		$this->assertEquals($actual,$expected);
	}
	

	
}