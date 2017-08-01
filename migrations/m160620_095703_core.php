<?php

class m160620_095703_core extends \yii\db\Migration {

	// Public Variables

	public $fk;
	public $options;

	// Private Variables

	private $prefix;

	public function init() {

		// Table prefix
		$this->prefix		= Yii::$app->migration->cmgPrefix;

		// Get the values via config
		$this->fk			= Yii::$app->migration->isFk();
		$this->options		= Yii::$app->migration->getTableOptions();

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

		// Location
		$this->upCountry();
		$this->upProvince();
		$this->upCity();
		$this->upAddress();

		// RBAC
		$this->upRole();
		$this->upPermission();
		$this->upRolePermission();

		// User
		$this->upUser();
		$this->upSite();
		$this->upSiteMeta();
		$this->upSiteMember();
		$this->upSiteAccess();

		// Files
		$this->upFile();
		$this->upGallery();

		// Grouping and group options
		$this->upTag();
		$this->upCategory();
		$this->upOption();

		// Forms
		$this->upForm();
		$this->upFormField();

		// Traits - Resources
		$this->upModelMessage();
		$this->upModelHierarchy();
		$this->upModelComment();
		$this->upModelMeta();

		// Traits - Mappers
		$this->upModelObject();
		$this->upModelAddress();
		$this->upModelFile();
		$this->upModelGallery();
		$this->upModelTag();
		$this->upModelCategory();
		$this->upModelOption();
		$this->upModelForm();

		if( $this->fk ) {

			$this->generateForeignKeys();
		}
	}

	private function upLocale() {

		$this->createTable( $this->prefix . 'core_locale', [
			'id' => $this->bigPrimaryKey( 20 ),
			'code' => $this->string( Yii::$app->core->smallText )->notNull(),
			'name' => $this->string( Yii::$app->core->mediumText )->notNull()
		], $this->options );
	}

	private function upTheme() {

		$this->createTable( $this->prefix . 'core_theme', [
			'id' => $this->bigPrimaryKey( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->largeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'description' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'default' => $this->boolean()->notNull()->defaultValue( false ),
			'renderer' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'basePath' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->text(),
			'data' => $this->text()
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'theme_creator', $this->prefix . 'core_theme', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'theme_modifier', $this->prefix . 'core_theme', 'modifiedBy' );
	}

	private function upTemplate() {

		$this->createTable( $this->prefix . 'core_template', [
			'id' => $this->bigPrimaryKey( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->largeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'active' => $this->boolean()->notNull()->defaultValue( false ),
			'renderer' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'fileRender' => $this->boolean()->notNull()->defaultValue( false ),
			'layout' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'layoutGroup' => $this->boolean()->notNull()->defaultValue( false ),
			'viewPath' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->text(),
			'data' => $this->text()
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'template_creator', $this->prefix . 'core_template', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'template_modifier', $this->prefix . 'core_template', 'modifiedBy' );
	}

	private function upObject() {

		$this->createTable( $this->prefix . 'core_object', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 )->notNull(),
			'themeId' => $this->bigInteger( 20 ),
			'templateId' => $this->bigInteger( 20 ),
			'avatarId' => $this->bigInteger( 20 ),
			'bannerId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'active' => $this->boolean()->notNull()->defaultValue( false ),
			'order' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'htmlOptions' => $this->text(),
			'content' => $this->text(),
			'data' => $this->text()
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'object_site', $this->prefix . 'core_object', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'object_theme', $this->prefix . 'core_object', 'themeId' );
		$this->createIndex( 'idx_' . $this->prefix . 'object_template', $this->prefix . 'core_object', 'templateId' );
		$this->createIndex( 'idx_' . $this->prefix . 'object_avatar', $this->prefix . 'core_object', 'avatarId' );
		$this->createIndex( 'idx_' . $this->prefix . 'object_banner', $this->prefix . 'core_object', 'bannerId' );
		$this->createIndex( 'idx_' . $this->prefix . 'object_creator', $this->prefix . 'core_object', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'object_modifier', $this->prefix . 'core_object', 'modifiedBy' );
	}

	private function upCountry() {

		$this->createTable( $this->prefix . 'core_country', [
			'id' => $this->bigPrimaryKey( 20 ),
			'code' => $this->string( Yii::$app->core->smallText )->notNull(),
			'iso' => $this->string( Yii::$app->core->smallText ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull()
		], $this->options );
	}

	private function upProvince() {

		$this->createTable( $this->prefix . 'core_province', [
			'id' => $this->bigPrimaryKey( 20 ),
			'countryId' => $this->bigInteger( 20 )->notNull(),
			'code' => $this->string( Yii::$app->core->smallText )->notNull(),
			'iso' => $this->string( Yii::$app->core->smallText ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull()
		], $this->options );

		// Index for columns country
		$this->createIndex( 'idx_' . $this->prefix . 'province_country', $this->prefix . 'core_province', 'countryId' );
	}

	private function upCity() {

		// Didn't add any dedicated table for zone(sub division) of a particular province/state, hence storing name directly.
		$this->createTable( $this->prefix . 'core_city', [
			'id' => $this->bigPrimaryKey( 20 ),
			'countryId' => $this->bigInteger( 20 )->notNull(),
			'provinceId' => $this->bigInteger( 20 ),
			'zone' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ), // It could be County in US, Tehsil in India
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'postal' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'latitude' => $this->float( 4 ),
			'longitude' => $this->float( 4 )
		], $this->options );

		// Index for columns country
		$this->createIndex( 'idx_' . $this->prefix . 'city_country', $this->prefix . 'core_city', 'countryId' );
		$this->createIndex( 'idx_' . $this->prefix . 'city_province', $this->prefix . 'core_city', 'provinceId' );
	}

	private function upAddress() {

		$this->createTable( $this->prefix . 'core_address', [
			'id' => $this->bigPrimaryKey( 20 ),
			'countryId' => $this->bigInteger( 20 )->notNull(),
			'provinceId' => $this->bigInteger( 20 ),
			'cityId' => $this->bigInteger( 20 ),
			'title' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'line1' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'line2' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'line3' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'countryName' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'provinceName' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'cityName' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'zip' => $this->string( Yii::$app->core->smallText )->defaultValue( null ),
			'subZip' => $this->string( Yii::$app->core->smallText )->defaultValue( null ),
			'firstName' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'lastName' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'phone' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'email' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'fax' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'website' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'latitude' => $this->float( 4 ),
			'longitude' => $this->float( 4 ),
			'zoomLevel' => $this->smallInteger( 6 )->defaultValue( 5 )
		], $this->options );

		// Index for columns country
		$this->createIndex( 'idx_' . $this->prefix . 'address_country', $this->prefix . 'core_address', 'countryId' );
		$this->createIndex( 'idx_' . $this->prefix . 'address_province', $this->prefix . 'core_address', 'provinceId' );
		$this->createIndex( 'idx_' . $this->prefix . 'address_city', $this->prefix . 'core_address', 'cityId' );
	}

	private function upRole() {

		$this->createTable( $this->prefix . 'core_role', [
			'id' => $this->bigPrimaryKey( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->largeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'group' => $this->boolean()->notNull()->defaultValue( false ),
			'adminUrl' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'homeUrl' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime()
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
			'name' => $this->string( Yii::$app->core->largeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'group' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime()
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
			'avatarId' => $this->bigInteger( 20 ),
			'status' => $this->smallInteger( 6 ),
			'email' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'username' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'passwordHash' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'firstName' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'lastName' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'dob' => $this->date(),
			'phone' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'avatarUrl' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'websiteUrl' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'verifyToken' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'resetToken' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'registeredAt' => $this->dateTime(),
			'lastLoginAt' => $this->dateTime(),
			'lastActivityAt' => $this->dateTime(),
			'authKey' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'accessToken' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'tokenCreatedAt' => $this->dateTime(),
			'tokenAccessedAt' => $this->dateTime(),
			'content' => $this->text(),
			'data' => $this->text()
		], $this->options );

		// Index for columns locale, gender and avatar
		$this->createIndex( 'idx_' . $this->prefix . 'user_locale', $this->prefix . 'core_user', 'localeId' );
		$this->createIndex( 'idx_' . $this->prefix . 'user_gender', $this->prefix . 'core_user', 'genderId' );
		$this->createIndex( 'idx_' . $this->prefix . 'user_avatar', $this->prefix . 'core_user', 'avatarId' );
	}

	private function upSite() {

		$this->createTable( $this->prefix . 'core_site', [
			'id' => $this->bigPrimaryKey( 20 ),
			'avatarId' => $this->bigInteger( 20 ),
			'bannerId' => $this->bigInteger( 20 ),
			'themeId' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->largeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'order' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'active' => $this->boolean()->notNull()->defaultValue( false ),
			'data' => $this->text()
		], $this->options );

		// Index for columns avatar, banner and theme
		$this->createIndex( 'idx_' . $this->prefix . 'site_avatar', $this->prefix . 'core_site', 'avatarId' );
		$this->createIndex( 'idx_' . $this->prefix . 'site_banner', $this->prefix . 'core_site', 'bannerId' );
		$this->createIndex( 'idx_' . $this->prefix . 'site_theme', $this->prefix . 'core_site', 'themeId' );
	}

	private function upSiteMeta() {

		$this->createTable( $this->prefix . 'core_site_meta', [
			'id' => $this->bigPrimaryKey( 20 ),
			'modelId' => $this->bigInteger( 20 )->notNull(),
			'name' => $this->string( Yii::$app->core->largeText )->notNull(),
			'label' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText ),
			'valueType' => $this->string( Yii::$app->core->mediumText )->notNull()->defaultValue( 'text' ),
			'value' => $this->text()
		], $this->options );

		// Index for columns site, parent, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'site_meta_parent', $this->prefix . 'core_site_meta', 'modelId' );
	}

	private function upSiteMember() {

		$this->createTable( $this->prefix . 'core_site_member', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 )->notNull(),
			'userId' => $this->bigInteger( 20 )->notNull(),
			'roleId' => $this->bigInteger( 20 )->notNull(),
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
			'ipNum' => $this->integer( 11 ),
			'url' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'controller' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'action' => $this->string( Yii::$app->core->largeText )->notNull(),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime()
		], $this->options );

		// Index for columns avatar, banner and theme
		$this->createIndex( 'idx_' . $this->prefix . 'site_access_site', $this->prefix . 'core_site_access', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'site_access_user', $this->prefix . 'core_site_access', 'userId' );
		$this->createIndex( 'idx_' . $this->prefix . 'site_access_role', $this->prefix . 'core_site_access', 'roleId' );
	}

	private function upFile() {

		$this->createTable( $this->prefix . 'core_file', [
			'id' => $this->bigPrimaryKey( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'title' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'extension' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'directory' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'size' => $this->float( 2 )->notNull()->defaultValue( 0 ),
			'visibility' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'type' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'url' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'medium' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'thumb' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'altText' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'link' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'shared' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime()
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'file_creator', $this->prefix . 'core_file', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'file_modifier', $this->prefix . 'core_file', 'modifiedBy' );
	}

	private function upGallery() {

		$this->createTable( $this->prefix . 'core_gallery', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 ),
			'templateId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'title' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'active' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'content' => $this->text(),
			'data' => $this->text()
		], $this->options );

		// Index for columns site, template, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'gallery_site', $this->prefix . 'core_gallery', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'gallery_template', $this->prefix . 'core_gallery', 'templateId' );
		$this->createIndex( 'idx_' . $this->prefix . 'gallery_creator', $this->prefix . 'core_gallery', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'gallery_modifier', $this->prefix . 'core_gallery', 'modifiedBy' );
	}

	private function upTag() {

		$this->createTable( $this->prefix . 'core_tag', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null )
		], $this->options );

		// Index for columns site
		$this->createIndex( 'idx_' . $this->prefix . 'tag_site', $this->prefix . 'core_tag', 'siteId' );
	}

	private function upCategory() {

		$this->createTable( $this->prefix . 'core_category', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 ),
			'parentId' => $this->bigInteger( 20 ),
			'rootId' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'featured' => $this->boolean()->notNull()->defaultValue( false ),
			'lValue' => $this->smallInteger( 6 ),
			'rValue' => $this->smallInteger( 6 ),
			'order' => $this->smallInteger( 20 ),
			'htmlOptions' => $this->text(),
			'content' => $this->text(),
			'data' => $this->text()
		], $this->options );

		// Index for columns site
		$this->createIndex( 'idx_' . $this->prefix . 'category_site', $this->prefix . 'core_category', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'category_parent', $this->prefix . 'core_category', 'parentId' );
		$this->createIndex( 'idx_' . $this->prefix . 'category_root', $this->prefix . 'core_category', 'rootId' );
	}

	private function upOption() {

		$this->createTable( $this->prefix . 'core_option', [
			'id' => $this->bigPrimaryKey( 20 ),
			'categoryId' => $this->bigInteger( 20 )->notNull(),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'value' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'icon' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'htmlOptions' => $this->text(),
			'content' => $this->text(),
			'data' => $this->text()
		], $this->options );

		// Index for columns site
		$this->createIndex( 'idx_' . $this->prefix . 'option_category', $this->prefix . 'core_option', 'categoryId' );
	}

	private function upForm() {

		$this->createTable( $this->prefix . 'core_form', [
			'id' => $this->bigPrimaryKey( 20 ),
			'siteId' => $this->bigInteger( 20 ),
			'templateId' => $this->bigInteger( 20 ),
			'createdBy' => $this->bigInteger( 20 )->notNull(),
			'modifiedBy' => $this->bigInteger( 20 ),
			'name' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'slug' => $this->string( Yii::$app->core->xxLargeText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'description' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'successMessage' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'captcha' => $this->boolean()->notNull()->defaultValue( false ),
			'visibility' => $this->smallInteger( 6 ),
			'active' => $this->boolean()->notNull()->defaultValue( false ),
			'userMail' => $this->boolean()->notNull()->defaultValue( false ),
			'adminMail' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'htmlOptions' => $this->text(),
			'content' => $this->text(),
			'data' => $this->text(),
			'uniqueSubmit' => $this->boolean()->notNull()->defaultValue( false )
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'form_site', $this->prefix . 'core_form', 'siteId' );
		$this->createIndex( 'idx_' . $this->prefix . 'form_template', $this->prefix . 'core_form', 'templateId' );
		$this->createIndex( 'idx_' . $this->prefix . 'form_creator', $this->prefix . 'core_form', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'form_modifier', $this->prefix . 'core_form', 'modifiedBy' );
	}

	private function upFormField() {

		$this->createTable( $this->prefix . 'core_form_field', [
			'id' => $this->bigPrimaryKey( 20 ),
			'formId' => $this->bigInteger( 20 )->notNull(),
			'name' => $this->string( Yii::$app->core->largeText )->notNull(),
			'label' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'type' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'icon' => $this->string( Yii::$app->core->largeText )->defaultValue( null ),
			'compress' => $this->boolean()->notNull()->defaultValue( false ),
			'validators' => $this->string( Yii::$app->core->xLargeText )->defaultValue( false ),
			'order' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'htmlOptions' => $this->text(),
			'content' => $this->text(),
			'data' => $this->text()
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'form_field_parent', $this->prefix . 'core_form_field', 'formId' );
	}

	private function upModelMessage() {

		$this->createTable( $this->prefix . 'core_model_message', [
			'id' => $this->bigPrimaryKey( 20 ),
			'localeId' => $this->bigInteger( 20 )->notNull(),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText ),
			'name' => $this->string( Yii::$app->core->largeText )->notNull(),
			'value' => $this->text()
		], $this->options );

		// Index for columns creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'model_message_locale', $this->prefix . 'core_model_message', 'localeId' );
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

	private function upModelComment() {

		$this->createTable( $this->prefix . 'core_model_comment', [
			'id' => $this->bigPrimaryKey( 20 ),
			'baseId' => $this->bigInteger( 20 ),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'createdBy' => $this->bigInteger( 20 ),
			'modifiedBy' => $this->bigInteger( 20 ),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'name' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'email' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'avatarUrl' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'websiteUrl' => $this->string( Yii::$app->core->xxLargeText )->defaultValue( null ),
			'ip' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'agent' => $this->string( Yii::$app->core->xLargeText )->defaultValue( null ),
			'status' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'type' => $this->string( Yii::$app->core->mediumText )->defaultValue( null ),
			'fragment' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'rating' => $this->smallInteger( 6 )->notNull()->defaultValue( 0 ),
			'featured' => $this->boolean()->notNull()->defaultValue( false ),
			'createdAt' => $this->dateTime()->notNull(),
			'modifiedAt' => $this->dateTime(),
			'approvedAt' => $this->dateTime(),
			'content' => $this->text(),
			'data' => $this->text()
		], $this->options );

		// Index for columns base, creator and modifier
		$this->createIndex( 'idx_' . $this->prefix . 'model_comment_base', $this->prefix . 'core_model_comment', 'baseId' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_comment_creator', $this->prefix . 'core_model_comment', 'createdBy' );
		$this->createIndex( 'idx_' . $this->prefix . 'model_comment_modifier', $this->prefix . 'core_model_comment', 'modifiedBy' );
	}

	private function upModelMeta() {

		$this->createTable( $this->prefix . 'core_model_meta', [
			'id' => $this->bigPrimaryKey( 20 ),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText ),
			'valueType' => $this->string( Yii::$app->core->mediumText )->notNull()->defaultValue( 'text' ),
			'name' => $this->string( Yii::$app->core->largeText )->notNull(),
			'label' => $this->string( Yii::$app->core->xLargeText )->notNull(),
			'value' => $this->text()
		], $this->options );
	}

	private function upModelObject() {

		$this->createTable( $this->prefix . 'core_model_object', [
			'id' => $this->bigPrimaryKey( 20 ),
			'modelId' => $this->bigInteger( 20 )->notNull(),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText ),
			'order' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'active' => $this->boolean()->notNull()->defaultValue( true )
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
			'type' => $this->string( Yii::$app->core->mediumText ),
			'order' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'active' => $this->boolean()->notNull()->defaultValue( true )
		], $this->options );

		// Index for columns user
		$this->createIndex( 'idx_' . $this->prefix . 'model_address_parent', $this->prefix . 'core_model_address', 'modelId' );
	}

	private function upModelFile() {

		$this->createTable( $this->prefix . 'core_model_file', [
			'id' => $this->bigPrimaryKey( 20 ),
			'modelId' => $this->bigInteger( 20 )->notNull(),
			'parentId' => $this->bigInteger( 20 )->notNull(),
			'parentType' => $this->string( Yii::$app->core->mediumText )->notNull(),
			'type' => $this->string( Yii::$app->core->mediumText ),
			'order' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'active' => $this->boolean()->notNull()->defaultValue( true )
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
			'order' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'active' => $this->boolean()->notNull()->defaultValue( true )
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
			'order' => $this->smallInteger( 6 )->defaultValue( 0 ),
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
			'order' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'active' => $this->boolean()->notNull()->defaultValue( true ),
			'userMapped' => $this->boolean()->notNull()->defaultValue( false )
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
			'order' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'active' => $this->boolean()->notNull()->defaultValue( true )
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
			'order' => $this->smallInteger( 6 )->defaultValue( 0 ),
			'active' => $this->boolean()->notNull()->defaultValue( true )
		], $this->options );

		// Index for columns user
		$this->createIndex( 'idx_' . $this->prefix . 'model_form_parent', $this->prefix . 'core_model_form', 'modelId' );
	}

	private function generateForeignKeys() {

		// Theme
		$this->addForeignKey( 'fk_' . $this->prefix . 'theme_creator', $this->prefix . 'core_theme', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'theme_modifier', $this->prefix . 'core_theme', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Template
		$this->addForeignKey( 'fk_' . $this->prefix . 'template_creator', $this->prefix . 'core_template', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'template_modifier', $this->prefix . 'core_template', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Object
		$this->addForeignKey( 'fk_' . $this->prefix . 'object_site', $this->prefix . 'core_object', 'siteId', $this->prefix . 'core_site', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'object_theme', $this->prefix . 'core_object', 'themeId', $this->prefix . 'core_theme', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'object_template', $this->prefix . 'core_object', 'templateId', $this->prefix . 'core_template', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'object_avatar', $this->prefix . 'core_object', 'avatarId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'object_banner', $this->prefix . 'core_object', 'bannerId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'object_creator', $this->prefix . 'core_object', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'object_modifier', $this->prefix . 'core_object', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Province
		$this->addForeignKey( 'fk_' . $this->prefix . 'province_country', $this->prefix . 'core_province', 'countryId', $this->prefix . 'core_country', 'id', 'RESTRICT' );

		// City
		$this->addForeignKey( 'fk_' . $this->prefix . 'city_country', $this->prefix . 'core_city', 'countryId', $this->prefix . 'core_country', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'city_province', $this->prefix . 'core_city', 'provinceId', $this->prefix . 'core_province', 'id', 'RESTRICT' );

		// Address
		$this->addForeignKey( 'fk_' . $this->prefix . 'address_country', $this->prefix . 'core_address', 'countryId', $this->prefix . 'core_country', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'address_province', $this->prefix . 'core_address', 'provinceId', $this->prefix . 'core_province', 'id', 'CASCADE' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'address_city', $this->prefix . 'core_address', 'cityId', $this->prefix . 'core_city', 'id', 'CASCADE' );

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
		$this->addForeignKey( 'fk_' . $this->prefix . 'user_avatar', $this->prefix . 'core_user', 'avatarId', $this->prefix . 'core_file', 'id', 'SET NULL' );

		// Site
		$this->addForeignKey( 'fk_' . $this->prefix . 'site_avatar', $this->prefix . 'core_site', 'avatarId', $this->prefix . 'core_file', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'site_banner', $this->prefix . 'core_site', 'bannerId', $this->prefix . 'core_file', 'id', 'SET NULL' );
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
		$this->addForeignKey( 'fk_' . $this->prefix . 'site_access_role', $this->prefix . 'core_site_access', 'roleId', $this->prefix . 'core_role', 'id', 'CASCADE' );

		// File
		$this->addForeignKey( 'fk_' . $this->prefix . 'file_creator', $this->prefix . 'core_file', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'file_modifier', $this->prefix . 'core_file', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Gallery
		$this->addForeignKey( 'fk_' . $this->prefix . 'gallery_site', $this->prefix . 'core_gallery', 'siteId', $this->prefix . 'core_site', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'gallery_template', $this->prefix . 'core_gallery', 'templateId', $this->prefix . 'core_template', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'gallery_creator', $this->prefix . 'core_gallery', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'gallery_modifier', $this->prefix . 'core_gallery', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Tag
		$this->addForeignKey( 'fk_' . $this->prefix . 'tag_site', $this->prefix . 'core_tag', 'siteId', $this->prefix . 'core_site', 'id', 'RESTRICT' );

		// Category
		$this->addForeignKey( 'fk_' . $this->prefix . 'category_site', $this->prefix . 'core_category', 'siteId', $this->prefix . 'core_site', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'category_parent', $this->prefix . 'core_category', 'parentId', $this->prefix . 'core_category', 'id', 'RESTRICT' );

		// Option
		$this->addForeignKey( 'fk_' . $this->prefix . 'option_category', $this->prefix . 'core_option', 'categoryId', $this->prefix . 'core_category', 'id', 'RESTRICT' );

		// Form
		$this->addForeignKey( 'fk_' . $this->prefix . 'form_site', $this->prefix . 'core_form', 'siteId', $this->prefix . 'core_site', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'form_template', $this->prefix . 'core_form', 'templateId', $this->prefix . 'core_template', 'id', 'SET NULL' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'form_creator', $this->prefix . 'core_form', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'form_modifier', $this->prefix . 'core_form', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Form Field
		$this->addForeignKey( 'fk_' . $this->prefix . 'form_field_parent', $this->prefix . 'core_form_field', 'formId', $this->prefix . 'core_form', 'id', 'RESTRICT' );

		// Model Message
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_message_locale', $this->prefix . 'core_model_message', 'localeId', $this->prefix . 'core_locale', 'id', 'CASCADE' );

		// Model Comment
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_comment_base', $this->prefix . 'core_model_comment', 'baseId', $this->prefix . 'core_model_comment', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_comment_creator', $this->prefix . 'core_model_comment', 'createdBy', $this->prefix . 'core_user', 'id', 'RESTRICT' );
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_comment_modifier', $this->prefix . 'core_model_comment', 'modifiedBy', $this->prefix . 'core_user', 'id', 'SET NULL' );

		// Model Object
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_object_parent', $this->prefix . 'core_model_object', 'modelId', $this->prefix . 'core_object', 'id', 'CASCADE' );

		// Model Address
		$this->addForeignKey( 'fk_' . $this->prefix . 'model_address_parent', $this->prefix . 'core_model_address', 'modelId', $this->prefix . 'core_address', 'id', 'CASCADE' );

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
	}

	public function down() {

		if( $this->fk ) {

			$this->dropForeignKeys();
		}

		$this->dropTable( $this->prefix . 'core_locale' );

		$this->dropTable( $this->prefix . 'core_theme' );
		$this->dropTable( $this->prefix . 'core_template' );
		$this->dropTable( $this->prefix . 'core_object' );

		$this->dropTable( $this->prefix . 'core_country' );
		$this->dropTable( $this->prefix . 'core_province' );
		$this->dropTable( $this->prefix . 'core_city' );
		$this->dropTable( $this->prefix . 'core_address' );

		$this->dropTable( $this->prefix . 'core_role' );
		$this->dropTable( $this->prefix . 'core_permission' );
		$this->dropTable( $this->prefix . 'core_role_permission' );

		$this->dropTable( $this->prefix . 'core_user' );
		$this->dropTable( $this->prefix . 'core_site' );
		$this->dropTable( $this->prefix . 'core_site_meta' );
		$this->dropTable( $this->prefix . 'core_site_member' );
		$this->dropTable( $this->prefix . 'core_site_access' );

		$this->dropTable( $this->prefix . 'core_file' );
		$this->dropTable( $this->prefix . 'core_gallery' );

		$this->dropTable( $this->prefix . 'core_tag' );
		$this->dropTable( $this->prefix . 'core_category' );
		$this->dropTable( $this->prefix . 'core_option' );

		$this->dropTable( $this->prefix . 'core_form' );
		$this->dropTable( $this->prefix . 'core_form_field' );

		$this->dropTable( $this->prefix . 'core_model_message' );
		$this->dropTable( $this->prefix . 'core_model_hierarchy' );
		$this->dropTable( $this->prefix . 'core_model_comment' );
		$this->dropTable( $this->prefix . 'core_model_meta' );

		$this->dropTable( $this->prefix . 'core_model_object' );
		$this->dropTable( $this->prefix . 'core_model_address' );
		$this->dropTable( $this->prefix . 'core_model_file' );
		$this->dropTable( $this->prefix . 'core_model_gallery' );
		$this->dropTable( $this->prefix . 'core_model_tag' );
		$this->dropTable( $this->prefix . 'core_model_category' );
		$this->dropTable( $this->prefix . 'core_model_option' );
		$this->dropTable( $this->prefix . 'core_model_form' );
	}

	private function dropForeignKeys() {

		// Theme
		$this->dropForeignKey( 'fk_' . $this->prefix . 'theme_creator', $this->prefix . 'core_theme' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'theme_modifier', $this->prefix . 'core_theme' );

		// Template
		$this->dropForeignKey( 'fk_' . $this->prefix . 'template_creator', $this->prefix . 'core_template' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'template_modifier', $this->prefix . 'core_template' );

		// Object
		$this->dropForeignKey( 'fk_' . $this->prefix . 'object_site', $this->prefix . 'core_object' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'object_theme', $this->prefix . 'core_object' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'object_template', $this->prefix . 'core_object' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'object_avatar', $this->prefix . 'core_object' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'object_banner', $this->prefix . 'core_object' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'object_creator', $this->prefix . 'core_object' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'object_modifier', $this->prefix . 'core_object' );

		// Province
		$this->dropForeignKey( 'fk_' . $this->prefix . 'province_country', $this->prefix . 'core_province' );

		// City
		$this->dropForeignKey( 'fk_' . $this->prefix . 'city_country', $this->prefix . 'core_city', 'countryId' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'city_province', $this->prefix . 'core_city', 'provinceId' );

		// Address
		$this->dropForeignKey( 'fk_' . $this->prefix . 'address_country', $this->prefix . 'core_address' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'address_province', $this->prefix . 'core_address' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'address_city', $this->prefix . 'core_address' );

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
		$this->dropForeignKey( 'fk_' . $this->prefix . 'user_avatar', $this->prefix . 'core_user' );

		// Site
		$this->dropForeignKey( 'fk_' . $this->prefix . 'site_avatar', $this->prefix . 'core_site' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'site_banner', $this->prefix . 'core_site' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'site_theme', $this->prefix . 'core_site' );

		// Site Meta
		$this->dropForeignKey( 'fk_' . $this->prefix . 'site_meta_parent', $this->prefix . 'core_site_meta' );

		// Site Member
		$this->dropForeignKey( 'fk_' . $this->prefix . 'site_member_site', $this->prefix . 'core_site_member' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'site_member_user', $this->prefix . 'core_site_member' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'site_member_role', $this->prefix . 'core_site_member' );

		// Site Access
		$this->dropForeignKey( 'fk_' . $this->prefix . 'site_access_site', $this->prefix . 'core_site_access' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'site_access_user', $this->prefix . 'core_site_access' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'site_access_role', $this->prefix . 'core_site_access' );

		// File
		$this->dropForeignKey( 'fk_' . $this->prefix . 'file_creator', $this->prefix . 'core_file' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'file_modifier', $this->prefix . 'core_file' );

		// Gallery
		$this->dropForeignKey( 'fk_' . $this->prefix . 'gallery_site', $this->prefix . 'core_gallery' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'gallery_template', $this->prefix . 'core_gallery' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'gallery_creator', $this->prefix . 'core_gallery' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'gallery_modifier', $this->prefix . 'core_gallery' );

		// Tag
		$this->dropForeignKey( 'fk_' . $this->prefix . 'tag_site', $this->prefix . 'core_tag', 'siteId', $this->prefix . 'core_site', 'id', 'RESTRICT' );

		// Category
		$this->dropForeignKey( 'fk_' . $this->prefix . 'category_site', $this->prefix . 'core_category' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'category_parent', $this->prefix . 'core_category' );

		// Option
		$this->dropForeignKey( 'fk_' . $this->prefix . 'option_category', $this->prefix . 'core_option' );

		// Form
		$this->dropForeignKey( 'fk_' . $this->prefix . 'form_site', $this->prefix . 'core_form' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'form_template', $this->prefix . 'core_form' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'form_creator', $this->prefix . 'core_form' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'form_modifier', $this->prefix . 'core_form' );

		// Form Field
		$this->dropForeignKey( 'fk_' . $this->prefix . 'form_field_parent', $this->prefix . 'core_form_field' );

		// Model Message
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_message_locale', $this->prefix . 'core_model_message' );

		// Model Comment
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_comment_base', $this->prefix . 'core_model_comment' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_comment_creator', $this->prefix . 'core_model_comment' );
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_comment_modifier', $this->prefix . 'core_model_comment' );

		// Model Object
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_object_parent', $this->prefix . 'core_model_object' );

		// Model Address
		$this->dropForeignKey( 'fk_' . $this->prefix . 'model_address_parent', $this->prefix . 'core_model_address' );

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
	}
}
