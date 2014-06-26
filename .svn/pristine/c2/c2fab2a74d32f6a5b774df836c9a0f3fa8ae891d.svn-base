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

App::uses('EodWorkflow', 'Model');
App::uses('LateReturn', 'Model');
App::uses('Step', 'Model');
App::uses('BackendTransaction', 'Model');

/**
 * Eod workflow step to update the add late_returns to merchant_ach_transactions
 */
class AddlateReturnsToMerchantAchTransactionsStep extends Step {
	
	/**
	 * Use database config
	 *
	 * @var string
	 */
	public $useDbConfig = 'warehouseRead';

	/**
	 * Use table
	 *
	 * @var mixed False or table name
	 */
	public $useTable = 'workflow_eod';

	/**
	 * Date Property
	 * @var Date Format 'Y-m-d'
	 */
	private $__date;

	/**
	 * Initialises 
	 * 
	 * @param Date $date Format 'Y-m-d'
	 */
	public function __construct($date) {
		$this->__date = $date;
		$this->__recovered = 'no';
		$this->_query = array();
		$this->_idempotent = false;
		$this->_stepField = 'add_late_returns_to_merchant_ach_transactions';
		$this->EodWorkflow = new EodWorkflow(); 
		$this->LateReturn = new LateReturn();
		$this->BackendTransaction = new BackendTransaction();

		parent::__construct();
		
	}

	/**
	 * Prepare query to update 'warehouse'.'workflow_eod' to set
	 * 'add_late_returns_to_merchant_ach_transactions' to success
	 * 
	 * @param date $date Format 'Y-m-d'
	 * @return array QueryString
	 */
	private function __getEODUpdateQuery($date) {
		$eodQuery = "UPDATE  warehouse.workflow_eod SET"
			. " add_late_returns_to_merchant_ach_transactions = 'success'"
			. " WHERE id = '" . $date . "'";
		return $eodQuery;
	}

	/**
	 * Prepare query to insert a row into 'warehouse'.'merchant_ach_transactions'
	 * and update 'warehouse'.'late_returns' 
	 * 
	 * @param array $lateReturnsData Data from warehosue.late_returns
	 * @param date $date Format 'Y-m-d'
	 */
	private function __addLateReturnsToMerchAchQuery($lateReturnsData,$date) {
		$query = null;
		$maxBackEndTransId = $this->BackendTransaction->getMaxId();
		foreach ($lateReturnsData as $lateReturns) {
			$query[] = "INSERT INTO warehouse.backend_transactions "
				. "(subtype) VALUES ('merchant_ach_transactions')";
			$query[] = 'set @lastInsertId = (Select last_insert_id())';
			$query[] = 'INSERT INTO warehouse.merchant_ach_transactions '
					. '(backend_transactions_id,merchant_id,account_type,'
					. 'transaction_type,amount,mergeability,status,merged_into_id,'
					. 'processing_scheduled_date,processing_actual_date)'
					.'values(@lastInsertId, 1154, "operation", "debit", "123.12",'
					. '"all", "pending", null,"2013-12-09"2013-12-09, null)';
			$query[] = 'UPDATE warehouse.late_returns SET recovered = "yes",' 
				.' backend_transaction_id = ' . $maxBackEndTransId[0]['id']
				.', recovered_date = "' . $date . '" WHERE id = "'
				. $lateReturns['LateReturn']['id'] . '"';
		}
		return $query;
	}

	/**
	 * Insert late_returns into 'warehouse'.'merchant_ach_transactions'
	 * and update 'warehouse'.'late_returns' fields
	 */
	public function executeInternal() {
		$this->useDbConfig = 'warehouseWrite';
		$lateReturnsData= $this->LateReturn->getLateReturnsToMerchAchQuery(
				$this->__date, $this->__recovered
		);
		if (!empty($lateReturnsData)) {
			$this->_query = $this->__addLateReturnsToMerchAchQuery(
				$lateReturnsData,$this->__date);
		}
		array_push($this->_query,$this->__getEodUpdateQuery($this->__date));
		$this->_atomicDbOperation();
	}

	/**
	 *  Check if 'EodWorkflow'.'add_late_returns_to_merchant_ach_transactions' is updated to 'sucess'
	 * 
	 * @return boolean true or false
	 */
	public function executedSuccessfully() {
		$result = $this->EodWorkflow->getTableFieldContent(
				$this->_stepField, $this->__date);

		return $result;
	}

}
?>
