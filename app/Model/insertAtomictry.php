<?php

App::uses('AppModel', 'Model');
App::uses('EodWorkflow', 'Model');
App::uses('SettlementWarehouse', 'Model');
APP::uses('AchTransaction', 'Model');

class insertAtomictry extends AppModel {

	public $useDbConfig = 'warehouseWrite';

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
		$this->EodWorkflow = new EodWorkflow();
		$this->AchTransaction = new AchTransaction();
		$this->SettlementWarehouse = new SettlementWarehouse();
		
		$this->workflowBad = array(
			'EodWorkflow' => array(
				'id' => '2014-03',
				'is_business_day' => ''
			)
		);

		$this->workflowGood = array(
			'EodWorkflow' => array(
				'id' => '2015-03-09',
				'is_business_day' => 'yes'
			)
		);

		$this->achDataGood = array(
			'AchTransaction' => array(
				'creation_date' => '2015-03-09',
				'submission_scheduled_date' => '2015-03-09', 
				'status' => 'pending',
				'creator' => 'deena',
				'amount' => '100')
			);

		$this->achDataBad = array(
			'AchTransaction' => array(
				'creation_date' => '2013-09q11',
				'submission_scheduled_date' => 'pendingits',
				'creator' => '',
				'amount' => 'abc')
			);

		$this->settlementDataGood = array(
			'SettlementWarehouse' => array('customer_transactions_id' => '1000003',
				'settlement_ideal_date' => 'null',
				'settlement_scheduled_date' => null,
				'settlement_actual_date' => null,
				'settlement_merchantId' => 12,
				'settlement_odfi' => 'BC',
				'settlement_amount' => '121.1'));
	}

	/**
	 * 
	 * @throws Exception
	 */
	public function saveData() {
		$data = array('SettlementWarehouse' => array('customer_transactions_id' => '1000003',
				'settlement_ideal_date' => 'null',
				'settlement_scheduled_date' => null,
				'settlement_actual_date' => null,
				'settlement_merchantId' => 12,
				'settlement_odfi' => 'BC',
				'settlement_amount' => '121.1'));

		$achDataGood = array(
			'AchTransaction' => array(
				'creation_date' => '2015-03-09',
				'submission_scheduled_date' => '2015-03-09', 
				'status' => 'pending',
				'creator' => 'deena',
				'amount' => '100')
			);

		$testD = array(
			'EodWorkflow' => array(
				'id' => '2014-01',
			)
		);

		$db = $this->SettlementWarehouse->getDataSource($this->useDbConfig);
		$db->useNestedTransactions = true;

		try {
			$db->begin($this);
			
			if(!$this->AchTransaction->save($achDataGood) ) {
			throw new Exception('SettlementWarehouse not Saved');
			}
			if(!$this->EodWorkflow->save($testD)) {
			throw new Exception('EodWorkflow not saved');
			}
			$db->commit($this);
		} catch (Exception $e) {
			$db->rollback($this);
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	/**
	 * Object AchTransaction Bad data
	 *	EodWorkflow Good data
	 * @throws Exception
	 */
	public function objACH_workGoodAchBad() {
		$db = $this->AchTransaction->getDataSource($this->useDbConfig);
		$db->useNestedTransactions = true;

		debug($this->workflowGood);
		debug($this->achDataBad);
//		die;
		try {
			$db->begin($this);
			if (!$this->EodWorkflow->save($this->workflowGood)) {
				throw new Exception('EodWorkflow not Saved');
				
			} 
			if (!$this->AchTransaction->save($this->achDataBad)) {
				throw new Exception('AchTransaction not saved');
			}

			$db->commit($this);
		} catch (Exception $e) {
			$db->rollback($this);
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

		/**
	 * Object AchTransaction Bad data
	 *	EodWorkflow Good data
	 * @throws Exception
	 */
	public function objACH_BothGood() {
		$db = $this->AchTransaction->getDataSource($this->useDbConfig);
		$db->useNestedTransactions = true;

		debug($this->workflowGood);
		debug($this->achDataGood);
		die;
		try {
			$db->begin($this);
			if (!$this->EodWorkflow->save($this->workflowGood)) {
				throw new Exception('EodWorkflow not Saved');
			} 
			if (!$this->AchTransaction->save($this->achDataGood)) {
				throw new Exception('AchTransaction not saved');
			}

			$db->commit($this);
		} catch (Exception $e) {
			$db->rollback($this);
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	/**
	 * Object AchTransaction Bad data
	 *	EodWorkflow Good data
	 * @throws Exception
	 */
		public function objACH_workBadAchGood() {
		$db = $this->AchTransaction->getDataSource($this->useDbConfig);
		$db->useNestedTransactions = true;

		debug($this->workflowBad);
		debug($this->achDataGood);
//		die;
		try {
			$db->begin($this);
			if (!$this->EodWorkflow->save($this->workflowBad)) {
				throw new Exception('EodWorkflow not Saved');
			} 
			if (!$this->AchTransaction->save($this->achDataGood)) {
				throw new Exception('AchTransaction not saved');
			}

			$db->commit($this);
		} catch (Exception $e) {
			$db->rollback($this);
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	/**
	 * Object EodWorkflow Bad data
	 *	EodWorkflow Good data
	 * @throws Exception
	 */
		public function objWorkFlow_workBadAchGood() {
		$db = $this->EodWorkflow->getDataSource($this->useDbConfig);
		$db->useNestedTransactions = true;

		debug($this->workflowBad);
		debug($this->achDataGood);
//		die;
		try {
			$db->begin($this);
			if (!$this->EodWorkflow->save($this->workflowBad)) {
				throw new Exception('EodWorkflow not Saved');
			} 
			if (!$this->AchTransaction->save($this->achDataGood)) {
				throw new Exception('AchTransaction not saved');
			}

			$db->commit($this);
		} catch (Exception $e) {
			$db->rollback($this);
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	/**
	 * Object EodWorkflow Bad data
	 *	EodWorkflow Good data
	 * @throws Exception
	 */
		public function objWorkFlow_AchGoodworkBad() {
		$db = $this->EodWorkflow->getDataSource($this->useDbConfig);
		$db->useNestedTransactions = true;

		debug($this->achDataGood);
		debug($this->workflowBad);
//		dsie;
		try {
			
			$db->begin($this);
			
			if (!$this->AchTransaction->save($this->achDataGood)) {
				throw new Exception('AchTransaction not saved');
			}
			if (!$this->EodWorkflow->save($this->workflowBad)) {
				throw new Exception('EodWorkflow not Saved');
			} 
			

			$db->commit($this);
		} catch (Exception $e) {
			$db->rollback($this);
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	/**
	 * 
	 * @throws Exception
	 */
	public function objWorkFlow_SetGoodWrkBad() {
		$db = $this->EodWorkflow->getDataSource($this->useDbConfig);
		$db->useNestedTransactions = true;
		try {
			$db->begin($this);
			debug($this->settlementDataGood);
			if (!$this->SettlementWarehouse->save($this->settlementDataGood)) {
				throw new Exception('SettlementWarehouse not Saved');
			} 
			debug($this->workflowBad);
			if (!$this->EodWorkflow->save($this->workflowBad)) {
				throw new Exception('EodWorkflow not saved');
			}

			$db->commit($this);
		} catch (Exception $e) {
			$db->rollback($this);
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	Public function pobjWorkflow_wrkBadSetGood() {
		$db = $this->EodWorkflow->getDataSource($this->useDbConfig);
		$db->useNestedTransactions = true;

		try {
			$db->begin($this);
			if (!$this->SettlementWarehouse->save($this->workflowBad)) {
				throw new Exception('SettlementWarehouse not saved');
			}
			if (!$this->EodWorkflow->save($this->settlementDataGood)) {
				throw new Exception('EodWorkflow not Saved');
			} 

			$db->commit($this);
		} catch (Exception $e) {
			$db->rollback($this);
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

}
?>
