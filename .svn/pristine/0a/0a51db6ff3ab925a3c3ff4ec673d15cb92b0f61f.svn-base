<?php
App::uses('AppModel', 'Model');
/**
 * AchTransaction Model
 *
 * @property Account $Account
 */
class AchTransaction extends AppModel {

/**
 * Use database config
 *
 * @var string
 */
	public $useDbConfig = 'warehouseWrite';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'creation_date' => array(
			'date' => array(
				'rule' => array('date'),
				'message' => 'Invalid date',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'submission_scheduled_date' => array(
			'date' => array(
				'rule' => array('date'),
				'message' => 'Invalid date',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'creator' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'not empty creator',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'amount' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'amount should be numeric',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

}
