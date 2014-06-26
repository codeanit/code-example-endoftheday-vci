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
 * @version $$Id$$
 */

App::uses('AppModel', 'Model');

/**
 * CustomerAchTransaction Model
 *
 */
class CustomerAchTransaction extends AppModel {

	public $useDbConfig = 'warehouseWrite';
	
	public $useTable = 'customer_ach_transactions';


			
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
	}

/**
 * Create the Query to generate a CSV file for all pending transactions in 
 * warehouse.customer_ach_transactions for given date. 
 * 
 * @param Date $date input Date Format 'Y-m-d'
 * 
 * @return array $sqlQuery Query to create a CSV file for all pending transactions in 
 * warehouse.customer_ach_transactions
 */
	public function createCSVTransactionsQuery($date) {
		$sqlQuery = array();

		$sqlQuery[] = "SELECT  ct.id ,ct.standard_entry_class_code,"
							. "ct.company_entry_description ,cat.processing_scheduled_date,"
							. "ct.customer_name,ct.routing_number,ct.account_number,"
							. "ct.account_type,if(cat.transaction_type = 'debit','7','2'),"
							. "cat.amount,'','','','',m.ODFI ,'19995ZST' "
							. "INTO OUTFILE '/var/www/endofday-vci/app/webroot/csv/"
							. $date."_customer_ach_csv.txt' FIELDS TERMINATED BY ','"
							. "ENCLOSED BY '\"' ESCAPED BY '\\\' LINES TERMINATED BY '\\n' "
							. "FROM warehouse.customer_transactions as ct "
							. "JOIN echecks.merchants as m ON (m.merchantId = ct.merchant_id)"
							. "JOIN warehouse.customer_ach_transactions as cat ON "
							. "(ct.id = cat.customer_transactions_id)"
							. "where cat.status = 'pending' and "
							. "cat.processing_scheduled_date = '2013-12-09' "
							. "and cat.processing_actual_date IS NULL";

		$sqlQuery[] = "UPDATE warehouse.workflow_eod SET "
								. "csv_customer_ach_transactions = 'success' "
								. "where id = '".$date."'";

		return $sqlQuery;
	}
	
	/**
 * Verify MySql trigger is working or not in customer_ach_transactions
 * 
 * @param $query query to insert in warehouse.customer_ach_transactions
 */
	public function verifycatTrigger ($query) {
		$this->query ($query);
	}
}