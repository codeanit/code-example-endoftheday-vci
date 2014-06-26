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
 * MerchantAchTransaction Model
 *
 */
class MerchantAchTransaction extends AppModel {

	public $useDbConfig = 'warehouseWrite';
	
	public $useTable = 'merchant_ach_transactions';

			
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
	}
	
/**
 * Create Query to insert in warehouse.backend_transactions,warehouse.merchant_ach_transactions 
 * from data having 'status' => 'pending' and 'processing_scheduled_date as passed Date 
 * and processing_actual_date = null and  'mergeability' <> 'none' 
 * and to update warehouse.merchant_ach_transactions field 'status' and 'merged_into_id'
 * 
 * @param Date $date input Date Format 'Y-m-d'
 * 
 * @return array $sqlQuery Query to insert in backend_transactions,  merchant_ach_transactions and workflow_eod
 */
	public function createMergedTransactionsQuery($date) {
		$sqlQuery = array();
		$data = $this->getMerchAchTransactions($date);

		foreach($data as $datum) {
			
			if($datum[0]['amount'] > 0) {
				$transType = 'credit'; 
				$amount = $datum[0]['amount'];
			} else if ( $datum[0]['amount'] < 0){
				$transType = 'debit';
				$amount = abs($datum[0]['amount']);
			} else {
				$transType = 'credit';
				$amount = 0;
			}

			$sqlQuery[] = "INSERT INTO warehouse.backend_transactions VALUES ('' , 'merchant_ach_transactions')";
			
			$sqlQuery[] = 'INSERT INTO warehouse.merchant_ach_transactions '
									.'(backend_transactions_id,merchant_id,account_type,transaction_type,amount,mergeability,status,merged_into_id,processing_scheduled_date,processing_actual_date)'
									.'SELECT max(id),'.$datum['MerchantAchTransaction']['merchant_id']. ',"'.$datum['MerchantAchTransaction']['account_type'].'","'.$transType.'",'.$amount.',"'.$datum['MerchantAchTransaction']['mergeability'].'",'
									.'"pending",null,"'. $date .'",null FROM warehouse.backend_transactions';
			
			$sqlQuery[] = "UPDATE warehouse.merchant_ach_transactions SET status = 'merged',merged_into_id = LAST_INSERT_ID() "
							. "where id IN (".$datum[0]['ids'].")";
		}

		$sqlQuery[] = "UPDATE warehouse.workflow_eod SET "
								. "merchant_ach_transactions_merge = 'success' "
								. "where id = '".$date."'";

		return $sqlQuery;
	}

/**
 * Fetch the Data having 'status' => 'pending' and 'processing_scheduled_date as passed Date 
 * and processing_actual_date = null and  'mergeability' <> 'none' 
 * 
 * @param Date $date input Date Format 'Y-m-d'
 * 
 * @return array $data merchant_ach_transactions's data group by 
 * merchant_ach_transactions's merchant_id,account_type and mergeability.
 */
	public function getMerchAchTransactions($date) {
		$query['fields'] = array(
				'merchant_id',
				'account_type',
				'mergeability',
				'SUM(if(transaction_type = "debit", amount, amount * -1)) as amount',
				'group_concat(id) as ids'
				);
		
		$query['conditions'] = array(
				'status' => 'pending',
				'processing_scheduled_date' => $date,
				'processing_actual_date' => null,
				'mergeability <>' => 'none'
			);
		
		$query['group'] = array(
				'merchant_id','account_type','mergeability'
		);
		$data = $this->find('all', $query);
		
		if(!empty($data)) {
			return $data;
		} else {
			
			throw new Exception('Transaction Not Fount');
		}
	}
	
	
//	public function createCSVTransactionsQuery($date) {
//		$sqlQuery = array();
//		$data = $this->getTransactionsForCSV($date);
//
//		foreach($data as $datum) {
//
//			if($datum['Merchant']['name_short'] !=  '') {
//				$merchantMame = $datum['Merchant']['name_short'];
//			} else {
//				$merchantMame = $datum['Merchant']['name'];
//			}
//
//			if($datum['MerchantAchTransaction']['account_type'] == 'operation') {
//				$routeNum = $datum['Merchant']['bankRouteNum'];
//				$bankNum = $datum['Merchant']['bankAcctNum'];
//			} else {
//				$routeNum = $datum['Merchant']['billingRoutingNumber'];
//				$bankNum = $datum['Merchant']['billingAccountNumber'];
//			}
//
//			if($datum['MerchantAchTransaction']['transaction_type'] == 'debit') {
//				$transType = '7';
//			} else if ($datum['MerchantAchTransaction']['transaction_type'] == 'credit') {
//				$transType = '2';
//			} else {
//				throw new Exception ('Invalid Transaction Type');
//			}
//
//			
//			$sqlQuery[] = "SELECT '".$datum['MerchantAchTransaction']['merchant_id']."',"
//							. "'CCD','SETTLEMENT','".$datum['MerchantAchTransaction']['processing_scheduled_date']."',"
//							. "'".$merchantMame."','".$routeNum."','".$bankNum."','".$transType."',"
//							. "'".$datum['MerchantAchTransaction']['amount']."',"
//							. "'','','','','".$datum['Merchant']['ODFI']."','19995ZST' "
//							. "INTO OUTFILE '/tmp/".$date."_merchant_ach_csv.txt' FIELDS TERMINATED BY ','"
//							. "ENCLOSED BY '\"' ESCAPED BY '\\\' LINES TERMINATED BY '\\n' ";
//			}
//
//		$sqlQuery[] = "UPDATE warehouse.workflow_eod SET "
//								. "csv_merchant_ach_transactions = 'success' "
//								. "where id = '".$date."'";
//
////		debug($sqlQuery);die;
//		return $sqlQuery;
//	}

	/**
 * Create the Query to generate a CSV file for all pending transactions in 
 * warehouse.merchant_ach_transactions for given date. 
 * 
 * @param Date $date input Date Format 'Y-m-d'
 * 
 * @return array $sqlQuery Query to create a CSV file for all pending transactions in 
 * warehouse.merchant_ach_transactions
 */
	public function createCSVTransactionsQuery($date) {
		$sqlQuery = array();

		$sqlQuery[] = "SELECT  mt.merchant_id ,'CCD','SETTLEMENT', "
						. "mt.processing_scheduled_date,"
						. "if(m.name_short <> '',m.name_short,m.name),"
						. "if(mt.account_type = 'operation',m.bankRouteNum, "
						. "m.billingRoutingNumber),"
						. "if(mt.account_type = 'operation', "
						. "m.bankAcctNum,m.billingAccountNumber),"
						. "m.acctType, if(mt.transaction_type = 'debit','7','2'),"
						. "mt.amount,'','','','',m.ODFI ,'19995ZST' "
						. "INTO OUTFILE '/var/www/endofday-vci/app/webroot/csv/"
						.$date."_merchant_ach_csv.txt' FIELDS TERMINATED BY ','"
						. "ENCLOSED BY '\"' ESCAPED BY '\\\' LINES TERMINATED BY '\\n' "
						. "FROM warehouse.merchant_ach_transactions as mt "
						. "JOIN echecks.merchants as m ON (m.merchantId = mt.merchant_id) "
						. "where mt.status = 'pending' and "
						. "mt.processing_scheduled_date = '2013-12-09' "
						. "and mt.processing_actual_date IS NULL";

		$sqlQuery[] = "UPDATE warehouse.workflow_eod SET "
								. "csv_merchant_ach_transactions = 'success' "
								. "where id = '".$date."'";

		return $sqlQuery;
	}

//	public function getTransactionsForCSV($date) {
//		
//		$query['fields'] = array(
//				'MerchantAchTransaction.merchant_id',
//				'MerchantAchTransaction.account_type',
//				'MerchantAchTransaction.amount',
//				'MerchantAchTransaction.transaction_type',
//				'MerchantAchTransaction.processing_scheduled_date',
//				'Merchant.name',
//				'Merchant.name_short',
//				'Merchant.bankRouteNum',
//				'Merchant.billingRoutingNumber',
//				'Merchant.bankAcctNum',
//				'Merchant.billingAccountNumber',
//				'Merchant.ODFI'
//				);
//		$query['joins'] = array(
//				array(
//						'table' => 'echecks.merchants',
//						'alias' => 'Merchant',
//						'type' => 'left',
//						'conditions' => 'Merchant.merchantId = MerchantAchTransaction.merchant_id')
//		);
//		$query['conditions'] = array(
//				'status' => 'pending',
//				'processing_scheduled_date' => $date,
//				'processing_actual_date' => null,
//			);
//		
//		
//		$data = $this->find('all', $query);
//		
//		return $data;
//		
//	}
	
/**
 * Verify MySql trigger is working or not in merchant_ach_transactions
 * 
 * @param $query query to insert in warehouse.merchant_ach_transactions
 */
	public function verifymatTrigger ($query) {
		$this->query ($query);
	}
	
}