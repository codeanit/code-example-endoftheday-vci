<?php

App::uses('insertAtomictry', 'Model');
//App::uses('SettlementWarehouse', 'Model');

class insertAtomictryTest extends CakeTestCase {
	
		public function setUp() {
		parent::setUp();
		$this->insertAtomictry = new insertAtomictry();
	}

	public function tearDown() {
		unset($this->SettlementScheduleAdjMerchantOrigHoldStep);
		parent::tearDown();
	}

	public function ptestsaveData() {
		$result = $this->insertAtomictry->saveData();
		debug($result);
	}

	public function ptestobjACH_workGoodAchBad() {
		$result = $this->insertAtomictry->objACH_workGoodAchBad();
		echo "objACHBad Operation Completed";
	}

	public function ptestobjACH_workGood() {
		$result = $this->insertAtomictry->objACH_workGood();
		echo "objACHBad Operation Completed";
	}

	public function ptestobjACH_workBadAchGood() {
		$result = $this->insertAtomictry->objACH_workBadAchGood();
		echo "objACHBad Operation Completed";
	}

	public function ptestobjWorkFlow_workBadAchGood() {
		$result = $this->insertAtomictry->objWorkFlow_workBadAchGood();
		echo "objWorkFlow_workBadAchGood Operation Completed";
	}

	public function ptestobjWorkFlow_AchGoodworkBad() {
		$result = $this->insertAtomictry->objWorkFlow_AchGoodworkBad();
		echo "objWorkFlow_workBadAchGood Operation Completed";
	}

	public function testobjWorkFlow_SetGoodWrkBad() {
		$result = $this->insertAtomictry->objWorkFlow_SetGoodWrkBad();
		echo "objWorkFlow_workBadAchGood Operation Completed";
	}
}
?>
