<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

// CMG Imports
use cmsgears\core\common\models\base\Meta;

/**
 * The core migration inserts the database tables of core module. It also insert the foreign
 * keys if FK flag of migration component is true.
 *
 * Made some of the fields size consistent as listed below:
 * * Name - 512 bytes (It can be changed to 1024 bytes to support larger names. MySQL does not support
 * indexing for string length beyond 1024 bytes. In case longer names are required, the name index must be
 * dropped first. A FULLTEXT index can be used in such cases.)
 * * Slug - 1024 bytes (Slug will be generated using name for first time. It can be changed later.)
 * * Type - 160 bytes (Used to group and classify the table rows.)
 * * Title - 2048 bytes (Can be used for longer names instead of Name. It can't be indexed. )
 * * Description - 4096 bytes (Used to describe the model in brief.)
 *
 * @since 1.0.0
 */
class m160620_095703_core extends \cmsgears\core\common\base\Migration {

	// Public Variables

	public $fk;
	public $options;

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;

		// Get the values via config
		$this->fk		= Yii::$app->migration->isFk();
		$this->options	= Yii::$app->migration->getTableOptions();

		// Default collation
		if( $this->db->driverName === 'mysql' ) {

			$this->options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
	}

	public function up() {

		// I18N
		$this->upLocale();

		// Theming
		$this->upTheme();
		$this->upTemplate();

		// Generic Object
		$this->upObject();
		$this->upObjectMeta();

		// Location
		$this->upCountry();
		$this->upProvince();
		$this->upRegion();
		$this->upCity();
		$this->upAddress();
		$this->upLocation();

		// RBAC
		$this->upRole();
		$this->upPermission();
		$this->upRolePermission();

		// User
		$this->upUser();
		$this->upUserMeta();
		$this->upSite();
		$this->upSiteMeta();
		$this->upSiteMember();
		$this->upSiteAccess();
		$this->upOtp();

		// Files
		$this->upFile();
		$this->upGallery();

		// Forms
		$this->upForm();
		$this->upFormField();

		// Grouping and group options
		$this->upTag();
		$this->upCategory();
		$this->upOption();

		// Dependent linking
		$this->upDependency();

		// Traits - Resources
		$this->upModelHierarchy();
		$this->upLocaleMessage();
		$this->upModelMessage();
		$this->upModelComment();
		$this->upModelAnalytics();
		$this->upModelHistory();
		$this->upModelMeta();

		// Traits - Mappers
		$this->upModelObject();
		$this->upModelAddress();
		$this->upModelLocation();
		$this->upModelFile();
		$this->upModelGallery();
		$this->upModelTag();
		$this->upModelCategory();
		$this->upModelOption();
		$this->upModelForm();
		$this->upModelFollower();

		if( $this->fk ) {

			$this->generateForeignKeys();
		}
	}

	private function upLocale() {

		$this->createTable( $this->prefix . 'core_locale', [
			'id' => $this->bigPrimaryKey( 20 ),
			'code' => $this->string( Yii::$app->core->smallText )->notNull(),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull()
		], $this->options );
	}

	private function upTheme() {

		$this->createTable( $this->prefix . 'core_theme', [
			'id' => $this->bigPrimaryKey( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'title' => $this->string( Yii::$app->core->xxxLargeText ),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'default' => $this->boolean()->notNull()->defaultValue( false ),
			'renderer' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'basePath' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'theme_creator', $this->prefix . 'core_theme', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'theme_modifier', $this->prefix . 'core_theme', 'modifiedBy' );
	}

	private function upTemplate() {

		$this->createTable( $this->prefix . 'core_template', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 ),
			'themeId' => $this->bigInteger( 20 ),
			'previewId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'title' => $this->string( Yii::$app->core->xxxLargeText ),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'active' => $this->boolean()->notNull()->defaultValue( false ),
			'frontend' => $this->boolean()->notNull()->defaultValue( false ),
			'classPath' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'dataPath' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'dataForm' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'attributesPath' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'attributesForm' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'configPath' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'configForm' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'settingsPath' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'settingsForm' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'renderer' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'fileRender' => $this->boolean()->notNull()->defaultValue( false ),
			'layout' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'layoutGroup' => $this->boolean()->notNull()->defaultValue( false ),
			'viewPath' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'view' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'htmlOptions' => $this->mediumText(),
			'help' => $this->mediumText(),
			'message' => $this->text(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'template_site', $this->prefix . 'core_template', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'template_theme', $this->prefix . 'core_template', 'themeId' );
		$this->createIndex( 'idx_' . $this->prefix . 'template_preview', $this->prefix . 'core_template', 'previewId' );
		$this->createIndex( 'idx_' . $this->prefix . 'template_creator', $this->prefix . 'core_template', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'template_modifier', $this->prefix . 'core_template', 'modifiedBy' );
	}

	private function upObject() {

		$this->createTable( $this->prefix . 'core_object', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 ),
			'themeId' => $this->bigInteger( 20 ),
			'templateId' => $this->bigInteger( 20 ),
			'userId' => $this->bigInteger( 20 ),
			'parentId' => $this->bigInteger( 20 ),
			'avatarId' => $this->bigInteger( 20 ),
			'bannerId' => $this->bigInteger( 20 ),
			'videoId' => $this->bigInteger( 20 ),
			'galleryId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'texture' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'title' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'classPath' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'viewPath' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'link' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'visibility' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'pinned' => $this->boolean()->notNull()->defaultValue( false ),
			'featured' => $this->boolean()->notNull()->defaultValue( false ),
			'popular' => $this->boolean()->notNull()->defaultValue( false ),
			'shared' => $this->boolean()->notNull()->defaultValue( false ), // Shared Object
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'htmlOptions' => $this->text(),
			'summary' => $this->text(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'object_site', $this->prefix . 'core_object', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'object_theme', $this->prefix . 'core_object', 'themeId' );
		$this->createIndex( 'idx_' . $this->prefix . 'object_template', $this->prefix . 'core_object', 'templateId' );
		$this->createIndex( 'idx_' . $this->prefix . 'object_user', $this->prefix . 'core_object', 'userId' );
		$this->createIndex( 'idx_' . $this->prefix . 'object_parent', $this->prefix . 'core_object', 'parentId' );
		$this->createIndex( 'idx_' . $this->prefix . 'object_avatar', $this->prefix . 'core_object', 'avatarId' );
		$this->createIndex( 'idx_' . $this->prefix . 'object_banner', $this->prefix . 'core_object', 'bannerId' );
		$this->createIndex( 'idx_' . $this->prefix . 'object_video', $this->prefix . 'core_object', 'videoId' );
		$this->createIndex( 'idx_' . $this->prefix . 'object_gallery', $this->prefix . 'core_object', 'galleryId' );
		$this->createIndex( 'idx_' . $this->prefix . 'object_creator', $this->prefix . 'core_object', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'object_modifier', $this->prefix . 'core_object', 'modifiedBy' );
	}

	private function upObjectMeta() {

		$this->createTable( $this->prefix . 'core_object_meta', [
			'id' => $this->bigPrimaryKey( 20 ),
			'modelId' => $this->bigInteger( 20 )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'label' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'active' => $this->boolean()->notNull()->defaultValue( false ),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'valueType' => $this->string( Yii::$app->core->mediumText )->notNull()->defaultValue( Meta::VALUE_TYPE_TEXT ),
			'value' => $this->text(),
			'data' => $this->mediumText()
		], $this->options );

		// Index for column parent
		$this->createIndex( 'idx_' . $this->prefix . 'object_meta_parent', $this->prefix . 'core_object_meta', 'modelId' );
	}

	private function upCountry() {

		$this->createTable( $this->prefix . 'core_country', [
			'id' => $this->bigPrimaryKey( 20 ),
			'code' => $this->string( Yii::$app->core->smallText )->notNull(),
			'iso' => $this->string( Yii::$app->core->smallText ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'title' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null )
		], $this->options );
	}

	private function upProvince() {

		$this->createTable( $this->prefix . 'core_province', [
			'id' => $this->bigPrimaryKey( 20 ),
			'countryId' => $this->bigInteger( 20 )->notNull(),
			'code' => $this->string( Yii::$app->core->smallText )->notNull(),
			'iso' => $this->string( Yii::$app->core->smallText ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'title' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null )
		], $this->options );

		// Index for columns country
		$this->createIndex( 'idx_' . $this->prefix . 'province_country', $this->prefix . 'core_province', 'countryId' );
	}

	private function upRegion() {

		$this->createTable( $this->prefix . 'core_region', [
			'id' => $this->bigPrimaryKey( 20 ),
			'countryId' => $this->bigInteger( 20 )->notNull(),
			'provinceId' => $this->bigInteger( 20 )->notNull(),
			'code' => $this->string( Yii::$app->core->smallText )->defaultValue( null ),
			'iso' => $this->string( Yii::$app->core->smallText )->defaultValue( null ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'title' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null )
		], $this->options );

		$this->createIndex( 'idx_' . $this->prefix . 'region_country', $this->prefix . 'core_region', 'countryId' );
		$this->createIndex( 'idx_' . $this->prefix . 'region_province', $this->prefix . 'core_region', 'provinceId' );
	}

	private function upCity() {

		// Didn't add any dedicated table for zone(sub division) of a particular province/state, hence storing name directly.
		$this->createTable( $this->prefix . 'core_city', [
			'id' => $this->bigPrimaryKey( 20 ),
			'countryId' => $this->bigInteger( 20 )->notNull(),
			'provinceId' => $this->bigInteger( 20 ),
			'regionId' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'title' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'code' => $this->string( Yii::$app->core->smallText )->defaultValue( null ),
			'iso' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'postal' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'zone' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'regions' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'zipCodes' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'timeZone' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'latitude' => $this->float()->defaultValue( 0 ),
			'longitude' => $this->float()->defaultValue( 0 ),
			'autoCache' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null )
		], $this->options );

		// Index for columns country
		$this->createIndex( 'idx_' . $this->prefix . 'city_country', $this->prefix . 'core_city', 'countryId' );
		$this->createIndex( 'idx_' . $this->prefix . 'city_province', $this->prefix . 'core_city', 'provinceId' );
		$this->createIndex( 'idx_' . $this->prefix . 'city_region', $this->prefix . 'core_city', 'regionId' );
	}

	private function upAddress() {

		$this->createTable( $this->prefix . 'core_address', [
			'id' => $this->bigPrimaryKey( 20 ),
			'countryId' => $this->bigInteger( 20 )->notNull(),
			'provinceId' => $this->bigInteger( 20 ),
			'regionId' => $this->bigInteger( 20 ),
			'cityId' => $this->bigInteger( 20 ),
			'title' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'line1' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'line2' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'line3' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'countryName' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'provinceName' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'regionName' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'cityName' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'zip' => $this->string( Yii::$app->core->smallText )->defaultValue( null ),
			'subZip' => $this->string( Yii::$app->core->smallText )->defaultValue( null ),
			'firstName' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'lastName' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'phone' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'email' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'fax' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'website' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'landmark' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'latitude' => $this->float()->defaultValue( 0 ),
			'longitude' => $this->float()->defaultValue( 0 ),
			'zoomLevel' => $this->smallInteger( 6 )->defaultValue( 5 )
		], $this->options );

		// Index for columns country
		$this->createIndex( 'idx_' . $this->prefix . 'address_country', $this->prefix . 'core_address', 'countryId' );
		$this->createIndex( 'idx_' . $this->prefix . 'address_province', $this->prefix . 'core_address', 'provinceId' );
		$this->createIndex( 'idx_' . $this->prefix . 'address_region', $this->prefix . 'core_address', 'regionId' );
		$this->createIndex( 'idx_' . $this->prefix . 'address_city', $this->prefix . 'core_address', 'cityId' );
	}

	private function upLocation() {

		$this->createTable( $this->prefix . 'core_location', [
			'id' => $this->bigPrimaryKey( 20 ),
			'countryId' => $this->bigInteger( 20 ),
			'provinceId' => $this->bigInteger( 20 ),
			'regionId' => $this->bigInteger( 20 ),
			'cityId' => $this->bigInteger( 20 ),
			'title' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'countryName' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'provinceName' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'regionName' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'cityName' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'zip' => $this->string( Yii::$app->core->smallText )->defaultValue( null ),
			'subZip' => $this->string( Yii::$app->core->smallText )->defaultValue( null ),
			'latitude' => $this->float(),
			'longitude' => $this->float(),
			'zoomLevel' => $this->smallInteger( 6 )->defaultValue( 5 ),
			'accessedAt' => $this->dateTime(),
			'notes' => $this->text(),
			'data' => $this->mediumText()
		], $this->options );

		// Index for columns country
		$this->createIndex( 'idx_' . $this->prefix . 'location_country', $this->prefix . 'core_location', 'countryId' );
		$this->createIndex( 'idx_' . $this->prefix . 'location_province', $this->prefix . 'core_location', 'provinceId' );
		$this->createIndex( 'idx_' . $this->prefix . 'location_region', $this->prefix . 'core_location', 'regionId' );
		$this->createIndex( 'idx_' . $this->prefix . 'location_city', $this->prefix . 'core_location', 'cityId' );
	}

	private function upRole() {

		$this->createTable( $this->prefix . 'core_role', [
			'id' => $this->bigPrimaryKey( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'group' => $this->boolean()->notNull()->defaultValue( false ),
			'adminUrl' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'homeUrl' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'role_creator', $this->prefix . 'core_role', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'role_modifier', $this->prefix . 'core_role', 'modifiedBy' );
	}

	private function upPermission() {

		$this->createTable( $this->prefix . 'core_permission', [
			'id' => $this->bigPrimaryKey( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'group' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'permission_creator', $this->prefix . 'core_permission', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'permission_modifier', $this->prefix . 'core_permission', 'modifiedBy' );
	}

	private function upRolePermission() {

		$this->createTable( $this->prefix . 'core_role_permission', [
			'roleId' => $this->bigInteger( 20 )->notNull(),
			'permissionId' => $this->bigInteger( 20 )->notNull(),
			'PRIMARY KEY( roleId, permissionId )',
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'role_permission_role', $this->prefix . 'core_role_permission', 'roleId' );
		$this->createIndex( 'idx_' . $this->prefix . 'role_permission_perm', $this->prefix . 'core_role_permission', 'permissionId' );
	}

	private function upUser() {

		$this->createTable( $this->prefix . 'core_user', [
			'id' => $this->bigPrimaryKey( 20 ),
			'localeId' => $this->bigInteger( 20 ),
			'genderId' => $this->bigInteger( 20 ),
			'maritalId' => $this->bigInteger( 20 ),
			'avatarId' => $this->bigInteger( 20 ),
			'bannerId' => $this->bigInteger( 20 ),
			'videoId' => $this->bigInteger( 20 ),
			'galleryId' => $this->bigInteger( 20 ),
			'templateId' => $this->bigInteger( 20 ),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'email' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'username' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'passwordHash' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText ),
			'title' => $this->string( Yii::$app->core->smallText )->defaultValue( null ),
			'firstName' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'middleName' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'lastName' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'name' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'message' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'dob' => $this->date(),
			'mobile' => $this->string( Yii::$app->core->smallText )->defaultValue( null ),
			'phone' => $this->string( Yii::$app->core->smallText )->defaultValue( null ),
			'emailVerified' => $this->boolean()->defaultValue( false ),
			'mobileVerified' => $this->boolean()->defaultValue( false ),
			'timeZone' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'avatarUrl' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'websiteUrl' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'verifyToken' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'verifyTokenValidTill' => $this->dateTime()->defaultValue( null ),
			'resetToken' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'resetTokenValidTill' => $this->dateTime()->defaultValue( null ),
			'registeredAt' => $this->dateTime(),
			'lastLoginAt' => $this->dateTime(),
			'lastActivityAt' => $this->dateTime(),
			'authKey' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'otp' => $this->integer( 10 )->defaultValue( null ),
			'otpValidTill' => $this->dateTime()->defaultValue( null ),
			// Access Token
			'accessToken' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'accessTokenType' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'tokenCreatedAt' => $this->dateTime(),
			'tokenAccessedAt' => $this->dateTime(),
			// Content
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			// Cache
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns locale, gender and avatar
		$this->createIndex( 'idx_' . $this->prefix . 'user_locale', $this->prefix . 'core_user', 'localeId' );
		$this->createIndex( 'idx_' . $this->prefix . 'user_marital', $this->prefix . 'core_user', 'maritalId' );
		$this->createIndex( 'idx_' . $this->prefix . 'user_gender', $this->prefix . 'core_user', 'genderId' );
		$this->createIndex( 'idx_' . $this->prefix . 'user_avatar', $this->prefix . 'core_user', 'avatarId' );
		$this->createIndex( 'idx_' . $this->prefix . 'user_banner', $this->prefix . 'core_user', 'bannerId' );
		$this->createIndex( 'idx_' . $this->prefix . 'user_video', $this->prefix . 'core_user', 'videoId' );
		$this->createIndex( 'idx_' . $this->prefix . 'user_gallery', $this->prefix . 'core_user', 'galleryId' );
		$this->createIndex( 'idx_' . $this->prefix . 'user_template', $this->prefix . 'core_user', 'templateId' );
	}

	private function upUserMeta() {

		$this->createTable( $this->prefix . 'core_user_meta', [
			'id' => $this->bigPrimaryKey( 20 ),
			'modelId' => $this->bigInteger( 20 )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'label' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'active' => $this->boolean()->notNull()->defaultValue( false ),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'valueType' => $this->string( Yii::$app->core->mediumText )->notNull()->defaultValue( Meta::VALUE_TYPE_TEXT ),
			'value' => $this->text(),
			'data' => $this->mediumText()
		], $this->options );

		// Index for column parent
		$this->createIndex( 'idx_' . $this->prefix . 'user_meta_parent', $this->prefix . 'core_user_meta', 'modelId' );
	}

	private function upSite() {

		$this->createTable( $this->prefix . 'core_site', [
			'id' => $this->bigPrimaryKey( 20 ),
			'avatarId' => $this->bigInteger( 20 ),
			'bannerId' => $this->bigInteger( 20 ),
			'videoId' => $this->bigInteger( 20 ),
			'themeId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText ),
			'title' => $this->string( Yii::$app->core->xxxLargeText ),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'active' => $this->boolean()->notNull()->defaultValue( false ),
			'pinned' => $this->boolean()->notNull()->defaultValue( false ),
			'featured' => $this->boolean()->notNull()->defaultValue( false ),
			'popular' => $this->boolean()->notNull()->defaultValue( false ),
			'primary' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns avatar, banner and theme
		$this->createIndex( 'idx_' . $this->prefix . 'site_creator', $this->prefix . 'core_site', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'site_modifier', $this->prefix . 'core_site', 'modifiedBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'site_avatar', $this->prefix . 'core_site', 'avatarId' );
		$this->createIndex( 'idx_' . $this->prefix . 'site_banner', $this->prefix . 'core_site', 'bannerId' );
		$this->createIndex( 'idx_' . $this->prefix . 'site_video', $this->prefix . 'core_site', 'videoId' );
		$this->createIndex( 'idx_' . $this->prefix . 'site_theme', $this->prefix . 'core_site', 'themeId' );
	}

	private function upSiteMeta() {

		$this->createTable( $this->prefix . 'core_site_meta', [
			'id' => $this->bigPrimaryKey( 20 ),
			'modelId' => $this->bigInteger( 20 )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'label' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'active' => $this->boolean()->notNull()->defaultValue( false ),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'valueType' => $this->string( Yii::$app->core->mediumText )->notNull()->defaultValue( Meta::VALUE_TYPE_TEXT ),
			'value' => $this->text(),
			'data' => $this->mediumText()
		], $this->options );

		// Index for column parent
		$this->createIndex( 'idx_' . $this->prefix . 'site_meta_parent', $this->prefix . 'core_site_meta', 'modelId' );
	}

	private function upSiteMember() {

		$this->createTable( $this->prefix . 'core_site_member', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 )->notNull(),
			'userId' => $this->bigInteger( 20 )->notNull(),
			'roleId' => $this->bigInteger( 20 )->notNull(),
			'pinned' => $this->boolean()->notNull()->defaultValue( false ),
			'featured' => $this->boolean()->notNull()->defaultValue( false ),
			'popular' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime()
		], $this->options );

		// Index for columns avatar, banner and theme
		$this->createIndex( 'idx_' . $this->prefix . 'site_member_site', $this->prefix . 'core_site_member', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'site_member_user', $this->prefix . 'core_site_member', 'userId' );
		$this->createIndex( 'idx_' . $this->prefix . 'site_member_role', $this->prefix . 'core_site_member', 'roleId' );
	}

	private function upSiteAccess() {

		$this->createTable( $this->prefix . 'core_site_access', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 )->notNull(),
			'userId' => $this->bigInteger( 20 )->notNull(),
			'roleId' => $this->bigInteger( 20 )->notNull(),
			'ip' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'ipNum' => $this->integer( 11 )->defaultValue( 0 ),
			'agent' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'controller' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'action' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'url' => $this->string( Yii::$app->core->xxxLargeText )->notNull(),
			'failed' => $this->boolean()->notNull()->defaultValue( false ),
			'failCount' => $this->smallInteger( 6 ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns avatar, banner and theme
		$this->createIndex( 'idx_' . $this->prefix . 'site_access_site', $this->prefix . 'core_site_access', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'site_access_user', $this->prefix . 'core_site_access', 'userId' );
		$this->createIndex( 'idx_' . $this->prefix . 'site_access_role', $this->prefix . 'core_site_access', 'roleId' );
	}

	private function upOtp() {

		$this->createTable( $this->prefix . 'core_otp', [
			'id' => $this->bigPrimaryKey( 20 ),
			'userId' => $this->bigInteger( 20 ),
			'email' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'mobile' => $this->string( Yii::$app->core->smallText )->defaultValue( null ),
			'ip' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'ipNum' => $this->integer(11)->defaultValue( 0 ),
			'agent' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'otp' => $this->integer( 10 )->defaultValue( null ),
			'otpValidTill' => $this->dateTime()->defaultValue( null ),
			'sent' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns avatar, banner and theme
		$this->createIndex( 'idx_' . $this->prefix . 'otp_user', $this->prefix . 'core_otp', 'userId' );
	}

	private function upFile() {

		$this->createTable( $this->prefix . 'core_file', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 )->notNull(),
			'userId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 ),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'code' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'title' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'extension' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'directory' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'size' => $this->float()->notNull()->defaultValue( 0 ),
			'visibility' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'storage' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'url' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'medium' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'small' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'thumb' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'placeholder' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'smallPlaceholder' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'ogg' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'webm' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'caption' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'altText' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'link' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'shared' => $this->boolean()->notNull()->defaultValue( false ), // Shared File
			'srcset' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'sizes' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'file_site', $this->prefix . 'core_file', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'file_user', $this->prefix . 'core_file', 'userId' );
		$this->createIndex( 'idx_' . $this->prefix . 'file_creator', $this->prefix . 'core_file', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'file_modifier', $this->prefix . 'core_file', 'modifiedBy' );
	}

	private function upGallery() {

		$this->createTable( $this->prefix . 'core_gallery', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 )->notNull(),
			'templateId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'title' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'code' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'status' => $this->smallInteger( 6 ),
			'visibility' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'pinned' => $this->boolean()->notNull()->defaultValue( false ),
			'featured' => $this->boolean()->notNull()->defaultValue( false ),
			'popular' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns site, template, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'gallery_site', $this->prefix . 'core_gallery', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'gallery_template', $this->prefix . 'core_gallery', 'templateId' );
		$this->createIndex( 'idx_' . $this->prefix . 'gallery_creator', $this->prefix . 'core_gallery', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'gallery_modifier', $this->prefix . 'core_gallery', 'modifiedBy' );
	}

	private function upForm() {

		$this->createTable( $this->prefix . 'core_form', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 )->notNull(),
			'userId' => $this->bigInteger( 20 ),
			'templateId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'texture' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'title' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'success' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'failure' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'captcha' => $this->boolean()->notNull()->defaultValue( false ),
			'visibility' => $this->smallInteger( 6 ),
			'status' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'sender' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ), // Mail sender email
			'replyTo' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ), // Reply to in case sender is different
			'mailTo' => $this->text()->defaultValue( null ), // Mailing list in CSV format
			'ccTo' => $this->text()->defaultValue( null ), // CC list in CSV format
			'bccTo' => $this->text()->defaultValue( null ), // BCC list in CSV format
			'userMail' => $this->boolean()->notNull()->defaultValue( false ), // Trigger mail to logged in user
			'adminMail' => $this->boolean()->notNull()->defaultValue( false ), // Trigger mail to admin
			'uniqueSubmit' => $this->boolean()->notNull()->defaultValue( false ),
			'updateSubmit' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'htmlOptions' => $this->text(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'form_site', $this->prefix . 'core_form', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'form_user', $this->prefix . 'core_form', 'userId' );
		$this->createIndex( 'idx_' . $this->prefix . 'form_template', $this->prefix . 'core_form', 'templateId' );
		$this->createIndex( 'idx_' . $this->prefix . 'form_creator', $this->prefix . 'core_form', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'form_modifier', $this->prefix . 'core_form', 'modifiedBy' );
	}

	private function upFormField() {

		$this->createTable( $this->prefix . 'core_form_field', [
			'id' => $this->bigPrimaryKey( 20 ),
			'formId' => $this->bigInteger( 20 )->notNull(),
			'optionGroupId' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'label' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'group' => $this->string( Yii::$app->core->smallText )->defaultValue( null ),
			'compress' => $this->boolean()->notNull()->defaultValue( false ),
			'meta' => $this->boolean()->defaultValue( true ),
			'active' => $this->boolean()->notNull()->defaultValue( true ),
			'validators' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( false ),
			'order' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'htmlOptions' => $this->text(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText()
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'form_field_parent', $this->prefix . 'core_form_field', 'formId' );
		$this->createIndex( 'idx_' . $this->prefix . 'form_field_ogroup', $this->prefix . 'core_form_field', 'optionGroupId' );
	}

	private function upTag() {

		$this->createTable( $this->prefix . 'core_tag', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 )->notNull(),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'texture' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'title' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xtraLargeText )->defaultValue( null ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'htmlOptions' => $this->text(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText()
		], $this->options );

		// Index for columns site
		$this->createIndex( 'idx_' . $this->prefix . 'tag_site', $this->prefix . 'core_tag', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'tag_creator', $this->prefix . 'core_tag', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'tag_modifier', $this->prefix . 'core_tag', 'modifiedBy' );
	}

	private function upCategory() {

		$this->createTable( $this->prefix . 'core_category', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 )->notNull(),
			'parentId' => $this->bigInteger( 20 ),
			'rootId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'texture' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'title' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'lValue' => $this->smallInteger( 6 ),
			'rValue' => $this->smallInteger( 6 ),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'pinned' => $this->boolean()->notNull()->defaultValue( false ),
			'featured' => $this->boolean()->notNull()->defaultValue( false ),
			'popular' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'htmlOptions' => $this->text(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText()
		], $this->options );

		// Index for columns site
		$this->createIndex( 'idx_' . $this->prefix . 'category_site', $this->prefix . 'core_category', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'category_parent', $this->prefix . 'core_category', 'parentId' );
		$this->createIndex( 'idx_' . $this->prefix . 'category_root', $this->prefix . 'core_category', 'rootId' );
		$this->createIndex( 'idx_' . $this->prefix . 'category_creator', $this->prefix . 'core_category', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'category_modifier', $this->prefix . 'core_category', 'modifiedBy' );
	}

	private function upOption() {

		$this->createTable( $this->prefix . 'core_option', [
			'id' => $this->bigPrimaryKey( 20 ),
			'categoryId' => $this->bigInteger( 20 )->notNull(),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'value' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'icon' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'active' => $this->boolean()->defaultValue( false ),
			'input' => $this->boolean()->defaultValue( false ),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'htmlOptions' => $this->text(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText()
		], $this->options );

		// Index for columns site
		$this->createIndex( 'idx_' . $this->prefix . 'option_category', $this->prefix . 'core_option', 'categoryId' );
	}

	private function upDependency() {

		$this->createTable( $this->prefix . 'core_dependency', [
			'sourceId' => $this->bigInteger( 20 )->notNull(),
			'sourceType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'targetId' => $this->bigInteger( 20 )->notNull(),
			'targetType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'active' => $this->boolean()->notNull()->defaultValue( true ),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'PRIMARY KEY( sourceId, sourceType, targetId, targetType )'
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'dependency_sid', $this->prefix . 'core_dependency', 'sourceId' );
		$this->createIndex( 'idx_' . $this->prefix . 'dependency_stype', $this->prefix . 'core_dependency', 'sourceType' );
		$this->createIndex( 'idx_' . $this->prefix . 'dependency_tid', $this->prefix . 'core_dependency', 'targetId' );
		$this->createIndex( 'idx_' . $this->prefix . 'dependency_ttype', $this->prefix . 'core_dependency', 'targetType' );
	}

	private function upModelHierarchy() {

		$this->createTable( $this->prefix . 'core_model_hierarchy', [
			'id' => $this->bigPrimaryKey( 20 ),
			'parentId' => $this->bigInteger( 20 ),
			'childId' => $this->bigInteger( 20 ),
			'rootId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'lValue' => $this->smallInteger( 6 ),
			'rValue' => $this->smallInteger( 6 )
		], $this->options );
	}

	private function upLocaleMessage() {

		$this->createTable( $this->prefix . 'core_locale_message', [
			'id' => $this->bigPrimaryKey( 20 ),
			'localeId' => $this->bigInteger( 20 )->notNull(),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'module' => $this->string( Yii::$app->core->mediumText ),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'value' => $this->mediumText()
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'locale_message_locale', $this->prefix . 'core_locale_message', 'localeId' );
	}

	private function upModelMessage() {

		$this->createTable( $this->prefix . 'core_model_message', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 )->notNull(),
			'baseId' => $this->bigInteger( 20 ),
			'bannerId' => $this->bigInteger( 20 ),
			'videoId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 ),
			'modifiedBy' => $this->bigInteger( 20 ),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'title' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'ip' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'ipNum' => $this->integer( 11 )->defaultValue( 0 ),
			'agent' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'consumed' => $this->boolean()->notNull()->defaultValue( false ),
			'trash' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns base, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'model_message_site', $this->prefix . 'core_model_message', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_message_base', $this->prefix . 'core_model_message', 'baseId' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_message_banner', $this->prefix . 'core_model_message', 'bannerId' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_message_video', $this->prefix . 'core_model_message', 'videoId' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_message_creator', $this->prefix . 'core_model_message', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_message_modifier', $this->prefix . 'core_model_message', 'modifiedBy' );
	}

	private function upModelComment() {

		$this->createTable( $this->prefix . 'core_model_comment', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 )->notNull(),
			'baseId' => $this->bigInteger( 20 ),
			'bannerId' => $this->bigInteger( 20 ),
			'videoId' => $this->bigInteger( 20 ),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'createdBy' => $this->bigInteger( 20 ),
			'modifiedBy' => $this->bigInteger( 20 ),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'title' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'name' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'email' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'mobile' => $this->string( Yii::$app->core->smallText )->defaultValue( null ),
			'phone' => $this->string( Yii::$app->core->smallText )->defaultValue( null ),
			'avatarUrl' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'websiteUrl' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'ip' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'ipNum' => $this->integer(11)->defaultValue( 0 ),
			'agent' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'status' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'rate1' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'rate2' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'rate3' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'rate4' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'rate5' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'rating' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'pinned' => $this->boolean()->notNull()->defaultValue( false ),
			'featured' => $this->boolean()->notNull()->defaultValue( false ),
			'popular' => $this->boolean()->notNull()->defaultValue( false ),
			'anonymous' => $this->boolean()->notNull()->defaultValue( false ),
			'field1' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'field2' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'field3' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'field4' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'field5' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'approvedAt' => $this->dateTime(),
			'content' => $this->mediumText(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns base, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'model_comment_site', $this->prefix . 'core_model_comment', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_comment_base', $this->prefix . 'core_model_comment', 'baseId' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_comment_banner', $this->prefix . 'core_model_comment', 'bannerId' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_comment_video', $this->prefix . 'core_model_comment', 'videoId' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_comment_creator', $this->prefix . 'core_model_comment', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_comment_modifier', $this->prefix . 'core_model_comment', 'modifiedBy' );
	}

	private function upModelAnalytics() {

		$this->createTable( $this->prefix . 'core_model_analytics', [
			'id' => $this->bigPrimaryKey( 20 ),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'views' => $this->integer( 11 )->defaultValue( 0 ),
			'referrals' => $this->integer( 11 )->defaultValue( 0 ),
			'comments' => $this->integer( 11 )->defaultValue( 0 ),
			'reviews' => $this->integer( 11 )->defaultValue( 0 ),
			'ratings' => $this->float(),
			'likes' => $this->integer( 11 )->defaultValue( 0 ),
			'wish' => $this->integer( 11 )->defaultValue( 0 ),
			'followers' => $this->integer( 11 )->defaultValue( 0 ),
			'rank' => $this->integer( 11 )->defaultValue( 0 ),
			'weight' => $this->float(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns base, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'model_analytics_parent', $this->prefix . 'core_model_analytics', 'parentId' );
	}

	private function upModelHistory() {

		$this->createTable( $this->prefix . 'core_model_history', [
			'id' => $this->bigPrimaryKey( 20 ),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'title' => $this->string( Yii::$app->core->xxxLargeText )->defaultValue( null ),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'ip' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'ipNum' => $this->integer(11)->defaultValue( 0 ),
			'agent' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'createdAt' => $this->dateTime()->notNull(),
			'data' => $this->mediumText(),
			'gridCache' => $this->longText(),
			'gridCacheValid' => $this->boolean()->notNull()->defaultValue( false ),
			'gridCachedAt' => $this->dateTime()
		], $this->options );

		// Index for columns base, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'model_history_parent', $this->prefix . 'core_model_history', 'parentId' );
	}

	private function upModelMeta() {

		$this->createTable( $this->prefix . 'core_model_meta', [
			'id' => $this->bigPrimaryKey( 20 ),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'label' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'active' => $this->boolean()->notNull()->defaultValue( false ),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'valueType' => $this->string( Yii::$app->core->mediumText )->notNull()->defaultValue( Meta::VALUE_TYPE_TEXT ),
			'value' => $this->mediumText(),
			'data' => $this->mediumText()
		], $this->options );

		// Index for columns base, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'model_meta_parent', $this->prefix . 'core_model_meta', 'parentId' );
	}

	private function upModelObject() {

		$this->createTable( $this->prefix . 'core_model_object', [
			'id' => $this->bigPrimaryKey( 20 ),
			'modelId' => $this->bigInteger( 20 )->notNull(),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'key' => $this->string( Yii::$app->core->mediumText ),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'active' => $this->boolean()->notNull()->defaultValue( true ),
			'pinned' => $this->boolean()->notNull()->defaultValue( false ),
			'featured' => $this->boolean()->notNull()->defaultValue( false ),
			'popular' => $this->boolean()->notNull()->defaultValue( false ),
			'nodes' => $this->text()
		], $this->options );

		// Index for columns user
		$this->createIndex( 'idx_' . $this->prefix . 'model_object_parent', $this->prefix . 'core_model_object', 'modelId' );
	}

	private function upModelAddress() {

		$this->createTable( $this->prefix . 'core_model_address', [
			'id' => $this->bigPrimaryKey( 20 ),
			'modelId' => $this->bigInteger( 20 )->notNull(),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'key' => $this->string( Yii::$app->core->mediumText ),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'active' => $this->boolean()->notNull()->defaultValue( true )
		], $this->options );

		// Index for columns user
		$this->createIndex( 'idx_' . $this->prefix . 'model_address_parent', $this->prefix . 'core_model_address', 'modelId' );
	}

	private function upModelLocation() {

		$this->createTable( $this->prefix . 'core_model_location', [
			'id' => $this->bigPrimaryKey( 20 ),
			'modelId' => $this->bigInteger( 20 )->notNull(),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'key' => $this->string( Yii::$app->core->mediumText ),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'active' => $this->boolean()->notNull()->defaultValue( true )
		], $this->options );

		// Index for columns user
		$this->createIndex( 'idx_' . $this->prefix . 'model_location_parent', $this->prefix . 'core_model_location', 'modelId' );
	}

	private function upModelFile() {

		$this->createTable( $this->prefix . 'core_model_file', [
			'id' => $this->bigPrimaryKey( 20 ),
			'modelId' => $this->bigInteger( 20 )->notNull(),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText ),
			'key' => $this->string( Yii::$app->core->mediumText ),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'active' => $this->boolean()->notNull()->defaultValue( true ),
			'pinned' => $this->boolean()->notNull()->defaultValue( false ),
			'featured' => $this->boolean()->notNull()->defaultValue( false ),
			'popular' => $this->boolean()->notNull()->defaultValue( false )
		], $this->options );

		// Index for columns user
		$this->createIndex( 'idx_' . $this->prefix . 'model_file_parent', $this->prefix . 'core_model_file', 'modelId' );
	}

	private function upModelGallery() {

		$this->createTable( $this->prefix . 'core_model_gallery', [
			'id' => $this->bigPrimaryKey( 20 ),
			'modelId' => $this->bigInteger( 20 )->notNull(),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText ),
			'key' => $this->string( Yii::$app->core->mediumText ),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'active' => $this->boolean()->notNull()->defaultValue( true ),
			'pinned' => $this->boolean()->notNull()->defaultValue( false ),
			'featured' => $this->boolean()->notNull()->defaultValue( false ),
			'popular' => $this->boolean()->notNull()->defaultValue( false )
		], $this->options );

		// Index for columns user
		$this->createIndex( 'idx_' . $this->prefix . 'model_gallery_parent', $this->prefix . 'core_model_gallery', 'modelId' );
	}

	private function upModelTag() {

		$this->createTable( $this->prefix . 'core_model_tag', [
			'id' => $this->bigPrimaryKey( 20 ),
			'modelId' => $this->bigInteger( 20 )->notNull(),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText ),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'active' => $this->boolean()->notNull()->defaultValue( true )
		], $this->options );

		// Index for columns user
		$this->createIndex( 'idx_' . $this->prefix . 'model_tag_parent', $this->prefix . 'core_model_tag', 'modelId' );
	}

	private function upModelCategory() {

		$this->createTable( $this->prefix . 'core_model_category', [
			'id' => $this->bigPrimaryKey( 20 ),
			'modelId' => $this->bigInteger( 20 )->notNull(),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText ),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'active' => $this->boolean()->notNull()->defaultValue( true ),
			'nodes' => $this->text()
		], $this->options );

		// Index for columns user
		$this->createIndex( 'idx_' . $this->prefix . 'model_category_parent', $this->prefix . 'core_model_category', 'modelId' );
	}

	private function upModelOption() {

		$this->createTable( $this->prefix . 'core_model_option', [
			'id' => $this->bigPrimaryKey( 20 ),
			'modelId' => $this->bigInteger( 20 )->notNull(),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText ),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'active' => $this->boolean()->notNull()->defaultValue( true ),
			'value' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null )
		], $this->options );

		// Index for columns user
		$this->createIndex( 'idx_' . $this->prefix . 'model_option_parent', $this->prefix . 'core_model_option', 'modelId' );
	}

	private function upModelForm() {

		$this->createTable( $this->prefix . 'core_model_form', [
			'id' => $this->bigPrimaryKey( 20 ),
			'modelId' => $this->bigInteger( 20 )->notNull(),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText ),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'active' => $this->boolean()->notNull()->defaultValue( true )
		], $this->options );

		// Index for columns user
		$this->createIndex( 'idx_' . $this->prefix . 'model_form_parent', $this->prefix . 'core_model_form', 'modelId' );
	}

	private function upModelFollower() {

		$this->createTable( $this->prefix . 'core_model_follower', [
			'id' => $this->bigPrimaryKey( 20 ),
			'modelId' => $this->bigInteger( 20 )->notNull(),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText ),
			'order' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'active' => $this->boolean()->notNull()->defaultValue( true ),
			'pinned' => $this->boolean()->notNull()->defaultValue( false ),
			'featured' => $this->boolean()->notNull()->defaultValue( false ),
			'popular' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'data' => $this->mediumText()
		], $this->options );

		// Index for columns user
		$this->createIndex( 'idx_' . $this->prefix . 'model_follower_parent', $this->prefix . 'core_model_follower', 'modelId' );
	}

	private function generateForeignKeys() {

		// Theme
		$this->addForeignKey( 'fk_' . $this->prefix . 'theme_creator', $this->prefix . 'core_theme', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'theme_modifier', $this->prefix . 'core_theme', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Template
		$this->addForeignKey( 'fk_' . $this->prefix . 'template_site', $this->prefix . 'core_template', 'siteId', $this->prefix . 'core_site', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'template_theme', $this->prefix . 'core_template', 'themeId', $this->prefix . 'core_theme', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'template_preview', $this->prefix . 'core_template', 'previewId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'template_creator', $this->prefix . 'core_template', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'template_modifier', $this->prefix . 'core_template', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Object
		$this->addForeignKey( 'fk_' . $this->prefix . 'object_site', $this->prefix . 'core_object', 'siteId', $this->prefix . 'core_site', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'object_theme', $this->prefix . 'core_object', 'themeId', $this->prefix . 'core_theme', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'object_template', $this->prefix . 'core_object', 'templateId', $this->prefix . 'core_template', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'object_user', $this->prefix . 'core_object', 'userId', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'object_parent', $this->prefix . 'core_object', 'parentId', $this->prefix . 'core_object', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'object_avatar', $this->prefix . 'core_object', 'avatarId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'object_banner', $this->prefix . 'core_object', 'bannerId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'object_video', $this->prefix . 'core_object', 'videoId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'object_gallery', $this->prefix . 'core_object', 'galleryId', $this->prefix . 'core_gallery', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'object_creator', $this->prefix . 'core_object', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'object_modifier', $this->prefix . 'core_object', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Object Meta
		$this->addForeignKey( 'fk_' . $this->prefix . 'object_meta_parent', $this->prefix . 'core_object_meta', 'modelId', $this->prefix . 'core_object', 'id', 'CASCADE' );

		// Province
		$this->addForeignKey( 'fk_' . $this->prefix . 'province_country', $this->prefix . 'core_province', 'countryId', $this->prefix . 'core_country', 'id', 'RESTRICT' );

		// Region
		$this->addForeignKey( 'fk_' . $this->prefix . 'region_country', $this->prefix . 'core_region', 'countryId', $this->prefix . 'core_country', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'region_province', $this->prefix . 'core_region', 'provinceId', $this->prefix . 'core_province', 'id', 'RESTRICT' );

		// City
		$this->addForeignKey( 'fk_' . $this->prefix . 'city_country', $this->prefix . 'core_city', 'countryId', $this->prefix . 'core_country', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'city_province', $this->prefix . 'core_city', 'provinceId', $this->prefix . 'core_province', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'city_region', $this->prefix . 'core_city', 'regionId', $this->prefix . 'core_region', 'id', 'RESTRICT' );

		// Address
		$this->addForeignKey( 'fk_' . $this->prefix . 'address_country', $this->prefix . 'core_address', 'countryId', $this->prefix . 'core_country', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'address_province', $this->prefix . 'core_address', 'provinceId', $this->prefix . 'core_province', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'address_region', $this->prefix . 'core_address', 'regionId', $this->prefix . 'core_region', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'address_city', $this->prefix . 'core_address', 'cityId', $this->prefix . 'core_city', 'id', 'CASCADE' );

		// Location
		$this->addForeignKey( 'fk_' . $this->prefix . 'location_country', $this->prefix . 'core_location', 'countryId', $this->prefix . 'core_country', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'location_province', $this->prefix . 'core_location', 'provinceId', $this->prefix . 'core_province', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'location_region', $this->prefix . 'core_location', 'regionId', $this->prefix . 'core_region', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'location_city', $this->prefix . 'core_location', 'cityId', $this->prefix . 'core_city', 'id', 'SET NULL' );

		// Role
		$this->addForeignKey( 'fk_' . $this->prefix . 'role_creator', $this->prefix . 'core_role', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'role_modifier', $this->prefix . 'core_role', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Permission
		$this->addForeignKey( 'fk_' . $this->prefix . 'permission_creator', $this->prefix . 'core_permission', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'permission_modifier', $this->prefix . 'core_permission', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Role Permission
		$this->addForeignKey( 'fk_' . $this->prefix . 'role_permission_role', $this->prefix . 'core_role_permission', 'roleId', $this->prefix . 'core_role', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'role_permission_perm', $this->prefix . 'core_role_permission', 'permissionId', $this->prefix . 'core_permission', 'id', 'CASCADE' );

		// User
		$this->addForeignKey( 'fk_' . $this->prefix . 'user_locale', $this->prefix . 'core_user', 'localeId', $this->prefix . 'core_locale', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'user_gender', $this->prefix . 'core_user', 'genderId', $this->prefix . 'core_option', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'user_marital', $this->prefix . 'core_user', 'maritalId', $this->prefix . 'core_option', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'user_avatar', $this->prefix . 'core_user', 'avatarId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'user_banner', $this->prefix . 'core_user', 'bannerId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'user_video', $this->prefix . 'core_user', 'videoId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'user_gallery', $this->prefix . 'core_user', 'galleryId', $this->prefix . 'core_gallery', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'user_template', $this->prefix . 'core_user', 'templateId', $this->prefix . 'core_template', 'id', 'SET NULL' );

		// User Meta
		$this->addForeignKey( 'fk_' . $this->prefix . 'user_meta_parent', $this->prefix . 'core_user_meta', 'modelId', $this->prefix . 'core_user', 'id', 'CASCADE' );

		// Site
		$this->addForeignKey( 'fk_' . $this->prefix . 'site_creator', $this->prefix . 'core_site', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'site_modifier', $this->prefix . 'core_site', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'site_avatar', $this->prefix . 'core_site', 'avatarId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'site_banner', $this->prefix . 'core_site', 'bannerId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'site_video', $this->prefix . 'core_site', 'videoId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'site_theme', $this->prefix . 'core_site', 'themeId', $this->prefix . 'core_theme', 'id', 'SET NULL' );

		// Site Meta
		$this->addForeignKey( 'fk_' . $this->prefix . 'site_meta_parent', $this->prefix . 'core_site_meta', 'modelId', $this->prefix . 'core_site', 'id', 'CASCADE' );

		// Site Member
		$this->addForeignKey( 'fk_' . $this->prefix . 'site_member_site', $this->prefix . 'core_site_member', 'siteId', $this->prefix . 'core_site', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'site_member_user', $this->prefix . 'core_site_member', 'userId', $this->prefix . 'core_user', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'site_member_role', $this->prefix . 'core_site_member', 'roleId', $this->prefix . 'core_role', 'id', 'CASCADE' );

		// Site Access
		$this->addForeignKey( 'fk_' . $this->prefix . 'site_access_site', $this->prefix . 'core_site_access', 'siteId', $this->prefix . 'core_site', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'site_access_user', $this->prefix . 'core_site_access', 'userId', $this->prefix . 'core_user', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'site_access_role', $this->prefix . 'core_site_access', 'roleId', $this->prefix . 'core_role', 'id', 'RESTRICT' );

		// OTP
		$this->addForeignKey( 'fk_' . $this->prefix . 'otp_user', $this->prefix . 'core_otp', 'userId', $this->prefix . 'core_user', 'id', 'CASCADE' );

		// File
		$this->addForeignKey( 'fk_' . $this->prefix . 'file_site', $this->prefix . 'core_file', 'siteId', $this->prefix . 'core_site', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'file_user', $this->prefix . 'core_file', 'userId', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'file_creator', $this->prefix . 'core_file', 'createdBy', $this->prefix . 'core_user', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'file_modifier', $this->prefix . 'core_file', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Gallery
		$this->addForeignKey( 'fk_' . $this->prefix . 'gallery_site', $this->prefix . 'core_gallery', 'siteId', $this->prefix . 'core_site', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'gallery_template', $this->prefix . 'core_gallery', 'templateId', $this->prefix . 'core_template', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'gallery_creator', $this->prefix . 'core_gallery', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'gallery_modifier', $this->prefix . 'core_gallery', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Form
		$this->addForeignKey( 'fk_' . $this->prefix . 'form_site', $this->prefix . 'core_form', 'siteId', $this->prefix . 'core_site', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'form_user', $this->prefix . 'core_form', 'userId', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'form_template', $this->prefix . 'core_form', 'templateId', $this->prefix . 'core_template', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'form_creator', $this->prefix . 'core_form', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'form_modifier', $this->prefix . 'core_form', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Form Field
		$this->addForeignKey( 'fk_' . $this->prefix . 'form_field_parent', $this->prefix . 'core_form_field', 'formId', $this->prefix . 'core_form', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'form_field_ogroup', $this->prefix . 'core_form_field', 'optionGroupId', $this->prefix . 'core_category', 'id', 'SET NULL' );

		// Tag
		$this->addForeignKey( 'fk_' . $this->prefix . 'tag_site', $this->prefix . 'core_tag', 'siteId', $this->prefix . 'core_site', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'tag_creator', $this->prefix . 'core_tag', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'tag_modifier', $this->prefix . 'core_tag', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Category
		$this->addForeignKey( 'fk_' . $this->prefix . 'category_site', $this->prefix . 'core_category', 'siteId', $this->prefix . 'core_site', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'category_parent', $this->prefix . 'core_category', 'parentId', $this->prefix . 'core_category', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'category_creator', $this->prefix . 'core_category', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'category_modifier', $this->prefix . 'core_category', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Option
		$this->addForeignKey( 'fk_' . $this->prefix . 'option_category', $this->prefix . 'core_option', 'categoryId', $this->prefix . 'core_category', 'id', 'RESTRICT' );

		// Locale Message
		$this->addForeignKey( 'fk_' . $this->prefix . 'locale_message_locale', $this->prefix . 'core_locale_message', 'localeId', $this->prefix . 'core_locale', 'id', 'CASCADE' );

		// Model Message
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_message_site', $this->prefix . 'core_model_message', 'siteId', $this->prefix . 'core_site', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_message_base', $this->prefix . 'core_model_message', 'baseId', $this->prefix . 'core_model_message', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_message_banner', $this->prefix . 'core_model_message', 'bannerId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_message_video', $this->prefix . 'core_model_message', 'videoId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_message_creator', $this->prefix . 'core_model_message', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_message_modifier', $this->prefix . 'core_model_message', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Model Comment
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_comment_site', $this->prefix . 'core_model_comment', 'siteId', $this->prefix . 'core_site', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_comment_base', $this->prefix . 'core_model_comment', 'baseId', $this->prefix . 'core_model_comment', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_comment_banner', $this->prefix . 'core_model_comment', 'bannerId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_comment_video', $this->prefix . 'core_model_comment', 'videoId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_comment_creator', $this->prefix . 'core_model_comment', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_comment_modifier', $this->prefix . 'core_model_comment', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Model Object
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_object_parent', $this->prefix . 'core_model_object', 'modelId', $this->prefix . 'core_object', 'id', 'CASCADE' );

		// Model Address
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_address_parent', $this->prefix . 'core_model_address', 'modelId', $this->prefix . 'core_address', 'id', 'CASCADE' );

		// Model Location
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_location_parent', $this->prefix . 'core_model_location', 'modelId', $this->prefix . 'core_location', 'id', 'CASCADE' );

		// Model File
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_file_parent', $this->prefix . 'core_model_file', 'modelId', $this->prefix . 'core_file', 'id', 'CASCADE' );

		// Model Gallery
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_gallery_parent', $this->prefix . 'core_model_gallery', 'modelId', $this->prefix . 'core_gallery', 'id', 'CASCADE' );

		// Model Tag
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_tag_parent', $this->prefix . 'core_model_tag', 'modelId', $this->prefix . 'core_tag', 'id', 'CASCADE' );

		// Model Category
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_category_parent', $this->prefix . 'core_model_category', 'modelId', $this->prefix . 'core_category', 'id', 'CASCADE' );

		// Model Option
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_option_parent', $this->prefix . 'core_model_option', 'modelId', $this->prefix . 'core_option', 'id', 'CASCADE' );

		// Model Form
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_form_parent', $this->prefix . 'core_model_form', 'modelId', $this->prefix . 'core_form', 'id', 'CASCADE' );

		// Model Follower
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_follower_parent', $this->prefix . 'core_model_follower', 'modelId', $this->prefix . 'core_user', 'id', 'CASCADE' );
	}

	public function down() {

		if( $this->fk ) {

			$this->dropForeignKeys();
		}

		$this->dropTable( $this->prefix . 'core_locale' );

		$this->dropTable( $this->prefix . 'core_theme' );
		$this->dropTable( $this->prefix . 'core_template' );
		$this->dropTable( $this->prefix . 'core_object' );
		$this->dropTable( $this->prefix . 'core_object_meta' );

		$this->dropTable( $this->prefix . 'core_location' );
		$this->dropTable( $this->prefix . 'core_address' );
		$this->dropTable( $this->prefix . 'core_city' );
		$this->dropTable( $this->prefix . 'core_region' );
		$this->dropTable( $this->prefix . 'core_province' );
		$this->dropTable( $this->prefix . 'core_country' );

		$this->dropTable( $this->prefix . 'core_role' );
		$this->dropTable( $this->prefix . 'core_permission' );
		$this->dropTable( $this->prefix . 'core_role_permission' );

		$this->dropTable( $this->prefix . 'core_user' );
		$this->dropTable( $this->prefix . 'core_user_meta' );
		$this->dropTable( $this->prefix . 'core_site' );
		$this->dropTable( $this->prefix . 'core_site_meta' );
		$this->dropTable( $this->prefix . 'core_site_member' );
		$this->dropTable( $this->prefix . 'core_site_access' );
		$this->dropTable( $this->prefix . 'core_otp' );

		$this->dropTable( $this->prefix . 'core_file' );
		$this->dropTable( $this->prefix . 'core_gallery' );

		$this->dropTable( $this->prefix . 'core_form' );
		$this->dropTable( $this->prefix . 'core_form_field' );

		$this->dropTable( $this->prefix . 'core_tag' );
		$this->dropTable( $this->prefix . 'core_category' );
		$this->dropTable( $this->prefix . 'core_option' );

		$this->dropTable( $this->prefix . 'core_dependency' );

		$this->dropTable( $this->prefix . 'core_model_hierarchy' );
		$this->dropTable( $this->prefix . 'core_locale_message' );
		$this->dropTable( $this->prefix . 'core_model_message' );
		$this->dropTable( $this->prefix . 'core_model_comment' );
		$this->dropTable( $this->prefix . 'core_model_analytics' );
		$this->dropTable( $this->prefix . 'core_model_history' );
		$this->dropTable( $this->prefix . 'core_model_meta' );

		$this->dropTable( $this->prefix . 'core_model_object' );
		$this->dropTable( $this->prefix . 'core_model_address' );
		$this->dropTable( $this->prefix . 'core_model_location' );
		$this->dropTable( $this->prefix . 'core_model_file' );
		$this->dropTable( $this->prefix . 'core_model_gallery' );
		$this->dropTable( $this->prefix . 'core_model_tag' );
		$this->dropTable( $this->prefix . 'core_model_category' );
		$this->dropTable( $this->prefix . 'core_model_option' );
		$this->dropTable( $this->prefix . 'core_model_form' );
		$this->dropTable( $this->prefix . 'core_model_follower' );
	}

	private function dropForeignKeys() {

		// Theme
		$this->dropForeignKey( 'fk_' . $this->prefix . 'theme_creator', $this->prefix . 'core_theme' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'theme_modifier', $this->prefix . 'core_theme' );

		// Template
		$this->dropForeignKey( 'fk_' . $this->prefix . 'template_site', $this->prefix . 'core_template' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'template_theme', $this->prefix . 'core_template' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'template_preview', $this->prefix . 'core_template' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'template_creator', $this->prefix . 'core_template' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'template_modifier', $this->prefix . 'core_template' );

		// Object
		$this->dropForeignKey( 'fk_' . $this->prefix . 'object_site', $this->prefix . 'core_object' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'object_theme', $this->prefix . 'core_object' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'object_template', $this->prefix . 'core_object' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'object_user', $this->prefix . 'core_object' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'object_parent', $this->prefix . 'core_object' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'object_avatar', $this->prefix . 'core_object' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'object_banner', $this->prefix . 'core_object' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'object_video', $this->prefix . 'core_object' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'object_gallery', $this->prefix . 'core_object' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'object_creator', $this->prefix . 'core_object' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'object_modifier', $this->prefix . 'core_object' );

		// Object Meta
		$this->dropForeignKey( 'fk_' . $this->prefix . 'object_meta_parent', $this->prefix . 'core_object_meta' );

		// Province
		$this->dropForeignKey( 'fk_' . $this->prefix . 'province_country', $this->prefix . 'core_province' );

		$this->dropForeignKey( 'fk_' . $this->prefix . 'region_country', $this->prefix . 'core_region' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'region_province', $this->prefix . 'core_region' );

		// City
		$this->dropForeignKey( 'fk_' . $this->prefix . 'city_country', $this->prefix . 'core_city' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'city_province', $this->prefix . 'core_city' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'city_region', $this->prefix . 'core_city' );

		// Address
		$this->dropForeignKey( 'fk_' . $this->prefix . 'address_country', $this->prefix . 'core_address' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'address_province', $this->prefix . 'core_address' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'address_city', $this->prefix . 'core_address' );

		// Location
		$this->dropForeignKey( 'fk_' . $this->prefix . 'location_country', $this->prefix . 'core_location' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'location_province', $this->prefix . 'core_location' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'location_region', $this->prefix . 'core_location' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'location_city', $this->prefix . 'core_location' );

		// Role
		$this->dropForeignKey( 'fk_' . $this->prefix . 'role_creator', $this->prefix . 'core_role' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'role_modifier', $this->prefix . 'core_role' );

		// Permission
		$this->dropForeignKey( 'fk_' . $this->prefix . 'permission_creator', $this->prefix . 'core_permission' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'permission_modifier', $this->prefix . 'core_permission' );

		// Role Permission
		$this->dropForeignKey( 'fk_' . $this->prefix . 'role_permission_role', $this->prefix . 'core_role_permission' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'role_permission_perm', $this->prefix . 'core_role_permission' );

		// User
		$this->dropForeignKey( 'fk_' . $this->prefix . 'user_locale', $this->prefix . 'core_user' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'user_gender', $this->prefix . 'core_user' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'user_marital', $this->prefix . 'core_user' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'user_avatar', $this->prefix . 'core_user' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'user_banner', $this->prefix . 'core_user' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'user_video', $this->prefix . 'core_user' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'user_gallery', $this->prefix . 'core_user' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'user_template', $this->prefix . 'core_user' );

		// User Meta
		$this->dropForeignKey( 'fk_' . $this->prefix . 'user_meta_parent', $this->prefix . 'core_user_meta' );

		// Site
		$this->dropForeignKey( 'fk_' . $this->prefix . 'site_creator', $this->prefix . 'core_site' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'site_modifier', $this->prefix . 'core_site' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'site_avatar', $this->prefix . 'core_site' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'site_banner', $this->prefix . 'core_site' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'site_video', $this->prefix . 'core_site' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'site_theme', $this->prefix . 'core_site' );

		// Site Meta
		$this->dropForeignKey( 'fk_' . $this->prefix . 'site_meta_parent', $this->prefix . 'core_site_meta' );

		// Site Member
		$this->dropForeignKey( 'fk_' . $this->prefix . 'site_member_site', $this->prefix . 'core_site_member' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'site_member_user', $this->prefix . 'core_site_member' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'site_member_role', $this->prefix . 'core_site_member' );

		// OTP
		$this->dropForeignKey( 'fk_' . $this->prefix . 'otp_user', $this->prefix . 'core_otp' );

		// Site Access
		$this->dropForeignKey( 'fk_' . $this->prefix . 'site_access_site', $this->prefix . 'core_site_access' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'site_access_user', $this->prefix . 'core_site_access' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'site_access_role', $this->prefix . 'core_site_access' );

		// File
		$this->dropForeignKey( 'fk_' . $this->prefix . 'file_site', $this->prefix . 'core_file' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'file_user', $this->prefix . 'core_file' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'file_creator', $this->prefix . 'core_file' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'file_modifier', $this->prefix . 'core_file' );

		// Gallery
		$this->dropForeignKey( 'fk_' . $this->prefix . 'gallery_site', $this->prefix . 'core_gallery' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'gallery_template', $this->prefix . 'core_gallery' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'gallery_creator', $this->prefix . 'core_gallery' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'gallery_modifier', $this->prefix . 'core_gallery' );

		// Form
		$this->dropForeignKey( 'fk_' . $this->prefix . 'form_site', $this->prefix . 'core_form' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'form_user', $this->prefix . 'core_form' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'form_template', $this->prefix . 'core_form' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'form_creator', $this->prefix . 'core_form' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'form_modifier', $this->prefix . 'core_form' );

		// Form Field
		$this->dropForeignKey( 'fk_' . $this->prefix . 'form_field_parent', $this->prefix . 'core_form_field' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'form_field_ogroup', $this->prefix . 'core_form_field' );

		// Tag
		$this->dropForeignKey( 'fk_' . $this->prefix . 'tag_site', $this->prefix . 'core_tag' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'tag_creator', $this->prefix . 'core_tag' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'tag_modifier', $this->prefix . 'core_tag' );

		// Category
		$this->dropForeignKey( 'fk_' . $this->prefix . 'category_site', $this->prefix . 'core_category' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'category_parent', $this->prefix . 'core_category' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'category_creator', $this->prefix . 'core_category' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'category_modifier', $this->prefix . 'core_category' );

		// Option
		$this->dropForeignKey( 'fk_' . $this->prefix . 'option_category', $this->prefix . 'core_option' );

		// Locale Message
		$this->dropForeignKey( 'fk_' . $this->prefix . 'locale_message_locale', $this->prefix . 'core_locale_message' );

		// Model Message
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_message_site', $this->prefix . 'core_model_message' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_message_base', $this->prefix . 'core_model_message' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_message_banner', $this->prefix . 'core_model_message' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_message_video', $this->prefix . 'core_model_message' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_message_creator', $this->prefix . 'core_model_message' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_message_modifier', $this->prefix . 'core_model_message' );

		// Model Comment
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_comment_site', $this->prefix . 'core_model_comment' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_comment_base', $this->prefix . 'core_model_comment' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_comment_banner', $this->prefix . 'core_model_comment' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_comment_video', $this->prefix . 'core_model_comment' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_comment_creator', $this->prefix . 'core_model_comment' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_comment_modifier', $this->prefix . 'core_model_comment' );

		// Model Object
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_object_parent', $this->prefix . 'core_model_object' );

		// Model Address
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_address_parent', $this->prefix . 'core_model_address' );

		// Model Location
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_location_parent', $this->prefix . 'core_model_location' );

		// Model File
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_file_parent', $this->prefix . 'core_model_file' );

		// Model Gallery
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_gallery_parent', $this->prefix . 'core_model_gallery' );

		// Model Tag
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_tag_parent', $this->prefix . 'core_model_tag' );

		// Model Category
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_category_parent', $this->prefix . 'core_model_category' );

		// Model Option
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_option_parent', $this->prefix . 'core_model_option' );

		// Model Form
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_form_parent', $this->prefix . 'core_model_form' );

		// Model Follower
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_follower_parent', $this->prefix . 'core_model_follower' );
	}

}
