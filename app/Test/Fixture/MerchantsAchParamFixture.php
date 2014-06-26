<?php
/**
 * MerchantsAchParamFixture
 *
 */
class MerchantsAchParamFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 15, 'key' => 'primary'),
		'merchantId' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'warn_pct_over_monthly_trans_vol' => array('type' => 'float', 'null' => false, 'default' => '0.25'),
		'warn_pct_over_monthly_trans_amt' => array('type' => 'float', 'null' => false, 'default' => '0.5'),
		'warn_pct_over_trans_high' => array('type' => 'float', 'null' => false, 'default' => '0.5'),
		'warn_pct_under_trans_low' => array('type' => 'float', 'null' => false, 'default' => '0.2'),
		'warn_pct_trans_avg_variance' => array('type' => 'float', 'null' => false, 'default' => '0.5'),
		'decline_pct_over_monthly_trans_vol' => array('type' => 'float', 'null' => false, 'default' => '0.5'),
		'decline_pct_over_monthly_trans_amt' => array('type' => 'float', 'null' => false, 'default' => '0.5'),
		'decline_pct_over_trans_high' => array('type' => 'float', 'null' => false, 'default' => '0.25'),
		'decline_pct_under_trans_low' => array('type' => 'float', 'null' => false, 'default' => '0.25'),
		'decline_pct_trans_avg_variance' => array('type' => 'float', 'null' => false, 'default' => '0.5'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'merchantId' => 'Lorem ipsum dolor sit amet',
			'warn_pct_over_monthly_trans_vol' => 1,
			'warn_pct_over_monthly_trans_amt' => 1,
			'warn_pct_over_trans_high' => 1,
			'warn_pct_under_trans_low' => 1,
			'warn_pct_trans_avg_variance' => 1,
			'decline_pct_over_monthly_trans_vol' => 1,
			'decline_pct_over_monthly_trans_amt' => 1,
			'decline_pct_over_trans_high' => 1,
			'decline_pct_under_trans_low' => 1,
			'decline_pct_trans_avg_variance' => 1
		),
	);

}
