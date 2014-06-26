<?php
App::uses('AppShell', '/Console/Command');
App::uses('AtomicModel','Atomicity.Model');

/**
 *  Atomic Shell
 */
class AtomicShell extends Shell {
	
	/**
	 * To execute plugin
	 */
    public function main() {
		$AtomicModel = new AtomicModel();
		$dataArray = array(
			'EodWorkflow' => array(
				'id' => '2015-03-18',
				'is_business_day' => 'yes'
			),
			'SettlementWarehouse' => array('customer_transactions_id' => '1000004',
				'settlement_ideal_date' => 'null',
				'settlement_scheduled_date' => null,
				'settlement_actual_date' => null,
				'settlement_merchantId' => 12,
				'settlement_odfi' => 'BC',
				'settlement_amount' => '121.1'),
			'AchTransaction' => array(
				'creation_date' => '2015-03-09',
				'submission_scheduled_date' => '2015-03-09', 
				'status' => 'pending',
				'creator' => 'deena',
				'amount' => '100'),
//			'Merchant' => array(
//				'merchantID' => '1005',
//				'isoNumber' => '2001'
//				)
			); 
		
		$sameDataSource = $AtomicModel->saveAllData($dataArray);
		
	}
}