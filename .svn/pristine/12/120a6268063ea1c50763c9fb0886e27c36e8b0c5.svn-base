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
App::uses('Merchant', 'Model');

/**
 * CustomerTransactions Model
 *
 */
class CustomerTransaction extends AppModel {

	public $useDbConfig = 'warehouseWrite';
	
	public $useTable = 'customer_transactions';

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->Merchant = new Merchant();
	}

	//----------------------------EOD Step process ------------------------------------------//

	/**
 * Fetch Transactions  With Status B, for given origination_scheduled_date and . 
 * 'origination_actual_date
 * 
 * @param date $date passed  Date
 * @param integer $transId Transaction Id.
 * @return array $data Transaction  with Status B.
 */
	public function getOriginationTransactionData($transId) {
		$query['fields'] = array(
				'CustomerTransaction.id',
				'CustomerTransaction.merchant_id',
				'CustomerTransaction.amount',
				'CustomerTransaction.transaction_type',
				'OrigBatchCustTrans.id'
				
				);
		
		$query['joins'] = array(
				array(
						'table' => 'warehouse.origination_batches_customer_transactions',
						'alias' => 'OrigBatchCustTrans',
						'type' => 'left',
						'conditions' => 'OrigBatchCustTrans.customer_transactions_id = CustomerTransaction.id')
		);
		$query['conditions'] = array(
				'CustomerTransaction.id' => $transId
			);
		$data = $this->find('all', $query);

		return $data;
	}

/**
 * Generate query to update warehouse.workflow_eod 
 * @param date $date passed Creation Date
 * @param time $cutoff cutoff time
 * @return array $data Transactions with Status A.
 */
	public function acceptedTransactionsExistsAfterCutOff($date, $cutOff = '18:00:00'){
		$sql = array();
		$query['fields'] = array('id','status');

		$query['conditions'] = array(
				'status' => 'A',
				'creation_date >' => $date.' '.$cutOff
			);

		$data = $this->find('all', $query);
		if (!empty($data)) {
			$sql[] = "UPDATE warehouse.workflow_eod SET "
						. "customer_trans_populated_check = 'success' "
						. "where id = '".$date."'";
		}

		return $sql;
	}

	/**
	 * Fetch the query to insert into warehouse.origination_batches and 
	 * warehouse.origination_batches_customer_transaction and
	 * Update warehouse.customer_transactions and
	 * Update warehouse.workflow_eod's field 
	 * 
	 * @param Date $date Format 'Y-m-d'
	 * @return array $sql queries.
	 */
	public function getOriginationsQuery($date) {
		$sql = array();
		$vciDate = new VciDate();
		$effectiveDate = date('Y-m-d',($vciDate->getBusinessDate(strtotime($date),+1)));

		$sql[] = "INSERT INTO warehouse.origination_batches "
						. "(process_date, effective_date) "
						. "VALUES ('".$date."','".$effectiveDate."')";

		
		$transQuery = $this->__getCustTransQuery($date, $sql); 

		return $transQuery;
	}

	/**
	 * Fetch the query to insert into warehouse.origination_batches_customer_transaction and
	 * to update warehouse.customer_transactions's field
	 * ('origination_scheduled_date','origination_actual_date','status')and
	 * to update warehouse.workflow_eod's field ('origination_batch_creation')
	 * 
	 * @param Date $date Format 'Y-m-d'
	 * @param array $sql query
	 * @return array 
	 */
	private function __getCustTransQuery($date, $sql) {
		$vciDate = new VciDate();
		$query['fields'] = array('id','Merchant.funding_time');

		$query['joins'] = array(
				array(
						'table' => 'echecks.merchants',
						'alias' => 'Merchant',
						'type' => 'left',
						'conditions' => 'Merchant.merchantId = CustomerTransaction.merchant_id')
		);

		$query['conditions'] = array(
				'status' => 'A',
				'origination_scheduled_date ' => $date,
				'origination_actual_date' => null,
				'Merchant.new_eod' => 'true'
			);
		
		$data = $this->find('all',$query);

		if(!empty($data)) {

			foreach ($data as $datum) {

				$passdate = $vciDate->getBusinessDate(strtotime($date), 1);
				$effDate = date('Y-m-d',$passdate);

				$sql[] = "UPDATE warehouse.customer_transactions SET customer_transactions.status = 'B', "
								."customer_transactions.origination_actual_date = '".$date."', "
								."customer_transactions.effective_entry_date = '".$effDate."' where customer_transactions.id = '".$datum['CustomerTransaction']['id']."'";

				$sql[] = "INSERT INTO warehouse.origination_batches_customer_transactions "
								. "(origination_batches_id,customer_transactions_id)"
								. "SELECT max(id),'".$datum['CustomerTransaction']['id']."'FROM warehouse.origination_batches";
			}

				$sql[] = "UPDATE warehouse.workflow_eod SET "
							. "origination_batch_creation = 'success' "
							. "where id = '".$date."'";

				return $sql;

		} else {
			throw new Exception("Transactions Not Found.");
		}
		
	}

	/**
	 * Get Max and Min transaction id's form 'customer_transactions' 
	 * of daily Origiantion
	 * 
	 * @param Date $date Format 'Y-m-d'
	 * @return array
	 */
	public function getStartEndTransOrigination($date) {
		$trans = $this->find('all', array(
			'fields' => array(
				'min(id) as startTransId',
				'max(id) as endTransId'
			),
			'conditions' => array(
				'origination_scheduled_date' => $date,
			)
		));
		return $trans;
		
	}
	/**
	 * Returns all the CustomerTransactions to originate
	 * if merchant is on origination hold
	 * 
	 * @param string $date Format- date(Y-m-d)
	 * @param string $status  Format 'A'
	 * @param string $merchOrigTransHold merchant.origTransHold Format '1'
	 * @return  array $data: Customer Transactions Data 
	 */
	public function getOrigScheduleMerchantOrigHoldTrans(
			$date,
			$status,
			$merchOrigTransHold) {
		$query['fields'] = array('id',
			'merchant_id',
			'origination_scheduled_date',
			);
		$query['joins'] = array(
			array(
				'table' => 'echecks.merchants',
				'alias' => 'Merchant',
				'type' => 'LEFT',
				'conditions' => 'CustomerTransaction.merchant_id = Merchant.merchantId'
			),
		);
		$query['conditions'] = array(
			'CustomerTransaction.origination_scheduled_date' => $date,
			'status' => $status,
			'Merchant.OrigTranHold'  => $merchOrigTransHold
		);
		$data = $this->find('all', $query);
		return $data;
	}

	/**
	 * Returns all the CustomerTransactions to originate
	 * if merchant is on inactive
	 * 
	 * @param string $status Format- 'A'
	 * @param string $origSchDate Format- date(Y-m-d)
	 * @param string $merchantActive : echecks.merchants.active Format '1'
	 * @return  array $data: Customer Transactions Data 
	 */
	public function getOrigScheduleAdjustmentInactiveMerchants(
			$status,
			$date,
			$merchantActive) {
		$query['fields'] = array(
			'id',
			'origination_scheduled_date',
			'status'
			);
		$query['joins'] = array(
				array(
						'table' => 'echecks.merchants',
						'alias' => 'Merchant',
						'type' => 'LEFT',
						'conditions' => 'CustomerTransaction.merchant_id = Merchant.merchantId'
				),
		);
		$query['conditions'] = array(
			'status' => $status,
			'CustomerTransaction.origination_scheduled_date' => $date,
			'Merchant.active <>' => $merchantActive
		);
		$data = $this->find('all',$query);
		return $data;
	}

	/**
	 * Update CustomerTransaction.origination_scheduled_date to null 
	 * 
	 * @param array $transData   
	 * @return boolean True or False
	 */
	public function updateOrigScheduledDate($transData) {
		foreach ($transData as $key => $trans) {
			$updateSchDate[$key]['id'] = $trans['CustomerTransaction']['id'];
			$updateSchDate[$key]['origination_scheduled_date'] = 'Null';
		}
		if ($this->saveMany($updateSchDate)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Returns all customerTransactions to originate ICL transactions
	 * 
	 * @param string $origScheduleDate Format- date(Y-m-d)
	 * @param  $standardEntryClassCode - Format 'ICL'
	 * @param string $status - Format 'A'
	 * @return array $data: Customer Transactions Data 
	 */
	public function getOriginationScheduleAdjustmentICL(
		$origScheduleDate,
		$standardEntryClassCode,
		$status) {
		$data = $this->find('all', array(
			'fields' => array('id','origination_scheduled_date',
			'standard_entry_class_code','status'),
			'conditions' => array(
				array(
					'origination_scheduled_date' => $origScheduleDate,
					'standard_entry_class_code' => $standardEntryClassCode,
					'status' => $status
				)
		)));
		return $data;
	}

	/**
	 * Prepare query to Implement end of the day processes of transactions
	*  to originate if merchant is on Origination hold.
	 * 
	 * @param array $trans
	 * @return array
	 */
	public function getUpdateOrigSchDateQuery($trans) {
		foreach ($trans as $transVal) {
			$query[] = "UPDATE  warehouse.customer_transactions SET".
					" origination_scheduled_date  = null WHERE id = '"
					.$transVal['CustomerTransaction']['id']."'";
		}
		return $query;
	}

	/**
	 * Fetch all the Transaction's required Information for given Transaction Ids
	 * @param string $transType (transaction_type)
	 * @param array $trans Transaction Id
	 * @return array Transaction's Data
	 */
	public function getSettlementTransactionData($transIds,$transType) {
		$query['fields'] = array(
			'CustomerTransaction.id',
			'CustomerTransaction.original_transaction_id',
			'CustomerTransaction.transaction_type',
			'CustomerTransaction.amount',
			'CustomerTransaction.origination_ideal_date',
			'CustomerTransaction.merchant_id',
			'CustomerTransaction.origination_actual_date',
			'Merchant.feeposttrans',
			'Merchant.prefundcr',
			'Merchant.ODFI',
			'Merchant.feePostAmt',
			'Merchant.feePostDiscount',
			'Merchant.funding_time',
			'MerchantFee.merchantId',
			'MerchantFee.ODFI'
			);
		$query['joins'] = array(
				array(
						'table' => 'echecks.merchants',
						'alias' => 'Merchant',
						'type' => 'LEFT',
						'conditions' => array('CustomerTransaction.merchant_id = Merchant.merchantId')
				),
					array(
						'table' => 'echecks.merchants',
						'alias' => 'MerchantFee',
						'type' => 'LEFT',
						'conditions' => array('MerchantFee.interceptPin = Merchant.feeAlterTransGrp')
				),
		);
		$query['conditions'] = array(
			'CustomerTransaction.id' => $transIds,
			'CustomerTransaction.transaction_type' => $transType,
		);

		$data = $this->find('all',$query);

		return $data;
	}
}
