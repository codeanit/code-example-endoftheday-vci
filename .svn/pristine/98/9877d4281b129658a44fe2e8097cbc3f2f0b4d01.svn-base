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
App::uses('VciDate', 'Lib');

/**
 * OriginationBatchesCustomerTransaction Model
 *
 */
class OriginationBatchesCustomerTransaction extends AppModel {

	public $useDbConfig = 'warehouseRead';
	
	public $useTable = 'origination_batches_customer_transactions';

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
	}

	/**
	 * Get Max and Min transaction id's form 'OriginationBatchesCustomerTransaction' 
	 * with process date as today in 'warehouse'.'origination_batches'
	 * 
	 * @param Date $date Format 'Y-m-d'
	 * @return array
	 */
	public function getStartEndCustomerTransactions($date) {
		$trans = $this->find('all', array(
			'fields' => array(
				'min(customer_transactions_id) as startTransId',
				'max(customer_transactions_id) as endTransId'
			),
			'joins' =>array(array(
				'table' => 'warehouse.origination_batches',
						'alias' => 'OriginationBatches',
						'type' => 'left',
						'conditions' => 'OriginationBatches.id = OriginationBatchesCustomerTransaction.origination_batches_id'),
			),
			'conditions' => array(
				'OriginationBatches.process_date' => $date,
			)
		));
			return $trans[0][0];
	}

	/**
 * Fetch Transactions Ids for given process_date and Transaction's transaction_type . 
 * 
 * @param date $date passed  Date
 * @return array $data Transaction Ids .
 */
	public function getScheduledOriginationsForDay($date, $transType) {
		$data = array();
		$transIds = array();
		$vciDate = new VciDate();
		$validDate = $vciDate->isBusinessDate(strtotime($date));
		
		$query['fields'] = array('customer_transactions_id');
		
		$query['joins'] = array(
				array(
						'table' => 'warehouse.origination_batches',
						'alias' => 'OriginationBatches',
						'type' => 'left',
						'conditions' => 'OriginationBatches.id = OriginationBatchesCustomerTransaction.origination_batches_id'),
				array(
						'table' => 'warehouse.customer_transactions',
						'alias' => 'CustomerTransactions',
						'type' => 'left',
						'conditions' => 'CustomerTransactions.id = OriginationBatchesCustomerTransaction.customer_transactions_id')
		);
		
		$query['conditions'] = array(
				'OriginationBatches.process_date' => $date,
				'CustomerTransactions.transaction_type' => $transType
			);

		try {
			if ($validDate == true) { 
				if($transType == 'debit' || $transType == 'credit' || $transType == null ) {
					$data = $this->find('list', $query);
				} else {
					throw new Exception("Transaction Type $transType Not Valid");
				}
				
			} else {
				throw new Exception("Date $date is invalid.");
			} 
			
		} catch (Exception $e) {
			echo $e->getMessage();
		}

		foreach($data as $datum) {
			$transIds[] = $datum;
		}
		
		return $transIds;
	}

/**
 * Fetch Transactions Ids from Todays Originated Batch. 
 * 
 * @param date $date passed  Date
 * @return array $data Transaction Ids .
 */
	public function getSettlementTransactionsIdsForDay($date) {
		$transIds = array();
		$query['fields'] = array('customer_transactions_id');

		$query['joins'] = array(
				array(
						'table' => 'warehouse.origination_batches',
						'alias' => 'OriginationBatches',
						'type' => 'left',
						'conditions' => 'OriginationBatches.id = OriginationBatchesCustomerTransaction.origination_batches_id'),
				array(
						'table' => 'warehouse.customer_transactions',
						'alias' => 'CustomerTransactions',
						'type' => 'left',
						'conditions' => 'CustomerTransactions.id = OriginationBatchesCustomerTransaction.customer_transactions_id')
		);

		$query['conditions'] = array(
				'OriginationBatches.process_date' => $date,
		);
		$data = $this->find('list', $query);

		foreach($data as $datum) {
			$transIds[] = $datum;
		}

		return $transIds;
	}
}