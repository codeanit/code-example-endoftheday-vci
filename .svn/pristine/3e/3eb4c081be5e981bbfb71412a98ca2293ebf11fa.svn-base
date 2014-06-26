<?php
/**
 * EodWorkflowFixture
 *
 */
class EodWorkflowFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'date', 'null' => false, 'default' => null,
				'key' => 'primary'),
		'is_business_day' => array('type' => 'enum', 'null' => false,
				'default' => 'failure', 'comment' => 'Step 1 of EOD Workflow process.'),
		'origination' => array('' => 'enum', 'null' => false,
				'default' => 'failure', 'comment' => 'Step 1 of EOD Workflow process.'),

		'indexes' => array(
		 'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 
				'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	 );

	/**
	 * 
	 */
	public function init() {
		$this->records = array(
				array(
			'id' => '2013-11-17',
			'origination' => 'success'
		),
		array(
			'id' => '2013-11-18',
			'origination' => 'success'
		),
				);
		parent::init();
	}
}
