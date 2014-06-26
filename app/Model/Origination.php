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
App::uses('OriginationBatchesCustomerTransaction', 'Model');
App::uses('CustomerTransaction', 'Model');

/**
 * Origination Model
 *
 */
class Origination extends AppModel {

	public $useDbConfig = 'warehouseRead';
	public $useTable = 'originations';

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->CustomerTransaction = new CustomerTransaction();
		$this->OriginationBatchesCustomerTransaction = new OriginationBatchesCustomerTransaction();
	}

	/**
	 * Create Query to insert in backend_transactions, originations, merchant_ach_transactions from data which have 'status' => 'B' and
	 * 'origination_scheduled_date & origination_actual_date as passed Date and 
	 * 'transaction_type' => 'credit' 
	 * 
	 * @param Date $date input Date Format 'Y-m-d'
	 * @param string $notes Information about Processing, default(EOD Process)
	 * @param boolean $databasesave if false return array else save data
	 * 
	 * @return array $cdData Query to insert in backend_transactions, originations, merchant_ach_transactions 
	 */
	public function originateCustomerCreditsForDay($date, $notes = 'EOD Process', $databaseSave = false) {
		$transIds = $this->OriginationBatchesCustomerTransaction->getScheduledOriginationsForDay($date, 'credit');

		if (!empty($transIds)) {
			return $this->originateCustomerCreditsOrDebits($transIds, $date, $notes, $databaseSave);
		} else {
			throw new Exception(" Transactions Not Found.");
		}
	}

	/**
	 * Create Query to insert in backend_transactions, originations, merchant_ach_transactions  from data which have 'status' => 'B' and
	 * 'origination_scheduled_date & origination_actual_date as passed Date and 
	 * 'transaction_type' => 'debit' 
	 * 
	 * @param Date $date input Date Format 'Y-m-d'
	 * @param string $notes Information about Processing, default(EOD Process)
	 * @param boolean $databasesave if false return array else save data
	 * 
	 * @return array Query to insert in backend_transactions, originations, merchant_ach_transactions 
	 */
	public function originateCustomerDebitsForDay($date, $notes = 'EOD Debit Process', $databaseSave = false) {
		$transIds = $this->OriginationBatchesCustomerTransaction->getScheduledOriginationsForDay($date, 'debit');

		if (!empty($transIds)) {
			return $this->originateCustomerCreditsOrDebits($transIds, $date, $notes, $databaseSave);
		} else {
			throw new Exception(" Transactions Not Found.");
		}
	}

	/**
	 * Checks if data is valid or not
	 * 
	 * @param array $transIds fetched transactions ID from warehouse.customer_transactions
	 * @param Date $date input Date Format 'Y-m-d'
	 * @param string $notes Information about Processing, default(EOD Process)
	 * @param boolean $databasesave if false return array else save data
	 * 
	 * @return array  Query to insert in backend_transactions, originations, merchant_ach_transactions 
	 */
	public function originateCustomerCreditsOrDebits($transIds, $date, $notes, $databaseSave) {
		try {
			if (!is_array($transIds)) {
				throw new Exception($transIds . " is not an Array .");
			} else if ($date == null) {
				throw new Exception("Date $date is invalid.");
			} else if ($notes == null || strlen($notes) > 255) {
				throw new Exception($notes . " is Invalid note");
			} else {
				$retdata = $this->CustomerTransaction->getOriginationTransactionData($transIds);
				if ($databaseSave == false) {
					return($this->__manageData($retdata, $date, $notes));
				}
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	/**
	 * Manage the Query According to warehouse.customer_transactions's 'transaction_type'
	 * 
	 * @param array $data fetched data from warehouse.customer_transactions
	 * @param Date $date input Date Format 'Y-m-d'
	 * @param string $notes Information about Processing, default(EOD Process)
	 * 
	 * @return array  Query to insert in backend_transactions, originations, merchant_ach_transactions 
	 */
	private function __manageData($data, $date, $notes) {
		$query = null;
		$value = 0;

		foreach ($data as $datum) {
			if ($datum['CustomerTransaction']['transaction_type'] == 'credit') {
				$value = 1;
				$query[] = "INSERT INTO warehouse.backend_transactions VALUES ('' , 'merchant_ach_transactions')";
				$query[] = 'INSERT INTO warehouse.originations '
					. '(customer_transaction_id,backend_transactions_id,'
					. 'origination_batches_customer_transaction_id,notes)'
					. 'SELECT ' . $datum['CustomerTransaction']['id'] . ', max(id),"'
					. $datum['OrigBatchCustTrans']['id'] . '","'
					. $notes . '" FROM  warehouse.backend_transactions';

				$query[] = 'INSERT INTO warehouse.merchant_ach_transactions '
					. '(backend_transactions_id,merchant_id,account_type,transaction_type,amount,mergeability,status,merged_into_id,processing_scheduled_date,processing_actual_date)'
					. 'SELECT max(id),' . $datum['CustomerTransaction']['merchant_id'] . ',"operation","debit",' . $datum['CustomerTransaction']['amount'] . ',"all",'
					. '"pending", null,"' . $date . '",null FROM warehouse.backend_transactions';
			} else {
				$query[] = "INSERT INTO warehouse.backend_transactions VALUES ('' , 'customer_ach_transactions')";

				$query[] = 'INSERT INTO warehouse.originations '
					. '(customer_transaction_id,backend_transactions_id,'
					. 'origination_batches_customer_transaction_id,notes)'
					. 'SELECT ' . $datum['CustomerTransaction']['id'] . ', max(id),"'
					. $datum['OrigBatchCustTrans']['id'] . '","' 
					. $notes . '" FROM  warehouse.backend_transactions';

				$query[] = 'INSERT INTO warehouse.customer_ach_transactions '
						. '(customer_transactions_id,backend_transactions_id,transaction_type,amount,status,processing_scheduled_date,processing_actual_date)'
						. 'SELECT ' . $datum['CustomerTransaction']['id'] . ',max(id),"debit",' . $datum['CustomerTransaction']['amount'] . ','
						. '"pending","' . $date . '",null FROM warehouse.backend_transactions';
			}
		}
//		debug($query);
//		die;
		if ($value == 1) {
			$query[] = "UPDATE warehouse.workflow_eod SET "
					. "origination_customer_credits = 'success' "
					. "where id = '" . $date . "'";
		} else {
			$query[] = "UPDATE warehouse.workflow_eod SET "
					. "origination_customer_debits = 'success' "
					. "where id = '" . $date . "'";
		}

		return $query;
	}

}