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
 * @version $$Id: Holiday.php 1382 2013-08-22 05:54:56Z anit $$
 */

App::uses('AppModel', 'Model');
/**
 * LateReturn Model
 *
 */
class LateReturn extends AppModel {

/**
 * Use database config
 *
 * @var string
 */
	public $useDbConfig = 'warehouseWrite';

	/**
	 * Get Details from LateReturn with given conditions in parameters
	 * 
	 * @param date $date Format:'Y-m-d'
	 * @param string $recovered Format: 'no' or 'yes'
	 * @return array $lateReturnsData 
	 */
	public function getLateReturnsToMerchAchQuery($date,$recovered) {
		$query['fields'] = array(
			'LateReturn.id',
			'LateReturn.backend_transaction_id',
			'customer_transactions_id',
			'CustomerTransaction.merchant_id',
			'CustomerTransaction.amount',
			'CustomerTransaction.transaction_type');
		$query['conditions'] = array(
			'LateReturn.return_date' => $date,
			'LateReturn.recovered' => $recovered
		);
		$query['joins'] = array(
				array(
					'table' => 'warehouse.customer_transactions',
					'alias' => 'CustomerTransaction',
					'type' => 'left',
					'conditions' => 'CustomerTransaction.id = LateReturn.customer_transactions_id')
			);
		$lateReturnsData = $this->find('all', $query);
		return $lateReturnsData;
	}

}
