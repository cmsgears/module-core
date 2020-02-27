<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

/**
 * The core stats migration insert the default row count for all the tables available in
 * core module. A scheduled console job can be executed to update these stats.
 *
 * @since 1.0.0
 */
class m160621_016618_core_stats extends \cmsgears\core\common\base\Migration {

	// Public Variables

	public $options;

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;

		// Get the values via config
		$this->options = Yii::$app->migration->getTableOptions();

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
			'tableName' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'count' => $this->bigInteger( 20 )->notNull()->defaultValue( 0 )
		], $this->options );
	}

	private function insertTables() {

		$columns = [ 'tableName', 'type', 'count' ];

		$tableData = [
			[ $this->prefix . 'core_locale', 'rows', 0 ],
			[ $this->prefix . 'core_theme', 'rows', 0 ],
			[ $this->prefix . 'core_template', 'rows', 0 ],
			[ $this->prefix . 'core_object', 'rows', 0 ],
			[ $this->prefix . 'core_country', 'rows', 0 ],
			[ $this->prefix . 'core_province', 'rows', 0 ],
			[ $this->prefix . 'core_city', 'rows', 0 ],
			[ $this->prefix . 'core_address', 'rows', 0 ],
			[ $this->prefix . 'core_role', 'rows', 0 ],
			[ $this->prefix . 'core_permission', 'rows', 0 ],
			[ $this->prefix . 'core_role_permission', 'rows', 0 ],
			[ $this->prefix . 'core_user', 'rows', 0 ],
			[ $this->prefix . 'core_site', 'rows', 0 ],
			[ $this->prefix . 'core_site_meta', 'rows', 0 ],
			[ $this->prefix . 'core_site_member', 'rows', 0 ],
			[ $this->prefix . 'core_site_access', 'rows', 0 ],
			[ $this->prefix . 'core_file', 'rows', 0 ],
			[ $this->prefix . 'core_gallery', 'rows', 0 ],
			[ $this->prefix . 'core_form', 'rows', 0 ],
			[ $this->prefix . 'core_form_field', 'rows', 0 ],
			[ $this->prefix . 'core_tag', 'rows', 0 ],
			[ $this->prefix . 'core_category', 'rows', 0 ],
			[ $this->prefix . 'core_option', 'rows', 0 ],
			[ $this->prefix . 'core_locale_message', 'rows', 0 ],
			[ $this->prefix . 'core_model_message', 'rows', 0 ],
			[ $this->prefix . 'core_model_hierarchy', 'rows', 0 ],
			[ $this->prefix . 'core_model_comment', 'rows', 0 ],
			[ $this->prefix . 'core_model_meta', 'rows', 0 ],
			[ $this->prefix . 'core_model_object', 'rows', 0 ],
			[ $this->prefix . 'core_model_address', 'rows', 0 ],
			[ $this->prefix . 'core_model_file', 'rows', 0 ],
			[ $this->prefix . 'core_model_gallery', 'rows', 0 ],
			[ $this->prefix . 'core_model_tag', 'rows', 0 ],
			[ $this->prefix . 'core_model_category', 'rows', 0 ],
			[ $this->prefix . 'core_model_option', 'rows', 0 ],
			[ $this->prefix . 'core_model_form', 'rows', 0 ],
			[ $this->prefix . 'core_model_follower', 'rows', 0 ]
		];

		$this->batchInsert( $this->prefix . 'core_stats', $columns, $tableData );
	}

	public function down() {

		$this->dropTable( $this->prefix . 'core_stats' );
	}

}
