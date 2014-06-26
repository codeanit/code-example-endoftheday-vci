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

class AtomicModel extends AppModel {

	/**
	 * Checks if the all the models passed in $dataArray paramter contains same
	 * 	data from same model and then saves them
	 * @param array $dataArray Data to be saved along with their model name
	 * @throws Exception: if $dataArray have data from more than one Model
	 */
	public function saveAllData($dataArray = array(), $isQuery = Null) {
		foreach ($dataArray as $modelNa => $dataOption) {
			App::uses($modelNa, 'Model');
			$ModelObj = new $modelNa();
			$modelDbSource[] = $ModelObj->useDbConfig;
			$database[] = $ModelObj->getDataSource()->config['database'];
		}
		$sameDbConfig = $this->__checkDbconfig($database);
		if ($sameDbConfig) {
			$this->useNestedTransactions = true;
			$db = $ModelObj->getDataSource($this->useDbConfig);
			$db->useNestedTransactions = true;
			$this->useDbConfig = $database[0] . 'Write';
			try {
				$db->begin($this);
				foreach ($dataArray as $modelName => $data) {
					App::uses($modelName, 'Model');
					$this->$modelName = new $modelName();
					if ($isQuery) {
						$results = $this->$modelName->query($data[0]);
						if (!empty($results)) {
							throw new Exception($modelName . ' not Saved');
						}
					} else {
						if (!$this->$modelName->save($data)) {
							throw new Exception($modelName . ' not Saved');
						}
					}
				}
				$db->commit($this);
				return true;
			} catch (Exception $e) {
				$db->rollback($this);
				echo 'Caught exception: ', $e->getMessage(), "\n";
			}
		} else {
			throw new Exception(__('Different Datasource used'));
		}
	}

	/**
	 * Checks to see if all the models passed in the dataArray uses same Database
	 * Config
	 * @param array $database : 
	 * 	Database name used for table saving, Format: warehouse
	 * @return boolean True: if same dbconfig else False
	 */
	private function __checkDbconfig($database) {
		$return = true;
		foreach ($database as $key => $dbconfig) {
			if ($key > 0) {
				$prevDB = $database[$key - 1];
				$db = $database[$key];
				if ($prevDB != $db) {
					$return = false;
				}
			}
		}
		return $return;
	}

}

?>
