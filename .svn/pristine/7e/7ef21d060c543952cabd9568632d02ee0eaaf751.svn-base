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

App::uses('SettlementWarehouseDebitsWithEmbeddedFee', 'Model');

class SettlementWarehouseDebitsWithEmbeddedFeeTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SettlementWarehouseDebitsWithEmbeddedFee =  new SettlementWarehouseDebitsWithEmbeddedFee();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SettlementWarehouseDebitsWithEmbeddedFee);
		parent::tearDown();
	}
	
	/**
	 * Test whether query to insert warehouse.settlement_warehouse for DebitsWithEmbeddedFee Transaction
	 * and to Update warehouse.workflow_eod Tables is fetched correctly or not.
	 * Good Case Scenario
	 */
	public function ptestCreateDebitsWithEmbeddedFeesQuery_Good() {
		$expected = array(
				"INSERT INTO warehouse.settlement_warehouse "
				. "(customer_transactions_id,settlement_ideal_date,"
						. "settlement_scheduled_date,settlement_actual_date,"
						. "settlement_merchantId,settlement_odfi,settlement_amount)"
				. "VALUES (1001996,'2013-12-05','2013-12-05',"
								. "null,'1085','BC','356.36');".
				'INSERT INTO warehouse.embedded_fees '
								. '(customer_transactions_id,settlement_warehouse_id,merchant_feePostAmt,'
								. 'merchant_feePostDiscount,original_amount,fee_amount,non_fee_amount) '
								. 'SELECT "1001996",max(id),"335.12","4.3235",'
								. '"491.28","356.36","134.92"'
								. 'FROM warehouse.settlement_warehouse;',
				"UPDATE warehouse.workflow_eod SET "
								. "populate_settlement_warehouse_debits_with_embedded_fees = 'success' "
								. "where id = '2013-12-03'"
		);
	
		$data = array('1001996');
		$actual = $this->SettlementWarehouseDebitsWithEmbeddedFee->createDebitsWithEmbeddedFeesQuery($data, '2013-12-03');
		$this->assertNotEmpty($actual);
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * Test whether query to insert warehouse.settlement_warehouse for DebitsWithEmbeddedFee Transaction
	 * and to Update warehouse.workflow_eod Tables is fetched correctly or not.
	 * Bad Case Scenario
	 */
	public function ptestCreateDebitsWithEmbeddedFeesQuery_Bad() {
		$expected = array(
				"INSERT INTO warehouse.settlement_warehouse "
				. "(customer_transactions_id,settlement_ideal_date,"
						. "settlement_scheduled_date,settlement_actual_date,"
						. "settlement_merchantId,settlement_odfi,settlement_amount)"
				. "VALUES (1001996,'2013-12-05','2013-12-05',"
								. "null,'1085','BC','356.36');".
				'INSERT INTO warehouse.embedded_fees '
								. '(customer_transactions_id,settlement_warehouse_id,merchant_feePostAmt,'
								. 'merchant_feePostDiscount,original_amount,fee_amount,non_fee_amount) '
								. 'SELECT "1001996",max(id),"335.12","4.3235",'
								. '"491.28","356.36","134.92"'
								. 'FROM warehouse.settlement_warehouse;',
				"UPDATE warehouse.workflow_eod SET "
								. "populate_settlement_warehouse_debits_with_embedded_fees = 'success' "
								. "where id = '2013-12-03'"
		);
	
		$data = array();
		$actual = $this->SettlementWarehouseDebitsWithEmbeddedFee->createDebitsWithEmbeddedFeesQuery($data, '2013-12-07');
		$this->assertNotEmpty($actual);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * Test whether query to insert warehouse.settlement_warehouse for DebitsWithEmbeddedFee Transaction
	 * and to Update warehouse.workflow_eod Tables is fetched correctly or not.
	 * Good Case Scenario
	 */
	public function ptestManageDebitsWithEmbeddedFeesQuery_good() {
		$expected = 
				"INSERT INTO warehouse.settlement_warehouse "
				. "(customer_transactions_id,settlement_ideal_date,"
						. "settlement_scheduled_date,settlement_actual_date,"
						. "settlement_merchantId,settlement_odfi,settlement_amount)"
				. "VALUES (1001996,'2013-12-05','2013-12-05',"
								. "null,'1085','BC','356.36');".
				'INSERT INTO warehouse.embedded_fees '
								. '(customer_transactions_id,settlement_warehouse_id,merchant_feePostAmt,'
								. 'merchant_feePostDiscount,original_amount,fee_amount,non_fee_amount) '
								. 'SELECT "1001996",max(id),"335.12","4.3235",'
								. '"491.28","356.36","134.92"'
								. 'FROM warehouse.settlement_warehouse;';
		$method = new ReflectionMethod(
					'SettlementWarehouseDebitsWithEmbeddedFee', '__manageDebitsWithEmbeddedFeesQuery'
				);
		$method->setAccessible(true);
		
		$data = array(
						'CustomerTransaction' =>  array(
								'id' => '1001996',
								'transaction_type' => 'debit',
								'amount' => '491.28',
								'origination_ideal_date' => '2013-12-03',
								'merchant_id' => '1085',
								'original_transaction_id' => null,
								'origination_actual_date' => '2013-12-03'
						),
						'Merchant' => array(
								'feeposttrans' => '1',
								'ODFI' => 'BC',
								'feePostAmt' => '335.12',
								'feePostDiscount' => '4.3235',
								'funding_time' => '2 Day',
								'prefundcr' => '1'
						),
						'MerchantFee' => array(
								'merchantId' => '1085',
								'ODFI' => 'BC'
						)
				);
		$actual = $method->invoke($this->SettlementWarehouseDebitsWithEmbeddedFee,$data,'2013-12-03');
		$this->assertNotEmpty($actual);
		$this->assertEquals($actual,$expected);
	}

		/**
	 * Test whether query to insert warehouse.settlement_warehouse for DebitsWithEmbeddedFee Transaction
	 * and to Update warehouse.workflow_eod Tables is fetched correctly or not.
	 * Bad Case Scenario
	 */
	public function testManageDebitsWithEmbeddedFeesQuery_Bad() {
		$expected = 
				"INSERT INTO warehouse.settlement_warehouse "
				. "(customer_transactions_id,settlement_ideal_date,"
						. "settlement_scheduled_date,settlement_actual_date,"
						. "settlement_merchantId,settlement_odfi,settlement_amount)"
				. "VALUES (1001996,'2013-12-05','2013-12-05',"
								. "null,'1085','BC','356.36');".
				'INSERT INTO warehouse.embedded_fees '
								. '(customer_transactions_id,settlement_warehouse_id,merchant_feePostAmt,'
								. 'merchant_feePostDiscount,original_amount,fee_amount,non_fee_amount) '
								. 'SELECT "1001996",max(id),"335.12","4.3235",'
								. '"491.28","356.36","134.92"'
								. 'FROM warehouse.settlement_warehouse;';
		$method = new ReflectionMethod(
					'SettlementWarehouseDebitsWithEmbeddedFee', '__manageDebitsWithEmbeddedFeesQuery'
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
		$actual = $method->invoke($this->SettlementWarehouseDebitsWithEmbeddedFee,$data);
		$this->assertNotEmpty($actual);
		$this->assertEquals($actual,$expected);
	}
	

	
}