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
App::uses('CustomerTransaction', 'Model');
App::uses('OriginationBatchesCustomerTransaction', 'Model');
App::uses('OriginationBatch', 'Model');
App::uses('Merchant', 'Model');

/**
 * SettlementWarehouse Model
 *
 */
class SettlementWarehouse extends AppModel {

	public $useDbConfig = 'warehouseWrite';
	public $useTable = 'settlement_warehouse';

	public function __construct($id = false, $table = null, $ds = null) {
		$this->Merchant = new Merchant();
		$this->OriginationBatchesCustomerTransaction = new OriginationBatchesCustomerTransaction();
		$this->CustomerTransaction = new CustomerTransaction();

		parent::__construct($id, $table, $ds);
	}

	/**
	 * Calculate the settlement_scheduled_Date for warehouse.settlement_warehouse
	 * 
	 * @param string $fundTime Merchants.funding_time
	 * @param Date $actDate Format (Y-m-d)
	 * @param Integer nxtbDay time interval for next business day
	 * @return Date $setDate settlement_scheduled_Date for warehouse.settlement_warehouse
	 */
	protected function _getSettlementIdealDate($fundTime, $actDate, $nxtbDay) {
		$vciDate = new VciDate();

		if ($fundTime != 'HOLD') {
			$setDate = "'" . date('Y-m-d', $vciDate->getBusinessDate(strtotime($actDate), $nxtbDay)) . "'";
		} else {
			$setDate = 'null';
		}
		return $setDate;
	}

	/**
	 * Fetch the all trasactionsIds for given Date 
	 * 
	 * @param Date $date Format (Y-m-d)
	 * @return array $transIds Transactions Ids
	 */
	public function getSettlementTransactionIds($date) {
		$transId = array();

		$transIds = $this->OriginationBatchesCustomerTransaction->getSettlementTransactionsIdsForDay($date);

		return $transIds;
	}

	/**
	 * Get Settlement Data with 
	 * 'settlement_warehouse'.'settlement_scheduled_date' >= today and 
	 * 'settlement_warehouse'.'settlement_actual_date' = null and 
	 * 'customer_transactions'.'standard_entry_class_code' == ICL

	 * @param Date $date Format (Y-m-d)
	 * @param string $standardEntryClass Format: "ICL"
	 * @return array data
	 */
	public function getSettlementScheduleAdjICL($date, $standardEntryClass) {
		$query['fields'] = array(
			'id',
			'settlement_scheduled_date',
			'settlement_actual_date'
		);
		$query['joins'] = array(
			array(
				'table' => 'warehouse.customer_transactions',
				'alias' => 'CustomerTransaction',
				'type' => 'left',
				'conditions' => 'CustomerTransaction.id = SettlementWarehouse.customer_transactions_id')
		);
		$query['conditions'] = array(
			'settlement_scheduled_date >' => $date,
			'settlement_actual_date' => null,
			'CustomerTransaction.standard_entry_class_code' => $standardEntryClass
		);

		$settleData = $this->find('all', $query);
		return $settleData;
	}

	/**
	 * Get all settlement_warehouse data with 'settlement_scheduled_date >' => $date,
	  'settlement_actual_date' => null, and  different Merchant conditions
	 * 
	 * @param date $date Format "Y-m-d"
	 * @param string $origTransHold 1
	 */
	public function getSettlementScheduleMerchantTrans($date, $details, $merchField) {
		if ($merchField == 'fundingTime') {
			$merchCond[] = array('Merchant.funding_time' => $details);
		} elseif ($merchField == 'OrigTranHold') {
			$merchCond[] = array('Merchant.OrigTranHold' => $details);
		} elseif ($merchField == 'MerchActive') {
			$merchCond[] = array('Merchant.active' => $details);
		}
		$query['fields'] = array(
			'id',
			'settlement_scheduled_date',
			'settlement_actual_date'
		);
		$query['joins'] = array(
			array(
				'table' => 'echecks.merchants',
				'alias' => 'Merchant',
				'type' => 'left',
				'conditions' => 'Merchant.merchantId = settlement_merchantId')
		);
		$query['conditions'] = array(
			'settlement_scheduled_date >' => $date,
			'settlement_actual_date' => null,
			'and' => $merchCond[0]
		);

		$settleData = $this->find('all', $query);
		return $settleData;
	}

	/**
	 * Get query to update 'settlement_warehouse'.'settlement_scheduled_date' to null
	 * 
	 * @param array transactions to be updated
	 * @return array Querystring
	 */
	public function updateSettlementSchDateQuery($trans) {
		foreach ($trans as $transVal) {
			$query[] = "UPDATE  warehouse.settlement_warehouse SET" .
					" settlement_scheduled_date = null WHERE id = '"
					. $transVal['SettlementWarehouse']['id'] . "'";
		}
		return $query;
	}

	/**
	 * Get details of 'warehouse'.'settlement_warehouse'
	 * 
	 * @param array $fields : Table fields of 'warehouse'.'settlement_warehouse'
	 * @param int $id 'settlement_warehouse'.'id'
	 * @return array : Data returned from 'warehouse'.'settlement_warehouse'
	 */
	public function getDetailsFromID($fields, $id) {
		$query['fields'] = $fields;
		$query['conditions'] = array(
			'SettlementWarehouse.id' => $id
		);

		$data = $this->find('all', $query);
		return $data;
	}

	/**
	 * 
	 * @param type $conditions
	 * @param type $fields
	 * @param type $joins
	 */
	public function getDetails($conditions, $fields, $joins) {
		$query['fields'] = $fields;
		$query['joins'] = $joins;
		$query['conditions'] = $conditions;

		$settleData = $this->find('all', $query);
		return $settleData;
	}

	/**
	 * Get SettlementWarehouse.id for given date and customer transactions types
	 * 
	 * @param Date $date Format "YYYY-MM-DD'
	 * @param string $transType Format 'debit' or 'credit
	 * 
	 * @return array of SettlementWarehouse.id
	 */
	public function getSettlementID($date, $transType) {
		$query['fields'] = array(
			'id', 
		);
		$query['joins'] = array(
			array(
				'table' => 'warehouse.customer_transactions',
				'alias' => 'CustomerTransaction',
				'type' => 'left',
				'conditions' => 'CustomerTransaction.id = SettlementWarehouse.customer_transactions_id')
		);
		$query['conditions'] = array(
			'settlement_scheduled_date' => $date,
			'settlement_actual_date' => null,
			'CustomerTransaction.transaction_type' => $transType
		);
		$settleData = $this->find('all', $query);
		return $settleData;
	}

	/**
	 * Manage Insert query for 'warehouse'.'backend_transactions',
	 * 'warehouse'.'settlements', 'warehouse'.'customer_ach_transactions' for 
	 * settlement_customer_credits for workflow_eod
	 * 
	 * @param array $settlementWarehouseData array including settlement_warehouse Id
	 * @param date $date Format: YYYY-MM-DD
	 * @param string $settleType Format 'Debit' or "Credit"
	 * @return boolean 
	 */
	public function manageSettlementCustomerDebitCreditQuery(
		$settlementWarehouseData, $date, $settleType) {
		$param['date'] = $date;
		$param['type'] = $settleType;
		$param['joins'] = array(
			array(
				'table' => 'warehouse.customer_transactions',
				'alias' => 'CustomerTransaction',
				'type' => 'left',
				'conditions' => 'CustomerTransaction.id = SettlementWarehouse.customer_transactions_id')
		);

		if ($settleType == 'Credit') {
			$param['fields'] = array('customer_transactions_id', 'settlement_amount');
			$param['notes'] = 'EOD Settlement Customer Credit';
			$query = $this->__getSettlementCreditQuery($settlementWarehouseData, $param);
		} elseif ($settleType == 'Debit') {
			$param['notes'] = 'EOD Settlement Customer Debit';
			$param['fields'] = array(
				'CustomerTransaction.merchant_id',
				'settlement_amount', 'customer_transactions_id');
			$query = $this->__getSettlementDebitQuery($settlementWarehouseData, $param);
		}

		return $query;
	}

	/**
	 * Get Insert query for 'warehouse'.'backend_transactions',
	 * 'warehouse'.'settlements', 'warehouse'.'merchant_ach_transactions' for 
	 * settlement_customer_debits for workflow_eod
	 * 
	 * @param array $settlementWarehouseIdDetail :
	 *		array with id from 'warehouse'.'settlement_warehouse'
	 * @param array $param: array containing $join, $conditions and $fields to get details
	 *	from SettlementWarehouse Id
	 * @return array $query Query String
	 */
	private function __getSettlementDebitQuery($settlementWarehouseIdDetail, $param) {
		$query = null;
		foreach ($settlementWarehouseIdDetail as $key => $data) {
			$settlementWarehouseID = $data['SettlementWarehouse']['id'];
			$conditions = array(
				'SettlementWarehouse.id' => $settlementWarehouseID,
				'settlement_scheduled_date' => $param['date'],
				'settlement_actual_date' => null,
				'CustomerTransaction.transaction_type' => $param['type']
			);

			$settlementWarehouseDetails = $this->getDetails(
				$conditions, $param['fields'], $param['joins']);

			$query[] = 'UPDATE warehouse.settlement_warehouse SET' .
				' settlement_actual_date = "' . $param['date'] . '" WHERE id = "'
				. $settlementWarehouseID . '"';
			$custTransID[$key] = $settlementWarehouseDetails[0]['SettlementWarehouse']['customer_transactions_id'];
			
			$query[] = "INSERT INTO warehouse.backend_transactions "
				. "(subtype) VALUES ('merchant_ach_transactions')";
			$query[] = 'INSERT INTO warehouse.settlements '
				. '(settlement_warehouse_id,backend_transaction_id,notes)'
				. 'SELECT "' . $settlementWarehouseID . '",max(id),"'
				. $param['notes']
				. '"' . ' FROM  warehouse.backend_transactions';
			$query[] = 'INSERT INTO warehouse.merchant_ach_transactions '
				. '(backend_transactions_id,merchant_id,account_type,'
				. 'transaction_type,amount,mergeability,status,'
				. 'processing_scheduled_date,processing_actual_date)'
				. 'SELECT max(id),'
				. $settlementWarehouseDetails[0]['CustomerTransaction']['merchant_id']
				. ',' . '"operation","credit","'
				. $settlementWarehouseDetails[0]['SettlementWarehouse']['settlement_amount']
				. '","all", "pending","' . $param['date']
				. '" , null FROM warehouse.backend_transactions';
		}
		$uniqueCustTrans = array_unique($custTransID);
		foreach($uniqueCustTrans as $transID) {
			$query[] = "UPDATE  warehouse.customer_transactions SET" .
					" status = 'S' WHERE id = '"
					. $transID . "'";
			}

		return $query;
	}

	/**
	 * Get Insert query for 'warehouse'.'backend_transactions',
	 * 'warehouse'.'settlements', 'warehouse'.'customer_ach_transactions' for 
	 * settlement_customer_credits for workflow_eod
	 * 
	 * @param array $settlementWarehouseIdDetail :
	 *		array with id from 'warehouse'.'settlement_warehouse'
	 * @param array $param: array containing $join, $conditions and $fields to get details
	 *	from SettlementWarehouse Id
	 * @return array $query Query String
	 */
	private function __getSettlementCreditQuery($settlementWarehouseIdDetail, $param) {
		$query = null;
		foreach ($settlementWarehouseIdDetail as $data) {
			$settlementWarehouseID = $data['SettlementWarehouse']['id'];
			$conditions = array(
				'SettlementWarehouse.id' => $settlementWarehouseID,
				'settlement_scheduled_date' => $param['date'],
				'settlement_actual_date' => null,
				'CustomerTransaction.transaction_type' => $param['type']
			);

			$settlementWHCreditDetails = $this->getDetails(
				$conditions, $param['fields'], $param['joins']);
			$query[] = 'UPDATE warehouse.settlement_warehouse SET' .
				' settlement_actual_date = "' . $param['date'] . '" WHERE id = "'
				. $settlementWarehouseID . '"';
			$query[] = "UPDATE warehouse.customer_transactions SET" .
				" status = 'S' WHERE id = '"
				. $settlementWHCreditDetails[0]['SettlementWarehouse']['customer_transactions_id'] . "'";
			$query[] = "INSERT INTO warehouse.backend_transactions "
				. "(subtype) VALUES ('customer_ach_transactions')";
			$query[] = 'INSERT INTO warehouse.settlements '
				. '(settlement_warehouse_id,backend_transaction_id,notes)'
				. 'SELECT "' . $settlementWarehouseID . '",max(id),"' 
				. $param['notes']
				. '"' . ' FROM  warehouse.backend_transactions';
			$query[] = 'INSERT INTO warehouse.customer_ach_transactions '
				. '(customer_transactions_id,backend_transactions_id,transaction_type,'
				. 'amount,status,processing_scheduled_date,processing_actual_date)'
				. 'SELECT '
				. $settlementWHCreditDetails[0]['SettlementWarehouse']['customer_transactions_id']
				. ',max(id),"credit",'
				. $settlementWHCreditDetails[0]['SettlementWarehouse']['settlement_amount'] . ','
				. '"pending","' . $param['date'] 
				. '",null FROM warehouse.backend_transactions';
		}
		return $query;
	}
	
/**
 * Verify MySql trigger is working or not in settlement_warehouse
 * 
 * @param $query query to insert in warehouse.settlement_warehouse
 */
	public function verifyTrigger($query) {
		$this->useDbConfig = 'warehouseWrite';
		$this->query ($query);
		
	}

}