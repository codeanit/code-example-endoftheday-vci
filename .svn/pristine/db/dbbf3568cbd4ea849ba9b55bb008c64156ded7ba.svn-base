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

App::uses('SettlementWarehouse', 'Model');

class SettlementWarehouseTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SettlementWarehouse =  new SettlementWarehouse();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SettlementWarehouse);
		parent::tearDown();
	}
	
	/**
	 * Test whether required TransactionIds are Fetched or not.
	 * Good Case scenario
	 */
	public function ptestGetSettlementTransactionIds_Good () {
		$expected = array(
				 '1001995','1001996','1001997','1001998','1001999','1002001','1002002','1002008','1002009','1002012'
		 );
		$actual = $this->SettlementWarehouse->getSettlementTransactionIds('2013-12-03');
		$this->assertEquals($expected, $actual);
	}
	
	/**
	 * Test whether required TransactionIds are Fetched or not.
	 * Bad Case scenario
	 */
	public function ptestGetSettlementTransactionIds_Bad () {
		$expected = array(
				 '1001995','1001996','1001997','1001998','1001999','1002001','1002002','1002008','1002009','1002012'
		 );
		$actual = $this->SettlementWarehouse->getSettlementTransactionIds('2013-12-07');
		$this->assertNotEmpty($actual);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * Test whether required Date is Fetched or not.
	 * Good Case scenario
	 */
	public function ptestGetSettlementIdealDate_Good () {
		$expected = "'2013-12-09'";
		$expnull = 'null';
	
		$method = new ReflectionMethod(
					'SettlementWarehouse', '_getSettlementIdealDate'
				);
		$method->setAccessible(true);
		
		$actual = $method->invoke($this->SettlementWarehouse,4,'2013-12-03',4);
		$actnull = $method->invoke($this->SettlementWarehouse,'HOLD','2013-12-03',4);

		$this->assertEquals($expected, $actual);
		$this->assertEquals($expnull, $actnull);
	}
	
	/**
	 * Test whether required Date is Fetched or not.
	 * Bad Case scenario
	 */
	public function ptestGetSettlementIdealDate_Bad () {
		$expected = "'2013-12-09'";
	
		$method = new ReflectionMethod(
					'SettlementWarehouse', '_getSettlementIdealDate'
				);
		$method->setAccessible(true);
		
		
		$actual = $method->invoke($this->SettlementWarehouse,'HOLD','2013-12-03',4);
		$this->assertEquals($expected, $actual);
		
	}

	/**
	 * Test function to get SettlementWarehouse.id for given date 
	 * and customer transactions types
	 */
	public function ptestgetSettlementID_Good() {
		$expected = array('SettlementWarehouse' => array(
			'id' => '5'
		));
		$actual = $this->SettlementWarehouse->getSettlementID('2013-12-09', 'credit');
		$this->assertEquals($expected, $actual[0]);

		$expectedDebit = array('SettlementWarehouse' => array(
			'id' => '3'
		));
		$actualDebit = $this->SettlementWarehouse->getSettlementID('2013-12-09', 'debit');
		$this->assertEquals($expectedDebit, $actualDebit[0]);
	}

	/**
	 * Bad test function to get SettlementWarehouse.id for given date 
	 * and customer transactions types debit and credit
	 */
	public function ptestGetSettlementID_Bad() {
		$expected = array('SettlementWarehouse' => array(
			'id' => ''
		));
		$actual = $this->SettlementWarehouse->getSettlementID('2013-12-09', 'credit');
		$this->assertNotEquals($expected, $actual[0]);

		$expectedDebit = array('SettlementWarehouse' => array(
			'id' => ''
		));
		$actualDebit = $this->SettlementWarehouse->getSettlementID('2013-12-09', 'debit');
		$this->assertNotEquals($expectedDebit, $actualDebit[0]);
	}

	/** 
	 * Test Insert query for 'warehouse'.'backend_transactions',
	 * 'warehouse'.'settlements', 'warehouse'.'customer_ach_transactions' for 
	 * settlement_customer_credits for workflow_eod
	 */
	public function ptestManageSettlementCustomerDebitCreditQuery_Good() {
		$expected = array(
			"UPDATE warehouse.settlement_warehouse SET settlement_actual_date = 2013-12-09 WHERE id = '5'",
			"UPDATE warehouse.customer_transactions SET status = 'S' WHERE id = '1001999'",
			"INSERT INTO warehouse.backend_transactions (id,subtype) VALUES ('' , 'customer_ach_transactions')",
			'INSERT INTO warehouse.settlements (settlement_warehouse_id,backend_transactions_id,notes)'
			. 'SELECT "5","EOD Settlement Customer Credit",max(id), FROM  warehouse.backend_transactions',
			'INSERT INTO warehouse.customer_ach_transactions '
			. '(customer_transactions_id,backend_transactions_id,transaction_type,amount,status,'
			. 'processing_scheduled_date,processing_actual_date)'
			.'SELECT 1001999,max(id),"credit",763.43,"pending","2013-12-09",null '
			. 'FROM warehouse.backend_transactions'
		);
		
		$settlementArray[0] = array('SettlementWarehouse' => array(
			'id' => '5'
		));
		$actual = $this->SettlementWarehouse->manageSettlementCustomerDebitCreditQuery(
				$settlementArray,'2013-12-09','Credit');
		$this->assertEquals($expected, $actual);
	}

	/** 
	 * Bad test Insert query for 'warehouse'.'backend_transactions',
	 * 'warehouse'.'settlements', 'warehouse'.'customer_ach_transactions' for 
	 * settlement_customer_credits for workflow_eod
	 */
	public function ptestmanageSettlementCustomerDebitCreditQuery_Bad() {
		$expected = array(
			"INSERT INTO warehouse.backend_transactions (id,subtype) VALUES ('' , 'customer_ach_transactions')",
			'INSERT INTO warehouse.settlements (settlement_warehouse_id,backend_transactions_id,notes) '
			. 'SELECT "5 ","EOD Settlement Customer Credit",max(id), FROM  warehouse.backend_transactions ',
			'INSERT INTO warehouse.customer_ach_transactions '
			. '(customer_transactions_id,backend_transactions_id,transaction_type,amount,status,'
			. 'processing_scheduled_date,processing_actual_date)'
			.'SELECT 1001999 ,max(id),"credit",763.43,"pending","2013-12-09",null '
			. 'FROM warehouse.backend_transactions'
		);
		
		$settlementArray[0] = array('SettlementWarehouse' => array(
			'id' => '6'
		));
		$actual = $this->SettlementWarehouse->manageSettlementCustomerDebitCreditQuery(
				$settlementArray,'2013-12-09','Credit');
		$this->assertNotEquals($expected, $actual);
	}
	
	/** 
	 * Test Whether the Trigger works or not
	 */
	public function ptestVerifyTrigger_Good() {
		$query = 
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
								. 'FROM warehouse.settlement_warehouse;'.
				"INSERT INTO warehouse.settlement_warehouse "
				. "(customer_transactions_id,settlement_ideal_date,"
						. "settlement_scheduled_date,settlement_actual_date,"
						. "settlement_merchantId,settlement_odfi,settlement_amount)"
				. "VALUES (1001996,'2013-12-05','2013-12-05',"
								. "null,'1085','BC','134.92');";
	
		$this->SettlementWarehouse->verifyTrigger($query);
	}
	
	/** 
	 * Test Whether the Trigger works or not
	 */
	public function testVerifyTrigger_Bad() {
		$query = 
				"INSERT INTO warehouse.settlement_warehouse "
				. "(customer_transactions_id,settlement_ideal_date,"
						. "settlement_scheduled_date,settlement_actual_date,"
						. "settlement_merchantId,settlement_odfi,settlement_amount)"
				. "VALUES (1001997,'2013-12-05','2013-12-05',"
								. "null,'1085','BC','231231');";
	
		$this->SettlementWarehouse->verifyTrigger($query);
	}
}