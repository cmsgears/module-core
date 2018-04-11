<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

// CMG Imports
use cmsgears\core\common\base\Migration;

/**
 * The core index migration inserts the recommended indexes for better performance. It
 * also list down other possible index commented out. These indexes can be created using
 * project based migration script.
 *
 * @since 1.0.0
 */
class m160621_014612_core_index extends Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;
	}

	public function up() {

		$this->upPrimary();

		$this->upDependent();
	}

	private function upPrimary() {

		// Locale
		$this->createIndex( 'idx_' . $this->prefix . 'locale_name', $this->prefix . 'core_locale', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'locale_code', $this->prefix . 'core_locale', 'code' );

		// Theme
		$this->createIndex( 'idx_' . $this->prefix . 'theme_name', $this->prefix . 'core_theme', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'theme_slug', $this->prefix . 'core_theme', 'slug' );
		//$this->createIndex( 'idx_' . $this->prefix . 'theme_slug', $this->prefix . 'core_theme', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'theme_rendrer', $this->prefix . 'core_theme', 'renderer' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_theme' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'theme_desc' . '(description ASC)' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_theme' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'theme_search' . '(name ASC, title ASC, description ASC, content ASC)' );

		// Template
		$this->createIndex( 'idx_' . $this->prefix . 'template_name', $this->prefix . 'core_template', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'template_slug', $this->prefix . 'core_template', 'slug' );
		$this->createIndex( 'idx_' . $this->prefix . 'template_type', $this->prefix . 'core_template', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'template_icon', $this->prefix . 'core_template', 'icon' );
		//$this->createIndex( 'idx_' . $this->prefix . 'template_rendrer', $this->prefix . 'core_template', 'renderer' );
		//$this->createIndex( 'idx_' . $this->prefix . 'template_layout', $this->prefix . 'core_template', 'layout' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_template' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'template_desc' . '(description ASC)' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_template' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'template_search' . '(name ASC, title ASC, description ASC, content ASC)' );

		// Object
		$this->createIndex( 'idx_' . $this->prefix . 'object_name', $this->prefix . 'core_object', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'object_slug', $this->prefix . 'core_object', 'slug' );
		$this->createIndex( 'idx_' . $this->prefix . 'object_type', $this->prefix . 'core_object', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'object_icon', $this->prefix . 'core_object', 'icon' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_object' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'object_desc' . '(description ASC)' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_object' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'object_search' . '(name ASC, title ASC, description ASC, content ASC)' );

		// Object Meta
		$this->createIndex( 'idx_' . $this->prefix . 'object_meta_name', $this->prefix . 'core_object_meta', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'object_meta_type', $this->prefix . 'core_object_meta', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'object_meta_label', $this->prefix . 'core_object_meta', 'label' );
		//$this->createIndex( 'idx_' . $this->prefix . 'object_meta_vtype', $this->prefix . 'core_object_meta', 'valueType' );
		//$this->createIndex( 'idx_' . $this->prefix . 'object_meta_mit', $this->prefix . 'core_object_meta', [ 'modelId', 'type' ] );
		//$this->createIndex( 'idx_' . $this->prefix . 'object_meta_mitn', $this->prefix . 'core_object_meta', [ 'modelId', 'type', 'name' ] );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_object_meta' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'object_meta_search' . '(name ASC, value ASC)' );

		// Country
		$this->createIndex( 'idx_' . $this->prefix . 'country_name', $this->prefix . 'core_country', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'country_code', $this->prefix . 'core_country', 'code' );
		$this->createIndex( 'idx_' . $this->prefix . 'country_iso', $this->prefix . 'core_country', 'iso' );

		// Province
		$this->createIndex( 'idx_' . $this->prefix . 'province_name', $this->prefix . 'core_province', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'province_code', $this->prefix . 'core_province', 'code' );
		$this->createIndex( 'idx_' . $this->prefix . 'province_iso', $this->prefix . 'core_province', 'iso' );

		// City
		$this->createIndex( 'idx_' . $this->prefix . 'city_name', $this->prefix . 'core_city', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'city_iso', $this->prefix . 'core_city', 'iso' );
		//$this->createIndex( 'idx_' . $this->prefix . 'city_postal', $this->prefix . 'core_city', 'postal' );
		//$this->createIndex( 'idx_' . $this->prefix . 'city_zone', $this->prefix . 'core_city', 'zone' );
		//$this->createIndex( 'idx_' . $this->prefix . 'city_tzone', $this->prefix . 'core_city', 'timeZone' );
		//$this->createIndex( 'idx_' . $this->prefix . 'city_lat', $this->prefix . 'core_city', 'latitude' );
		//$this->createIndex( 'idx_' . $this->prefix . 'city_lon', $this->prefix . 'core_city', 'longitude' );
		//$this->createIndex( 'idx_' . $this->prefix . 'city_lat_lon', $this->prefix . 'core_city', [ 'latitude', 'longitude' ] );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_city' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'city_reg' . '(regions ASC)' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_city' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'city_zip' . '(zipCodes ASC)' );

		// Address
		//$this->createIndex( 'idx_' . $this->prefix . 'address_title', $this->prefix . 'core_address', 'title' );
		//$this->createIndex( 'idx_' . $this->prefix . 'address_line1', $this->prefix . 'core_address', 'line1' );
		//$this->createIndex( 'idx_' . $this->prefix . 'address_line2', $this->prefix . 'core_address', 'line2' );
		//$this->createIndex( 'idx_' . $this->prefix . 'address_line3', $this->prefix . 'core_address', 'line3' );
		//$this->createIndex( 'idx_' . $this->prefix . 'address_country_n', $this->prefix . 'core_address', 'countryName' );
		//$this->createIndex( 'idx_' . $this->prefix . 'address_province_n', $this->prefix . 'core_address', 'provinceName' );
		//$this->createIndex( 'idx_' . $this->prefix . 'address_city_n', $this->prefix . 'core_address', 'cityName' );
		//$this->createIndex( 'idx_' . $this->prefix . 'address_zip', $this->prefix . 'core_address', 'zip' );
		//$this->createIndex( 'idx_' . $this->prefix . 'address_zip_sub', $this->prefix . 'core_address', 'subZip' );
		//$this->createIndex( 'idx_' . $this->prefix . 'address_name_f', $this->prefix . 'core_address', 'firstName' );
		//$this->createIndex( 'idx_' . $this->prefix . 'address_name_l', $this->prefix . 'core_address', 'lastName' );
		//$this->createIndex( 'idx_' . $this->prefix . 'address_phone', $this->prefix . 'core_address', 'phone' );
		//$this->createIndex( 'idx_' . $this->prefix . 'address_email', $this->prefix . 'core_address', 'email' );
		//$this->createIndex( 'idx_' . $this->prefix . 'address_fax', $this->prefix . 'core_address', 'fax' );
		//$this->createIndex( 'idx_' . $this->prefix . 'address_lat', $this->prefix . 'core_address', 'latitude' );
		//$this->createIndex( 'idx_' . $this->prefix . 'address_lon', $this->prefix . 'core_address', 'longitude' );
		//$this->createIndex( 'idx_' . $this->prefix . 'address_lat_lon', $this->prefix . 'core_address', [ 'latitude', 'longitude' ] );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_address' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'address_search' . '(title ASC, line1 ASC, line2 ASC, line3 ASC)' );

		// Role
		$this->createIndex( 'idx_' . $this->prefix . 'role_name', $this->prefix . 'core_role', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'role_slug', $this->prefix . 'core_role', 'slug' );
		$this->createIndex( 'idx_' . $this->prefix . 'role_type', $this->prefix . 'core_role', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'role_icon', $this->prefix . 'core_role', 'icon' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_role' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'role_desc' . '(description ASC)' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_role' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'role_search' . '(name ASC, description ASC)' );

		// Permission
		$this->createIndex( 'idx_' . $this->prefix . 'permission_name', $this->prefix . 'core_permission', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'permission_slug', $this->prefix . 'core_permission', 'slug' );
		$this->createIndex( 'idx_' . $this->prefix . 'permission_type', $this->prefix . 'core_permission', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'permission_icon', $this->prefix . 'core_permission', 'icon' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_permission' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'permission_desc' . '(description ASC)' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_permission' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'permission_search' . '(name ASC, description ASC)' );

		// User
		$this->createIndex( 'idx_' . $this->prefix . 'user_email', $this->prefix . 'core_user', 'email' );
		$this->createIndex( 'idx_' . $this->prefix . 'user_username', $this->prefix . 'core_user', 'username' );
		//$this->createIndex( 'idx_' . $this->prefix . 'user_name_f', $this->prefix . 'core_user', 'firstName' );
		//$this->createIndex( 'idx_' . $this->prefix . 'user_name_m', $this->prefix . 'core_user', 'middleName' );
		//$this->createIndex( 'idx_' . $this->prefix . 'user_name_l', $this->prefix . 'core_user', 'lastName' );
		//$this->createIndex( 'idx_' . $this->prefix . 'user_mobile', $this->prefix . 'core_user', 'mobile' );
		//$this->createIndex( 'idx_' . $this->prefix . 'user_phone', $this->prefix . 'core_user', 'phone' );
		//$this->createIndex( 'idx_' . $this->prefix . 'user_tzone', $this->prefix . 'core_user', 'timeZone' );
		$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_user' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'user_name' . '(name ASC)' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_user' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'user_search' . '(name ASC, message ASC, description ASC, content ASC)' );

		// Site
		$this->createIndex( 'idx_' . $this->prefix . 'site_name', $this->prefix . 'core_site', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'site_slug', $this->prefix . 'core_site', 'slug' );
		//$this->createIndex( 'idx_' . $this->prefix . 'site_slug', $this->prefix . 'core_site', 'icon' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_site' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'site_desc' . '(description ASC)' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_site' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'site_search' . '(name ASC, title ASC, description ASC, content ASC)' );

		// Site Meta
		$this->createIndex( 'idx_' . $this->prefix . 'site_meta_name', $this->prefix . 'core_site_meta', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'site_meta_type', $this->prefix . 'core_site_meta', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'site_meta_label', $this->prefix . 'core_site_meta', 'label' );
		//$this->createIndex( 'idx_' . $this->prefix . 'site_meta_vtype', $this->prefix . 'core_site_meta', 'valueType' );
		//$this->createIndex( 'idx_' . $this->prefix . 'site_meta_mit', $this->prefix . 'core_site_meta', [ 'modelId', 'type' ] );
		//$this->createIndex( 'idx_' . $this->prefix . 'site_meta_mitn', $this->prefix . 'core_site_meta', [ 'modelId', 'type', 'name' ] );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_site_meta' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'site_meta_search' . '(name ASC, value ASC)' );

		// Site Access
		$this->createIndex( 'idx_' . $this->prefix . 'site_access_ip', $this->prefix . 'core_site_access', 'ip' );
		$this->createIndex( 'idx_' . $this->prefix . 'site_access_ipn', $this->prefix . 'core_site_access', 'ipNum' );
		//$this->createIndex( 'idx_' . $this->prefix . 'site_access_con', $this->prefix . 'core_site_access', 'controller' );
		//$this->createIndex( 'idx_' . $this->prefix . 'site_access_act', $this->prefix . 'core_site_access', 'action' );

		// File
		$this->createIndex( 'idx_' . $this->prefix . 'file_name', $this->prefix . 'core_file', 'name' );
		//$this->createIndex( 'idx_' . $this->prefix . 'file_ext', $this->prefix . 'core_file', 'extension' );
		//$this->createIndex( 'idx_' . $this->prefix . 'file_dir', $this->prefix . 'core_file', 'directory' );
		$this->createIndex( 'idx_' . $this->prefix . 'file_type', $this->prefix . 'core_file', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'file_storage', $this->prefix . 'core_file', 'storage' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_file' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'file_desc' . '(description ASC)' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_file' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'file_search' . '(name ASC, title ASC, description ASC, content ASC)' );

		// Gallery
		$this->createIndex( 'idx_' . $this->prefix . 'gallery_name', $this->prefix . 'core_gallery', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'gallery_slug', $this->prefix . 'core_gallery', 'slug' );
		$this->createIndex( 'idx_' . $this->prefix . 'gallery_type', $this->prefix . 'core_gallery', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'gallery_icon', $this->prefix . 'core_gallery', 'icon' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_gallery' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'gallery_desc' . '(description ASC)' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_gallery' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'gallery_search' . '(name ASC, title ASC, description ASC, content ASC)' );

		// Form
		$this->createIndex( 'idx_' . $this->prefix . 'form_name', $this->prefix . 'core_form', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'form_slug', $this->prefix . 'core_form', 'slug' );
		$this->createIndex( 'idx_' . $this->prefix . 'form_type', $this->prefix . 'core_form', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'form_icon', $this->prefix . 'core_form', 'icon' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_form' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'form_desc' . '(description ASC)' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_form' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'form_search' . '(name ASC, title ASC, description ASC, content ASC)' );

		// Form Field
		//$this->createIndex( 'idx_' . $this->prefix . 'form_field_icon', $this->prefix . 'core_form_field', 'icon' );

		// Tag
		$this->createIndex( 'idx_' . $this->prefix . 'tag_name', $this->prefix . 'core_tag', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'tag_slug', $this->prefix . 'core_tag', 'slug' );
		$this->createIndex( 'idx_' . $this->prefix . 'tag_type', $this->prefix . 'core_tag', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'tag_icon', $this->prefix . 'core_tag', 'icon' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_tag' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'tag_desc' . '(description ASC)' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_tag' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'tag_search' . '(name ASC, title ASC, description ASC)' );

		// Category
		$this->createIndex( 'idx_' . $this->prefix . 'category_name', $this->prefix . 'core_category', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'category_slug', $this->prefix . 'core_category', 'slug' );
		$this->createIndex( 'idx_' . $this->prefix . 'category_type', $this->prefix . 'core_category', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'category_icon', $this->prefix . 'core_category', 'icon' );
		//$this->createIndex( 'idx_' . $this->prefix . 'category_tf', $this->prefix . 'core_category', [ 'type', 'featured' ] );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_category' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'category_desc' . '(description ASC)' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_category' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'category_search' . '(name ASC, title ASC, description ASC, content ASC)' );

		// Option
		$this->createIndex( 'idx_' . $this->prefix . 'option_name', $this->prefix . 'core_option', 'name' );
		//$this->createIndex( 'idx_' . $this->prefix . 'option_value', $this->prefix . 'core_option', 'value' );
		//$this->createIndex( 'idx_' . $this->prefix . 'option_icon', $this->prefix . 'core_option', 'icon' );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_option' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'option_search' . '(name ASC, title ASC, value ASC, content ASC)' );
	}

	private function upDependent() {

		// Model message
		$this->createIndex( 'idx_' . $this->prefix . 'model_message_parent_t', $this->prefix . 'core_model_message', 'parentType' );
		//$this->createIndex( 'idx_' . $this->prefix . 'model_message_name', $this->prefix . 'core_model_message', 'name' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_message_type', $this->prefix . 'core_model_message', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'model_message_pipt', $this->prefix . 'core_model_message', [ 'parentId', 'parentType' ] );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_model_message' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'model_message_search' . '(name ASC, value ASC)' );

		// Model Comment
		//$this->createIndex( 'idx_' . $this->prefix . 'model_comment_name', $this->prefix . 'core_model_comment', 'name' );
		//$this->createIndex( 'idx_' . $this->prefix . 'model_comment_email', $this->prefix . 'core_model_comment', 'email' );
		//$this->createIndex( 'idx_' . $this->prefix . 'model_comment_ip', $this->prefix . 'core_model_comment', 'ip' );
		//$this->createIndex( 'idx_' . $this->prefix . 'model_comment_ipn', $this->prefix . 'core_model_comment', 'ipNum' );
		//$this->createIndex( 'idx_' . $this->prefix . 'model_comment_agent', $this->prefix . 'core_model_comment', 'agent' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_comment_type', $this->prefix . 'core_model_comment', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'model_comment_pipt', $this->prefix . 'core_model_comment', [ 'parentId', 'parentType' ] );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_model_comment' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'model_comment_search' . '(name ASC, email ASC, agent ASC, content ASC)' );

		// Model Meta
		//$this->createIndex( 'idx_' . $this->prefix . 'model_meta_parent_t', $this->prefix . 'core_model_meta', 'parentType' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_meta_type', $this->prefix . 'core_model_meta', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'model_meta_type_v', $this->prefix . 'core_model_meta', 'valueType' );
		//$this->createIndex( 'idx_' . $this->prefix . 'model_meta_piptt', $this->prefix . 'core_model_meta', [ 'parentId', 'parentType', 'type' ] );
		//$this->createIndex( 'idx_' . $this->prefix . 'model_meta_pipttn', $this->prefix . 'core_model_meta', [ 'parentId', 'parentType', 'type', 'name' ] );
		//$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_model_meta' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'model_meta_search' . '(name ASC, value ASC)' );

		// Model Object
		//$this->createIndex( 'idx_' . $this->prefix . 'model_object_parent_t', $this->prefix . 'core_model_object', 'parentType' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_object_type', $this->prefix . 'core_model_object', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'model_object_pipta', $this->prefix . 'core_model_object', [ 'parentId', 'parentType', 'active' ] );
		$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_model_object' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'model_object_nodes' . '(nodes ASC)' );

		// Model Address
		//$this->createIndex( 'idx_' . $this->prefix . 'model_address_parent_t', $this->prefix . 'core_model_address', 'parentType' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_address_type', $this->prefix . 'core_model_address', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'model_address_pipta', $this->prefix . 'core_model_address', [ 'parentId', 'parentType', 'active' ] );

		// Model File
		//$this->createIndex( 'idx_' . $this->prefix . 'model_file_parent_t', $this->prefix . 'core_model_file', 'parentType' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_file_type', $this->prefix . 'core_model_file', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'model_file_pipta', $this->prefix . 'core_model_file', [ 'parentId', 'parentType', 'active' ] );

		// Model Gallery
		//$this->createIndex( 'idx_' . $this->prefix . 'model_gallery_parent_t', $this->prefix . 'core_model_gallery', 'parentType' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_gallery_type', $this->prefix . 'core_model_gallery', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'model_gallery_pipta', $this->prefix . 'core_model_gallery', [ 'parentId', 'parentType', 'active' ] );

		// Model Tag
		//$this->createIndex( 'idx_' . $this->prefix . 'model_tag_parent_t', $this->prefix . 'core_model_tag', 'parentType' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_tag_type', $this->prefix . 'core_model_tag', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'model_tag_pipta', $this->prefix . 'core_model_tag', [ 'parentId', 'parentType', 'active' ] );

		// Model Category
		//$this->createIndex( 'idx_' . $this->prefix . 'model_category_parent_t', $this->prefix . 'core_model_category', 'parentType' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_category_type', $this->prefix . 'core_model_category', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'model_category_pipta', $this->prefix . 'core_model_category', [ 'parentId', 'parentType', 'active' ] );
		$this->execute( 'ALTER TABLE ' . $this->prefix . 'core_model_category' . ' ADD FULLTEXT ' . 'idx_' . $this->prefix . 'model_category_nodes' . '(nodes ASC)' );

		// Model Option
		//$this->createIndex( 'idx_' . $this->prefix . 'model_option_parent_t', $this->prefix . 'core_model_option', 'parentType' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_option_type', $this->prefix . 'core_model_option', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'model_option_pipta', $this->prefix . 'core_model_option', [ 'parentId', 'parentType', 'active' ] );

		// Model Form
		//$this->createIndex( 'idx_' . $this->prefix . 'model_form_parent_t', $this->prefix . 'core_model_form', 'parentType' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_form_type', $this->prefix . 'core_model_form', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'model_form_pipta', $this->prefix . 'core_model_form', [ 'parentId', 'parentType', 'active' ] );

		// Model Follower
		//$this->createIndex( 'idx_' . $this->prefix . 'model_follower_parent_t', $this->prefix . 'core_model_follower', 'parentType' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_follower_type', $this->prefix . 'core_model_follower', 'type' );
		//$this->createIndex( 'idx_' . $this->prefix . 'model_follower_pipta', $this->prefix . 'core_model_follower', [ 'parentId', 'parentType', 'active' ] );
	}

	public function down() {

		$this->downPrimary();

		$this->downDependent();
	}

	private function downPrimary() {

		// Locale
		$this->dropIndex( 'idx_' . $this->prefix . 'locale_name', $this->prefix . 'core_locale' );
		$this->dropIndex( 'idx_' . $this->prefix . 'locale_code', $this->prefix . 'core_locale' );

		// Theme
		$this->dropIndex( 'idx_' . $this->prefix . 'theme_name', $this->prefix . 'core_theme' );
		$this->dropIndex( 'idx_' . $this->prefix . 'theme_slug', $this->prefix . 'core_theme' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'theme_slug', $this->prefix . 'core_theme' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'theme_rendrer', $this->prefix . 'core_theme' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'theme_desc', $this->prefix . 'core_theme' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'theme_search', $this->prefix . 'core_theme' );

		// Template
		$this->dropIndex( 'idx_' . $this->prefix . 'template_name', $this->prefix . 'core_template' );
		$this->dropIndex( 'idx_' . $this->prefix . 'template_slug', $this->prefix . 'core_template' );
		$this->dropIndex( 'idx_' . $this->prefix . 'template_type', $this->prefix . 'core_template' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'template_icon', $this->prefix . 'core_template' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'template_rendrer', $this->prefix . 'core_template' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'template_layout', $this->prefix . 'core_template' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'template_desc', $this->prefix . 'core_template' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'template_search', $this->prefix . 'core_template' );

		// Object
		$this->dropIndex( 'idx_' . $this->prefix . 'object_name', $this->prefix . 'core_object' );
		$this->dropIndex( 'idx_' . $this->prefix . 'object_slug', $this->prefix . 'core_object' );
		$this->dropIndex( 'idx_' . $this->prefix . 'object_type', $this->prefix . 'core_object' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'object_icon', $this->prefix . 'core_object' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'object_desc', $this->prefix . 'core_object' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'object_search', $this->prefix . 'core_object' );

		// Country
		$this->dropIndex( 'idx_' . $this->prefix . 'country_name', $this->prefix . 'core_country' );
		$this->dropIndex( 'idx_' . $this->prefix . 'country_code', $this->prefix . 'core_country', 'code' );
		$this->dropIndex( 'idx_' . $this->prefix . 'country_iso', $this->prefix . 'core_country', 'iso' );

		// Province
		$this->dropIndex( 'idx_' . $this->prefix . 'province_name', $this->prefix . 'core_province' );
		$this->dropIndex( 'idx_' . $this->prefix . 'province_code', $this->prefix . 'core_province', 'code' );
		$this->dropIndex( 'idx_' . $this->prefix . 'province_iso', $this->prefix . 'core_province', 'iso' );

		// City
		$this->dropIndex( 'idx_' . $this->prefix . 'city_name', $this->prefix . 'core_city' );
		$this->dropIndex( 'idx_' . $this->prefix . 'city_iso', $this->prefix . 'core_city' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'city_postal', $this->prefix . 'core_city' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'city_zone', $this->prefix . 'core_city' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'city_tzone', $this->prefix . 'core_city' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'city_lat', $this->prefix . 'core_city' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'city_lon', $this->prefix . 'core_city' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'city_lat_lon', $this->prefix . 'core_city' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'city_reg', $this->prefix . 'core_city' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'city_zip', $this->prefix . 'core_city' );

		// Address
		//$this->dropIndex( 'idx_' . $this->prefix . 'address_title', $this->prefix . 'core_address' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'address_line1', $this->prefix . 'core_address' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'address_line2', $this->prefix . 'core_address' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'address_line3', $this->prefix . 'core_address' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'address_country_n', $this->prefix . 'core_address' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'address_province_n', $this->prefix . 'core_address' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'address_city_n', $this->prefix . 'core_address' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'address_zip', $this->prefix . 'core_address' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'address_zip_sub', $this->prefix . 'core_address' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'address_name_f', $this->prefix . 'core_address' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'address_name_l', $this->prefix . 'core_address' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'address_phone', $this->prefix . 'core_address' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'address_email', $this->prefix . 'core_address' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'address_fax', $this->prefix . 'core_address' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'address_lat', $this->prefix . 'core_address' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'address_lon', $this->prefix . 'core_address' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'address_lat_lon', $this->prefix . 'core_address' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'address_search', $this->prefix . 'core_address' );

		// Role
		$this->dropIndex( 'idx_' . $this->prefix . 'role_name', $this->prefix . 'core_role' );
		$this->dropIndex( 'idx_' . $this->prefix . 'role_slug', $this->prefix . 'core_role' );
		$this->dropIndex( 'idx_' . $this->prefix . 'role_type', $this->prefix . 'core_role' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'role_icon', $this->prefix . 'core_role' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'role_desc', $this->prefix . 'core_role' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'role_search', $this->prefix . 'core_role' );

		// Permission
		$this->dropIndex( 'idx_' . $this->prefix . 'permission_name', $this->prefix . 'core_permission' );
		$this->dropIndex( 'idx_' . $this->prefix . 'permission_slug', $this->prefix . 'core_permission' );
		$this->dropIndex( 'idx_' . $this->prefix . 'permission_type', $this->prefix . 'core_permission' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'permission_icon', $this->prefix . 'core_permission' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'permission_desc', $this->prefix . 'core_permission' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'permission_search', $this->prefix . 'core_permission' );

		// User
		$this->dropIndex( 'idx_' . $this->prefix . 'user_email', $this->prefix . 'core_user' );
		$this->dropIndex( 'idx_' . $this->prefix . 'user_username', $this->prefix . 'core_user' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'user_name_f', $this->prefix . 'core_user' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'user_name_m', $this->prefix . 'core_user' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'user_name_l', $this->prefix . 'core_user' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'user_mobile', $this->prefix . 'core_user' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'user_phone', $this->prefix . 'core_user' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'user_tzone', $this->prefix . 'core_user' );
		$this->dropIndex( 'idx_' . $this->prefix . 'user_name', $this->prefix . 'core_user' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'user_search', $this->prefix . 'core_user' );

		// Site
		$this->dropIndex( 'idx_' . $this->prefix . 'site_name', $this->prefix . 'core_site' );
		$this->dropIndex( 'idx_' . $this->prefix . 'site_slug', $this->prefix . 'core_site' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'site_slug', $this->prefix . 'core_site' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'site_desc', $this->prefix . 'core_site' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'site_search', $this->prefix . 'core_site' );

		// Site Meta
		$this->dropIndex( 'idx_' . $this->prefix . 'site_meta_name', $this->prefix . 'core_site_meta' );
		$this->dropIndex( 'idx_' . $this->prefix . 'site_meta_type', $this->prefix . 'core_site_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'site_meta_label', $this->prefix . 'core_site_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'site_meta_vtype', $this->prefix . 'core_site_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'site_meta_mit', $this->prefix . 'core_site_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'site_meta_mitn', $this->prefix . 'core_site_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'site_meta_search', $this->prefix . 'core_site_meta' );

		// Site Access
		$this->dropIndex( 'idx_' . $this->prefix . 'site_access_ip', $this->prefix . 'core_site_access' );
		$this->dropIndex( 'idx_' . $this->prefix . 'site_access_ipn', $this->prefix . 'core_site_access' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'site_access_con', $this->prefix . 'core_site_access' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'site_access_act', $this->prefix . 'core_site_access' );

		// File
		$this->dropIndex( 'idx_' . $this->prefix . 'file_name', $this->prefix . 'core_file' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'file_ext', $this->prefix . 'core_file' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'file_dir', $this->prefix . 'core_file' );
		$this->dropIndex( 'idx_' . $this->prefix . 'file_type', $this->prefix . 'core_file' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'file_storage', $this->prefix . 'core_file' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'file_desc', $this->prefix . 'core_file' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'file_search', $this->prefix . 'core_file' );

		// Gallery
		$this->dropIndex( 'idx_' . $this->prefix . 'gallery_name', $this->prefix . 'core_gallery' );
		$this->dropIndex( 'idx_' . $this->prefix . 'gallery_slug', $this->prefix . 'core_gallery' );
		$this->dropIndex( 'idx_' . $this->prefix . 'gallery_type', $this->prefix . 'core_gallery' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'gallery_icon', $this->prefix . 'core_gallery' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'gallery_desc', $this->prefix . 'core_gallery' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'gallery_search', $this->prefix . 'core_gallery' );

		// Form
		$this->dropIndex( 'idx_' . $this->prefix . 'form_name', $this->prefix . 'core_form' );
		$this->dropIndex( 'idx_' . $this->prefix . 'form_slug', $this->prefix . 'core_form' );
		$this->dropIndex( 'idx_' . $this->prefix . 'form_type', $this->prefix . 'core_form' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'form_icon', $this->prefix . 'core_form' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'form_desc', $this->prefix . 'core_form' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'form_search', $this->prefix . 'core_form' );

		// Form Field
		//$this->dropIndex( 'idx_' . $this->prefix . 'form_field_icon', $this->prefix . 'core_form_field' );

		// Tag
		$this->dropIndex( 'idx_' . $this->prefix . 'tag_name', $this->prefix . 'core_tag' );
		$this->dropIndex( 'idx_' . $this->prefix . 'tag_slug', $this->prefix . 'core_tag' );
		$this->dropIndex( 'idx_' . $this->prefix . 'tag_type', $this->prefix . 'core_tag' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'tag_icon', $this->prefix . 'core_tag' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'tag_desc', $this->prefix . 'core_tag' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'tag_search', $this->prefix . 'core_tag' );

		// Category
		$this->dropIndex( 'idx_' . $this->prefix . 'category_name', $this->prefix . 'core_category' );
		$this->dropIndex( 'idx_' . $this->prefix . 'category_slug', $this->prefix . 'core_category' );
		$this->dropIndex( 'idx_' . $this->prefix . 'category_type', $this->prefix . 'core_category' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'category_icon', $this->prefix . 'core_category' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'category_tf', $this->prefix . 'core_category' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'category_desc', $this->prefix . 'core_category' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'category_search', $this->prefix . 'core_category' );

		// Option
		$this->dropIndex( 'idx_' . $this->prefix . 'option_name', $this->prefix . 'core_option' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'option_value', $this->prefix . 'core_option' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'option_icon', $this->prefix . 'core_option' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'option_search', $this->prefix . 'core_option' );
	}

	private function downDependent() {

		// Model message
		$this->dropIndex( 'idx_' . $this->prefix . 'model_message_parent_t', $this->prefix . 'core_model_message' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_message_name', $this->prefix . 'core_model_message' );
		$this->dropIndex( 'idx_' . $this->prefix . 'model_message_type', $this->prefix . 'core_model_message' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_message_pipt', $this->prefix . 'core_model_message' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_message_search', $this->prefix . 'core_model_message' );

		// Model Comment
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_comment_name', $this->prefix . 'core_model_comment' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_comment_email', $this->prefix . 'core_model_comment' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_comment_ip', $this->prefix . 'core_model_comment' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_comment_ipn', $this->prefix . 'core_model_comment' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_comment_agent', $this->prefix . 'core_model_comment' );
		$this->dropIndex( 'idx_' . $this->prefix . 'model_comment_type', $this->prefix . 'core_model_comment', 'type' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_comment_pipt', $this->prefix . 'core_model_comment' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_comment_search', $this->prefix . 'core_model_comment' );

		// Model Meta
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_meta_parent_t', $this->prefix . 'core_model_meta' );
		$this->dropIndex( 'idx_' . $this->prefix . 'model_meta_type', $this->prefix . 'core_model_meta', 'type' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_meta_type_v', $this->prefix . 'core_model_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_meta_piptt', $this->prefix . 'core_model_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_meta_pipttn', $this->prefix . 'core_model_meta' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_meta_search', $this->prefix . 'core_model_meta' );

		// Model Object
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_object_parent_t', $this->prefix . 'core_model_object' );
		$this->dropIndex( 'idx_' . $this->prefix . 'model_object_type', $this->prefix . 'core_model_object' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_object_pipta', $this->prefix . 'core_model_object' );
		$this->dropIndex( 'idx_' . $this->prefix . 'model_object_nodes', $this->prefix . 'core_model_object' );

		// Model Address
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_address_parent_t', $this->prefix . 'core_model_address' );
		$this->dropIndex( 'idx_' . $this->prefix . 'model_address_type', $this->prefix . 'core_model_address' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_address_pipta', $this->prefix . 'core_model_address' );

		// Model File
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_file_parent_t', $this->prefix . 'core_model_file' );
		$this->dropIndex( 'idx_' . $this->prefix . 'model_file_type', $this->prefix . 'core_model_file' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_file_pipta', $this->prefix . 'core_model_file' );

		// Model Gallery
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_gallery_parent_t', $this->prefix . 'core_model_gallery' );
		$this->dropIndex( 'idx_' . $this->prefix . 'model_gallery_type', $this->prefix . 'core_model_gallery' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_gallery_pipta', $this->prefix . 'core_model_gallery' );

		// Model Tag
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_tag_parent_t', $this->prefix . 'core_model_tag' );
		$this->dropIndex( 'idx_' . $this->prefix . 'model_tag_type', $this->prefix . 'core_model_tag' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_tag_pipta', $this->prefix . 'core_model_tag' );

		// Model Category
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_category_parent_t', $this->prefix . 'core_model_category' );
		$this->dropIndex( 'idx_' . $this->prefix . 'model_category_type', $this->prefix . 'core_model_category' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_category_pipta', $this->prefix . 'core_model_category' );
		$this->dropIndex( 'idx_' . $this->prefix . 'model_category_nodes', $this->prefix . 'core_model_category' );

		// Model Option
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_option_parent_t', $this->prefix . 'core_model_option' );
		$this->dropIndex( 'idx_' . $this->prefix . 'model_option_type', $this->prefix . 'core_model_option' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_option_pipta', $this->prefix . 'core_model_option' );

		// Model Form
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_form_parent_t', $this->prefix . 'core_model_form' );
		$this->dropIndex( 'idx_' . $this->prefix . 'model_form_type', $this->prefix . 'core_model_form' );
		//$this->dropIndex( 'idx_' . $this->prefix . 'model_form_pipta', $this->prefix . 'core_model_form' );
	}

}
