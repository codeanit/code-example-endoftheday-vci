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

/**
 * Enqueue objects in FIFO order.
 * Executes object->execute() function, likewise.
 */
Class SequentialWorkflow {

	/**
	 * Is an array Objects.
	 * 
	 * @var array 
	 */
	private $__queue;

	public function __construct() {
		$this->__queue = array();
	}

	/**
	 * Check whether the Step implementations execute()
	 * executed successfully or not.
	 * 
	 * @param object $priorStep Step implementation.
	 * @return boolean ture/false
	 */
	private function __priorStepExecutedSuccessfully($priorStep) {
		$result = false;
		if ($priorStep == null) {
			$result = true;
		} elseif ($priorStep->executedSuccessfully()) {
			$result = true;
		}

		return $result;
	}

	/**
	 * Enqueue objects.
	 * 
	 * @param object $step
	 * @throws ErrorException
	 */
	public function enqueue($step) {
		if (is_object($step) && is_subclass_of($step, 'Step')) {
			$this->__queue[] = $step;
		} else {
			throw new Exception(__($step . ' is not a subclass of Step.'));
		}
	}

	/**
	 * Start executing Steps implementations
	 * in $queue list.
	 */
	public function start() {
		$priorStep = null;

		foreach ($this->__queue as $step) {
			if ($this->__priorStepExecutedSuccessfully($priorStep)) {
				$priorStep = $step;
				$step->execute();
			}
		}
	}

	/**
	 * Return $queue
	 * 
	 * @return array $queue
	 */
	public function getQueue() {
		return $this->__queue;
	}

}