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

App::uses('CustomerAchTransaction', 'Model');

class CustomerAchTransactionTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CustomerAchTransaction =  new CustomerAchTransaction();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CustomerAchTransaction);
		parent::tearDown();
	}
	
		/**
 * Test whether the required query has been fetched or not 
 * Good Case Scenario
 */
	public function ptestCreateCSVTransactionsQuery_Good() {
		$expected = array(
						 "SELECT  ct.id ,ct.standard_entry_class_code,ct.company_entry_description ,cat.processing_scheduled_date,"
							. "ct.customer_name,ct.routing_number,ct.account_number,ct.account_type,"
							. "if(cat.transaction_type = 'debit','7','2'),"
							. "cat.amount,'','','','',m.ODFI ,'19995ZST' "
							. "INTO OUTFILE '/tmp/2013-12-09_customer_ach_csv.txt' FIELDS TERMINATED BY ','"
							. "ENCLOSED BY '\"' ESCAPED BY '\\\' LINES TERMINATED BY '\\n' "
							. "FROM warehouse.customer_transactions as ct "
							. "JOIN echecks.merchants as m ON (m.merchantId = ct.merchant_id)"
							. "JOIN warehouse.customer_ach_transactions as cat ON (ct.id = cat.customer_transactions_id)"
							. "where cat.status = 'pending' and "
							. "cat.processing_scheduled_date = '2013-12-09' "
							. "and cat.processing_actual_date IS NULL",
							"UPDATE warehouse.workflow_eod SET "
								. "csv_customer_ach_transactions = 'success' "
								. "where id = '2013-12-09'"
		);
		$actual = $this->CustomerAchTransaction->createCSVTransactionsQuery('2013-12-09');
		$this->assertEquals($actual, $expected);
	}
	/** 
	 * Test Whether the Trigger works or not
	 * Good Case
	 */
	public function testVerifycatTrigger_good () {
		$sqlQuery = 	'INSERT INTO warehouse.backend_transactions (id,subtype) VALUES ("2" , "customer_ach_transactions");'
									.'INSERT INTO warehouse.customer_ach_transactions '
									.'(customer_transactions_id,backend_transactions_id,transaction_type,amount,status,processing_scheduled_date,processing_actual_date)'
									.'Values ("1002059","2","debit","123.45","pending","2013-12-09",null)';
		$this->CustomerAchTransaction->verifycatTrigger($sqlQuery);
	}

	/** 
	 * Test Whether the Trigger works or not
	 * Bad Case
	 */
	public function testVerifycatTrigger_bad_merch () {
		$sqlQuery = 	'INSERT INTO warehouse.merchant_ach_transactions '
									.'(backend_transactions_id,merchant_id,account_type,transaction_type,amount,mergeability,status,merged_into_id,processing_scheduled_date,processing_actual_date)'
									.'Values ("2","1085","operation","debit","123.45","all","pending",null,"2013-12-09",null);'
									;
		$this->CustomerAchTransaction->verifycatTrigger($sqlQuery);
	}
	
	/** 
	 * Test Whether the Trigger works or not
	 * Bad Case
	 */
	public function testVerifycatTrigger_bad_wire () {
		$sqlQuery =		'INSERT INTO warehouse.wires '
									.'(backend_transactions_id,merchant_id,status,account_name,account_number,amount,reference_number,'
						. 'sender_bank,sender_name,beneficiary_info,additional_beneficiary_info,reference_for_beneficiary,fed_reference_number)'
									.'Values ("2","1085","received","abc","12345667","1234.5","1234","CO","abchedd","asdaas","asd","sad","sadasd")';
		$this->CustomerAchTransaction->verifycatTrigger($sqlQuery);
	}
}