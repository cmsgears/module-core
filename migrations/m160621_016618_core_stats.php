<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

class m160621_016618_core_stats extends \yii\db\Migration {

	// Public Variables

	public $options;

	// Private Variables

	private $prefix;

	public function init() {

		// Fixed
		$this->prefix		= 'cmg_';

		// Get the values via config
		$this->options		= Yii::$app->migration->getTableOptions();

		// Default collation
		if( $this->db->driverName === 'mysql' ) {

			$this->options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
	}

	public function up() {

		// Table Stats
		$this->upStats();

		$this->insertTables();
	}

	private function upStats() {

		$this->createTable( $this->prefix . 'core_stats', [
			'id' => $this->primaryKey( 11 ),
			'table' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'rows' => $this->bigInteger( 20 )->notNull()->defaultValue( 0 )
		], $this->options );
	}

	private function insertTables() {

		$columns 	= [ 'table', 'rows' ];

		$tableData	= [
			[ $this->prefix . 'core_locale', 0 ],
			[ $this->prefix . 'core_theme', 0 ],
			[ $this->prefix . 'core_template', 0 ],
			[ $this->prefix . 'core_object', 0 ],
			[ $this->prefix . 'core_country', 0 ],
			[ $this->prefix . 'core_province', 0 ],
			[ $this->prefix . 'core_city', 0 ],
			[ $this->prefix . 'core_address', 0 ],
			[ $this->prefix . 'core_role', 0 ],
			[ $this->prefix . 'core_permission', 0 ],
			[ $this->prefix . 'core_role_permission', 0 ],
			[ $this->prefix . 'core_user', 0 ],
			[ $this->prefix . 'core_site', 0 ],
			[ $this->prefix . 'core_site_meta', 0 ],
			[ $this->prefix . 'core_site_member', 0 ],
			[ $this->prefix . 'core_file', 0 ],
			[ $this->prefix . 'core_gallery', 0 ],
			[ $this->prefix . 'core_tag', 0 ],
			[ $this->prefix . 'core_category', 0 ],
			[ $this->prefix . 'core_option', 0 ],
			[ $this->prefix . 'core_form', 0 ],
			[ $this->prefix . 'core_form_field', 0 ],
			[ $this->prefix . 'core_model_message', 0 ],
			[ $this->prefix . 'core_model_hierarchy', 0 ],
			[ $this->prefix . 'core_model_comment', 0 ],
			[ $this->prefix . 'core_model_meta', 0 ],
			[ $this->prefix . 'core_model_object', 0 ],
			[ $this->prefix . 'core_model_address', 0 ],
			[ $this->prefix . 'core_model_file', 0 ],
			[ $this->prefix . 'core_model_gallery', 0 ],
			[ $this->prefix . 'core_model_tag', 0 ],
			[ $this->prefix . 'core_model_category', 0 ],
			[ $this->prefix . 'core_model_option', 0 ],
			[ $this->prefix . 'core_model_form', 0 ]
		];

		$this->batchInsert( $this->prefix . 'core_stats', $columns, $tableData );
	}

	public function down() {

		$this->dropTable( $this->prefix . 'core_stats' );
	}
}
