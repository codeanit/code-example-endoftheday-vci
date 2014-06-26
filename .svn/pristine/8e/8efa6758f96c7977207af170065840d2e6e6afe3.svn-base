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

App::uses('Wire', 'Model');

class WireTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Wire =  new Wire();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Wire);
		parent::tearDown();
	}
	
	/** 
	 * Test Whether the Trigger works or not
	 * Good Case
	 */
	public function testVerifywireTrigger_good () {
		$sqlQuery =		'INSERT INTO warehouse.backend_transactions (id,subtype) VALUES ("3" , "wires");'
									.'INSERT INTO warehouse.wires '
									.'(backend_transactions_id,merchant_id,status,account_name,account_number,amount,reference_number,'
						. 'sender_bank,sender_name,beneficiary_info,additional_beneficiary_info,reference_for_beneficiary,fed_reference_number)'
									.'Values ("3","1085","received","abc","12345667","1234.5","1234","CO","abchedd","asdaas","asd","sad","sadasd")';
		
		$this->Wire->verifywireTrigger($sqlQuery);
	}
	
	/** 
	 * Test Whether the Trigger works or not
	 * Bad Case
	 */
	public function testVerifywireTrigger_bad_cust () {
		$sqlQuery =		'INSERT INTO warehouse.customer_ach_transactions '
									.'(customer_transactions_id,backend_transactions_id,transaction_type,amount,status,processing_scheduled_date,processing_actual_date)'
									.'Values ("1002059","3","debit","123.45","pending","2013-12-09",null)';
		$this->Wire->verifywireTrigger($sqlQuery);
	}
	
	/** 
	 * Test Whether the Trigger works or not
	 * Bad Case
	 */
	public function testVerifywireTrigger_bad_merch () {
		$sqlQuery =		'INSERT INTO warehouse.merchant_ach_transactions '
									.'(backend_transactions_id,merchant_id,account_type,transaction_type,amount,mergeability,status,merged_into_id,processing_scheduled_date,processing_actual_date)'
									.'Values ("3","1085","operation","debit","123.45","all","pending",null,"2013-12-09",null);'
									;
		$this->Wire->verifywireTrigger($sqlQuery);
	}
}