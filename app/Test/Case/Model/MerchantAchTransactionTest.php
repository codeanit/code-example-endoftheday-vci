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

App::uses('MerchantAchTransaction', 'Model');

class MerchantAchTransactionTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MerchantAchTransaction =  new MerchantAchTransaction();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MerchantAchTransaction);
		parent::tearDown();
	}
	
	/**
 * Test whether the required query has been fetched or not 
 * Good Case Scenario
 */
	public function ptestCreateMergedTransactionQuery_Good() {
		$expected = array(
						"INSERT INTO warehouse.backend_transactions VALUES ('' , 'merchant_ach_transactions')",
						'INSERT INTO warehouse.merchant_ach_transactions '
					 .'(backend_transactions_id,merchant_id,account_type,transaction_type,amount,mergeability,status,merged_into_id,processing_scheduled_date,processing_actual_date)'
					 .'SELECT max(id),1085,"operation","credit",13909.02,"all",'
					 .'"pending",null,"2013-12-09",null FROM warehouse.backend_transactions',
						"UPDATE warehouse.merchant_ach_transactions SET status = 'merged',merged_into_id = LAST_INSERT_ID() "
							. "where id IN (1,2)",
						"UPDATE warehouse.workflow_eod SET "
								. "merchant_ach_transactions_merge = 'success' "
								. "where id = '2013-12-09'"
		);
		$actual = $this->MerchantAchTransaction->createMergedTransactionQuery('2013-12-09');
		$this->assertEquals($actual, $expected);
	}
	
/**
 * Test whether the required query has been fetched or not 
 * Bad Case Scenario
 */
	public function ptestCreateMergedTransactionQuery_Bad() {
		$expected = array(
						"INSERT INTO warehouse.backend_transactions VALUES ('' , 'merchant_ach_transactions')",
						'INSERT INTO warehouse.merchant_ach_transactions '
					 .'(backend_transactions_id,merchant_id,account_type,transaction_type,amount,mergeability,status,merged_into_id,processing_scheduled_date,processing_actual_date)'
					 .'SELECT max(id),1085,"operation","credit",13909.02,"all",'
					 .'"pending",null,"2013-12-09",null FROM warehouse.backend_transactions',
						"UPDATE warehouse.merchant_ach_transactions SET status = 'merged',merged_into_id = LAST_INSERT_ID() "
							. "where id IN (1,2)",
						"UPDATE warehouse.workflow_eod SET "
								. "merchant_ach_transactions_merge = 'success' "
								. "where id = '2013-12-09'"
		);
		$actual = $this->MerchantAchTransaction->createMergedTransactionQuery('2013-12-07');
		$this->assertEquals($actual, $expected);
	}
	
/**
 * Test whether the required Data has been fetched or not 
 * Good Case Scenario
 */
	public function ptestGetMerchAchTransactions_Good() {
		$expected = array(
				array(
					'MerchantAchTransaction' => array (
							'merchant_id' => '1085',
							'account_type' => 'operation',
							'mergeability' => 'all',
							
					),
					0 => array(
							'amount' => '13909.02',
							'ids' => '1,2'
					)
				));
		$actual = $this->MerchantAchTransaction->getMerchAchTransactions('2013-12-09');
		$this->assertEquals($actual, $expected);
	}
	
/**
 * Test whether the required Data has been fetched or not 
 * Bad Case Scenario
 */
		public function ptestGetMerchAchTransactions_Bad() {
		$expected = array(
				array(
					'MerchantAchTransaction' => array (
							'merchant_id' => '1085',
							'account_type' => 'operation',
							'mergeability' => 'all',
							
					),
					0 => array(
							'amount' => '13909.02',
							'ids' => '1,2'
					)
				));
		$actual = $this->MerchantAchTransaction->getMerchAchTransactions('2013-12-07');
		$this->assertEquals($actual, $expected);
	}
	
//		public function ptestGetTransactionsForCSV() {
//		$expected = array(
//				array(
//					'MerchantAchTransaction' => array (
//							'merchant_id' => '1085',
//							'account_type' => 'billing',
//							'amount' => '2389.10',
//							'transaction_type' => 'debit',
//							'processing_scheduled_date' => '2013-12-09'
//					),
//					'Merchant' => array(
//							'name' => 'Thrawn Inc',
//							'name_short' => '',
//							'bankRouteNum' => '071916602',
//							'billingRoutingNumber' => '091204116',
//							'bankAcctNum' => '156544096354',
//							'billingAccountNumber' => '391417287322',
//							'ODFI' => 'BC'
//					)
//				));
//		$actual = $this->MerchantAchTransaction->getTransactionsForCSV('2013-12-09');
//		$this->assertEquals($actual[0], $expected[0]);
//	}

		/**
 * Test whether the required query has been fetched or not 
 * Good Case Scenario
 */
	public function ptestCreateCSVTransactionsQuery_Good() {
		$expected = array(
						 "SELECT  mt.merchant_id ,'CCD','SETTLEMENT' ,mt.processing_scheduled_date,"
							. "if(m.name_short <> '',m.name_short,m.name),"
							. "if(mt.account_type = 'operation',m.bankRouteNum,m.billingRoutingNumber),"
							. "if(mt.account_type = 'operation',m.bankAcctNum,m.billingAccountNumber),"
							. "m.acctType, if(mt.transaction_type = 'debit','7','2'),"
							. "mt.amount,'','','','',m.ODFI ,'19995ZST' "
							. "INTO OUTFILE '/tmp/2013-12-09_merchant_ach_csv.txt' FIELDS TERMINATED BY ','"
							. "ENCLOSED BY '\"' ESCAPED BY '\\\' LINES TERMINATED BY '\\n' "
							. "FROM warehouse.merchant_ach_transactions as mt "
							. "JOIN echecks.merchants as m ON (m.merchantId = mt.merchant_id) "
							. "where mt.status = 'pending' and "
							. "mt.processing_scheduled_date = '2013-12-09' "
							. "and mt.processing_actual_date IS NULL",
							"UPDATE warehouse.workflow_eod SET "
								. "csv_merchant_ach_transactions = 'success' "
								. "where id = '2013-12-09'"
		);
		$actual = $this->MerchantAchTransaction->createCSVTransactionsQuery('2013-12-09');
		$this->assertEquals($actual, $expected);
	}
	
	/** 
	 * Test Whether the Trigger works or not
	 * Good Case
	 */
	public function ptestVerifymatTrigger_good () {
		$sqlQuery =		'INSERT INTO warehouse.backend_transactions (id,subtype) VALUES ("1" , "merchant_ach_transactions");'
									.'INSERT INTO warehouse.merchant_ach_transactions '
									.'(backend_transactions_id,merchant_id,account_type,transaction_type,amount,mergeability,status,merged_into_id,processing_scheduled_date,processing_actual_date)'
									.'Values ("1","1085","operation","debit","123.45","all","pending",null,"2013-12-09",null);'
									;
		$this->MerchantAchTransaction->verifymatTrigger($sqlQuery);
	}
	
	/** 
	 * Test Whether the Trigger works or not
	 * Bad Case
	 */
	public function ptestVerifymatTrigger_bad_cust () {
		$sqlQuery =		'INSERT INTO warehouse.customer_ach_transactions '
									.'(customer_transactions_id,backend_transactions_id,transaction_type,amount,status,processing_scheduled_date,processing_actual_date)'
									.'Values ("1002059","1","debit","123.45","pending","2013-12-09",null)';
		$this->MerchantAchTransaction->verifymatTrigger($sqlQuery);
	}
	
	/** 
	 * Test Whether the Trigger works or not
	 * Bad Case
	 */
	public function testVerifymatTrigger_bad_wire () {
		$sqlQuery =		'INSERT INTO warehouse.wires '
									.'(backend_transactions_id,merchant_id,status,account_name,account_number,amount,reference_number,'
						. 'sender_bank,sender_name,beneficiary_info,additional_beneficiary_info,reference_for_beneficiary,fed_reference_number)'
									.'Values ("1","1085","received","abc","12345667","1234.5","1234","CO","abchedd","asdaas","asd","sad","sadasd")';
		$this->MerchantAchTransaction->verifymatTrigger($sqlQuery);
	}
}