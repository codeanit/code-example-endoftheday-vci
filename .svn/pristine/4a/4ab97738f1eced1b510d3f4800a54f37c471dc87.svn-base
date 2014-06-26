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

App::uses('AppShell', '/Console/Command');
App::uses('SequentialWorkflow', '/Lib');
App::uses('EodWorkflow', '/Model');
App::uses('BusinessDayStep', '/Model');
App::uses('CustomerTransPopulatedCheckStep', '/Model');
App::uses('OriginationScheduleMerchantOrigHoldStep', '/Model');
App::uses('OriginationScheduleAdjustmentInactiveMerchantsStep', '/Model');
App::uses('OriginationScheduleAdjustmentICLStep', '/Model');
App::uses('OriginationBatchCreationStep', '/Model');
App::uses('OriginationCustomerCreditsStep', '/Model');
App::uses('OriginationCustomerDebitsStep', '/Model');
App::uses('CurrentStatusEodInsertionStep', '/Model');
App::uses('PopulateSettlementWarehouseDebitsStep', '/Model');
App::uses('PopulateSettlementWarehouseCreditsStep', '/Model');
App::uses('PopulateSettlementWarehouseReversalsStep', '/Model');
App::uses('PopulateSettlementWarehouseDebitsWithEmbeddedFeesStep', '/Model');
App::uses('SettlementScheduleAdjMerchantOrigHoldStep', '/Model');
App::uses('SettlementScheduleAdjMerchantSettleHoldStep', '/Model');
App::uses('SettlementScheduleAdjInactiveMerchantsStep', '/Model');
App::uses('SettlementScheduleAdjICLStep', '/Model');
App::uses('SettlementCustomerCreditsStep', '/Model');
App::uses('SettlementCustomerDebitsStep', '/Model');
App::uses('AddlateReturnsToMerchantAchTransactionsStep', '/Model');
App::uses('MerchantAchTransactionMergeStep', '/Model');
App::uses('CSVCustomerAchTransactionsStep', '/Model');
App::uses('CSVMerchantAchTransactionsStep', '/Model');

/**
 * UI to EOD workflow
 * 
 */
class EODWorkflowShell extends AppShell {

	public function getOptionParser() {
		$parser = parent::getOptionParser();
		
		$parser
			->description('Populate warehouse.workflow_eod.')
			->addOption(
				'day',
				array(
					'short' => 'd',
					'help' => 'Date for which to populate: YYYY-MM-DD.',
					'required' => false));

		return $parser;
	}

	public function  main() {
			if (array_key_exists('day', $this->params)) {
				$day = $this->params['day'];
				$this->start($day);
			} else {
				$this->out($this->OptionParser->help());
				$this->out(__d('cake_console',
						'Enter: cake EODWorkflow [-d] [YYYY-MM-DD]' . "\n"));
			}
	}

	/**
	 * Enqueue the steps to the SequenceWorkflow.
	 * 
	 * @param object SequentialWorkflow
	 */
	private function __prepareSequence($obj, $date) {

		$eodSteps = array(
				'BusinessDayStep',
				'CustomerTransPopulatedCheckStep',
				'OriginationScheduleMerchantOrigHoldStep',
				'OriginationScheduleAdjustmentInactiveMerchantsStep',
				'OriginationScheduleAdjustmentICLStep',
				'OriginationBatchCreationStep',
				'OriginationCustomerCreditsStep',
				'OriginationCustomerDebitsStep',
				'CurrentStatusEodInsertionStep',
				'PopulateSettlementWarehouseDebitsStep',
				'PopulateSettlementWarehouseCreditsStep',
				'PopulateSettlementWarehouseReversalsStep',
				'PopulateSettlementWarehouseDebitsWithEmbeddedFeesStep',
				'SettlementScheduleAdjMerchantOrigHoldStep',
				'SettlementScheduleAdjMerchantSettleHoldStep',
				'SettlementScheduleAdjInactiveMerchantsStep',
				'SettlementScheduleAdjICLStep',
				'SettlementCustomerCreditsStep',
				'SettlementCustomerDebitsStep',
				'AddlateReturnsToMerchantAchTransactionsStep',
				'MerchantAchTransactionMergeStep',
				'CSVCustomerAchTransactionsStep',
				'CSVMerchantAchTransactionsStep'
				);

		foreach ($eodSteps as $stepClass) {
			$stepObj = new $stepClass($date);
			$stepObjPointer = &$stepObj;

			$obj->enqueue($stepObjPointer);
		}
	}

	/**
	 * Initiate End Of Day processes.
	 */
	public function start($date) {
		try {
			$SequentialWorkflow = new SequentialWorkflow();
			$SequentialWorkflowPointer = &$SequentialWorkflow;

			$this->__prepareSequence($SequentialWorkflowPointer, $date);

			$EodWorkflow = new EodWorkflow();
			$EodWorkflow->insertNewEOD($date);

			$SequentialWorkflow->start();

		} catch(Exception $xcp) {
			$class = new ReflectionClass("Exception");
			$property = $class->getProperty("trace");
			$property->setAccessible(true);
			$exceptionOriginTrace = $property->getValue($xcp);

			$property = $class->getProperty("message");
			$property->setAccessible(true);
			$exceptionMessage = $property->getValue($xcp);

			echo "\n File: " . $exceptionOriginTrace[0]['file'];
			echo "\n Line No.: " . $exceptionOriginTrace[0]['line'];
			echo "\n Function : " . $exceptionOriginTrace[0]['class'];
			echo "\n Class : " . $exceptionOriginTrace[0]['class'];
			echo "\n Message: " . $exceptionMessage;
		}
	}

}