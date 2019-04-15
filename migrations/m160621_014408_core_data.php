<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\Locale;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\Role;
use cmsgears\core\common\models\entities\Permission;
use cmsgears\core\common\models\resources\Form;
use cmsgears\core\common\models\resources\FormField;
use cmsgears\core\common\models\resources\Category;

use cmsgears\core\common\services\base\EntityService;

use cmsgears\core\common\utilities\DateUtil;

/**
 * The core data migration inserts the base data required to run the application.
 *
 * @since 1.0.0
 */
class m160621_014408_core_data extends \cmsgears\core\common\base\Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	// Entities

	private $site;
	private $locale;

	private $master;

	// Config

	private $siteName;
	private $siteTitle;

	private $siteMaster;

	private $primaryDomain;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;

		// Site config
		$this->siteName		= Yii::$app->migration->getSiteName();
		$this->siteTitle	= Yii::$app->migration->getSiteTitle();
		$this->siteMaster	= Yii::$app->migration->getSiteMaster();

		$this->primaryDomain = Yii::$app->migration->getPrimaryDomain();
	}

	public function up() {

		// Insert Locales
		$this->insertLocale();

		// Create default users
		$this->insertDefaultUsers();

		// Create main site
		$this->insertMainSite();

		// Create roles and permissions
		$this->insertRolePermission();

		// Create permissions
		$this->insertFileRolePermission();
		$this->insertGalleryRolePermission();

		// Create Site Members
		$this->insertSiteMembers();

		// Create various config
		$this->insertCoreConfig();
		$this->insertCacheConfig();
		$this->insertMailConfig();
		$this->insertCommentsConfig();
		$this->insertBackendConfig();
		$this->insertFrontendConfig();

		// Init default config
		$this->insertDefaultConfig();

		// Default Categories and Options
		$this->insertCategories();

		$this->insertNotificationTemplates();
	}

	private function insertLocale() {

		$columns = [ 'code', 'name' ];

		$locales = [
			[ 'en_AS', 'English American Samoa' ],
			[ 'en_AU', 'English Australia' ],
			[ 'en_BE', 'English Belgium' ],
			[ 'en_BZ', 'English Belize' ],
			[ 'en_BW', 'English Botswana' ],
			[ 'en_CA', 'English Canada' ],
			[ 'en_GB', 'English United Kingdom' ],
			[ 'en_GU', 'English Guam' ],
			[ 'en_HK', 'English Hong Kong SAR China' ],
			[ 'en_IE', 'English Ireland' ],
			[ 'en_IN', 'English India' ],
			[ 'en_JM', 'English Jamaica' ],
			[ 'en_MT', 'English Malta' ],
			[ 'en_MH', 'English Marshall Islands' ],
			[ 'en_MP', 'English Northern Mariana Islands' ],
			[ 'en_MU', 'English Mauritius' ],
			[ 'en_NA', 'English Namibia' ],
			[ 'en_NZ', 'English New Zealand' ],
			[ 'en_PH', 'English Philippines' ],
			[ 'en_PK', 'English Pakistan' ],
			[ 'en_SG', 'English Singapore' ],
			[ 'en_TT', 'English Trinidad and Tobago' ],
			[ 'en_UM', 'English U.S. Minor Outlying Islands' ],
			[ 'en_US', 'English US' ],
			[ 'en_VI', 'English U.S. Virgin Islands' ],
			[ 'en_ZA', 'English South Africa' ],
			[ 'en_ZW', 'English Zimbabwe' ]
		];

		$this->batchInsert( $this->prefix . 'core_locale', $columns, $locales );

		$this->locale = Locale::findByCode( 'en_US' );
	}

	private function insertDefaultUsers() {

		$primaryDomain	= $this->primaryDomain;
		$siteMaster		= $this->siteMaster;

		// Default password for all test users is test#123
		// Super Admin i.e. demomaster must change username, password and email on first login and remove other users if required.

		$columns = [ 'localeId', 'status', 'email', 'username', 'type', 'passwordHash', 'firstName', 'lastName', 'name', 'registeredAt', 'lastLoginAt', 'authKey' ];

		$users	= [
			[ $this->locale->id, User::STATUS_ACTIVE, "$siteMaster@$primaryDomain", $siteMaster, CoreGlobal::TYPE_DEFAULT, '$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W', 'Demo', 'Master', 'Demo Master', DateUtil::getDateTime(), DateUtil::getDateTime(), 'JuL37UBqGpjnA7kaPiRnlsiWRwbRvXx7' ]
		];

		if( Yii::$app->migration->isTestAccounts() ) {

			$users[] = [ $this->locale->id, User::STATUS_ACTIVE, "demoadmin@$primaryDomain", 'demoadmin', CoreGlobal::TYPE_DEFAULT, '$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','Demo','Admin', 'Demo Admin', DateUtil::getDateTime(), DateUtil::getDateTime(), 'SQ1LLCWEPva4IKuQklILLGDpmUTGzq8E' ];
			$users[] = [ $this->locale->id, User::STATUS_ACTIVE, "demouser@$primaryDomain", 'demouser', CoreGlobal::TYPE_DEFAULT, '$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','Demo','User', 'Demo User', DateUtil::getDateTime(), DateUtil::getDateTime(), '-jG5ExHS0Y39ucSxHhl3PZ4xmPsfvQFC' ];
			$users[] = [ $this->locale->id, User::STATUS_ACTIVE, "demouadmin@$primaryDomain", 'demouadmin', CoreGlobal::TYPE_DEFAULT, '$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','Demo','User', 'Demo User', DateUtil::getDateTime(), DateUtil::getDateTime(), '-jG5ExHS0Y39ucSxHhl3PZ4xmPsfvQFC' ];
		}

		$this->batchInsert( $this->prefix . 'core_user', $columns, $users );

		$this->master = User::findByUsername( $this->siteMaster );
	}

	private function insertMainSite() {

		$this->insert( $this->prefix . 'core_site', [
			'createdBy' => $this->master->id, 'modifiedBy' => $this->master->id,
			'name' => 'Main', 'slug' => CoreGlobal::SITE_MAIN,
			'order' => 0, 'active' => true,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		]);

		$this->site	= Site::findBySlug( CoreGlobal::SITE_MAIN );

		Yii::$app->core->setSite( $this->site );
	}

	private function insertRolePermission() {

		// Roles

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'adminUrl', 'homeUrl', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$roles = [
			[ $this->master->id, $this->master->id, 'Super Admin', 'super-admin', 'dashboard', NULL, CoreGlobal::TYPE_SYSTEM, NULL, 'The Super Admin have all the permisisons to perform operations on the admin site and website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Admin','admin','dashboard', NULL, CoreGlobal::TYPE_SYSTEM, NULL, 'The Admin have all the permisisons to perform operations on the admin site and website except RBAC module.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'User', 'user', NULL, NULL, CoreGlobal::TYPE_SYSTEM, NULL, 'The role User is limited to website users.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'User Admin', 'user-admin', 'dashboard', NULL, CoreGlobal::TYPE_SYSTEM, NULL, 'The role User Admin is limited to manage site users from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_role', $columns, $roles );

		$superAdminRole	= Role::findBySlugType( 'super-admin', CoreGlobal::TYPE_SYSTEM );
		$adminRole		= Role::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$userRole		= Role::findBySlugType( 'user', CoreGlobal::TYPE_SYSTEM );
		$userAdminRole	= Role::findBySlugType( 'user-admin', CoreGlobal::TYPE_SYSTEM );

		// Permissions

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			[ $this->master->id, $this->master->id, 'Admin', 'admin', CoreGlobal::TYPE_SYSTEM, NULL, 'The permission admin is to distinguish between admin and app user. It is a must have permission for admins.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'User', 'user', CoreGlobal::TYPE_SYSTEM, NULL, 'The permission user is to distinguish between admin and app user. It is a must have permission for app users.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Core', 'core', CoreGlobal::TYPE_SYSTEM, NULL, 'The permission core is to manage sites, themes, testimonials, countries, drop downs and settings from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Identity', 'identity', CoreGlobal::TYPE_SYSTEM, NULL, 'The permission identity is to manage users from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'RBAC', 'rbac', CoreGlobal::TYPE_SYSTEM, NULL, 'The permission rbac is to manage roles and permissions from admin. It also need identity permission.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		$adminPerm		= Permission::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$userPerm		= Permission::findBySlugType( 'user', CoreGlobal::TYPE_SYSTEM );
		$corePerm		= Permission::findBySlugType( 'core', CoreGlobal::TYPE_SYSTEM );
		$identityPerm	= Permission::findBySlugType( 'identity', CoreGlobal::TYPE_SYSTEM );
		$rbacPerm		= Permission::findBySlugType( 'rbac', CoreGlobal::TYPE_SYSTEM );

		// RBAC Mapping

		$columns = [ 'roleId', 'permissionId' ];

		$mappings = [
			[ $superAdminRole->id, $adminPerm->id ], [ $superAdminRole->id, $userPerm->id ], [ $superAdminRole->id, $corePerm->id ], [ $superAdminRole->id, $identityPerm->id ], [ $superAdminRole->id, $rbacPerm->id ],
			[ $adminRole->id, $adminPerm->id ], [ $adminRole->id, $userPerm->id ], [ $adminRole->id, $corePerm->id ], [ $adminRole->id, $identityPerm->id ],
			[ $userRole->id, $userPerm->id ],
			[ $userAdminRole->id, $adminPerm->id ], [ $userAdminRole->id, $userPerm->id ], [ $userAdminRole->id, $identityPerm->id ]
		];

		$this->batchInsert( $this->prefix . 'core_role_permission', $columns, $mappings );
	}

	private function insertFileRolePermission() {

		// Roles

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'adminUrl', 'homeUrl', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$roles = [
			[ $this->master->id, $this->master->id, 'File Admin', 'file-admin', 'dashboard', NULL, CoreGlobal::TYPE_SYSTEM, NULL, 'The role File Admin is limited to manage files from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_role', $columns, $roles );

		$superAdminRole		= Role::findBySlugType( 'super-admin', CoreGlobal::TYPE_SYSTEM );
		$adminRole			= Role::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$fileAdminRole		= Role::findBySlugType( 'file-admin', CoreGlobal::TYPE_SYSTEM );

		// Permissions
		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'group', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			// Admin Permissions - Hard Coded
			[ $this->master->id, $this->master->id, 'Admin Files', 'admin-files', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission admin files allows user to administer files from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		// Admin
		$adminPerm		= Permission::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$userPerm		= Permission::findBySlugType( 'user', CoreGlobal::TYPE_SYSTEM );
		$fileAdminPerm	= Permission::findBySlugType( 'admin-files', CoreGlobal::TYPE_SYSTEM );

		// RBAC Mapping

		$columns = [ 'roleId', 'permissionId' ];

		$mappings = [
			[ $superAdminRole->id, $fileAdminPerm->id ],
			[ $adminRole->id, $fileAdminPerm->id ],
			[ $fileAdminRole->id, $adminPerm->id ], [ $fileAdminRole->id, $userPerm->id ], [ $fileAdminRole->id, $fileAdminPerm->id ]
		];

		$this->batchInsert( $this->prefix . 'core_role_permission', $columns, $mappings );
	}

	private function insertGalleryRolePermission() {

		// Roles

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'adminUrl', 'homeUrl', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$roles = [
			[ $this->master->id, $this->master->id, 'Gallery Admin', 'gallery-admin', 'dashboard', NULL, CoreGlobal::TYPE_SYSTEM, NULL, 'The role Gallery Admin is limited to manage galleries from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_role', $columns, $roles );

		$superAdminRole		= Role::findBySlugType( 'super-admin', CoreGlobal::TYPE_SYSTEM );
		$adminRole			= Role::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$galleryAdminRole	= Role::findBySlugType( 'gallery-admin', CoreGlobal::TYPE_SYSTEM );

		// Permissions
		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'group', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			// Admin Permissions - Hard Coded
			[ $this->master->id, $this->master->id, 'Admin Galleries', 'admin-galleries', CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission admin galleries allows user to administer galleries from admin. It also need admin files permission.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		// Admin
		$adminPerm			= Permission::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$userPerm			= Permission::findBySlugType( 'user', CoreGlobal::TYPE_SYSTEM );
		$galleryAdminPerm	= Permission::findBySlugType( 'admin-galleries', CoreGlobal::TYPE_SYSTEM );

		// RBAC Mapping

		$columns = [ 'roleId', 'permissionId' ];

		$mappings = [
			[ $superAdminRole->id, $galleryAdminPerm->id ],
			[ $adminRole->id, $galleryAdminPerm->id ],
			[ $galleryAdminRole->id, $adminPerm->id ], [ $galleryAdminRole->id, $userPerm->id ], [ $galleryAdminRole->id, $galleryAdminPerm->id ]
		];

		$this->batchInsert( $this->prefix . 'core_role_permission', $columns, $mappings );
	}

	private function insertSiteMembers() {

		$superAdminRole	= Role::findBySlugType( 'super-admin', CoreGlobal::TYPE_SYSTEM );
		$adminRole		= Role::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$userRole		= Role::findBySlugType( 'user', CoreGlobal::TYPE_SYSTEM );
		$userAdminRole	= Role::findBySlugType( 'user-admin', CoreGlobal::TYPE_SYSTEM );

		$columns = [ 'siteId', 'userId', 'roleId', 'createdAt', 'modifiedAt' ];

		$roles = [
			[ $this->site->id, $this->master->id, $superAdminRole->id, DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		if( Yii::$app->migration->isTestAccounts() ) {

			$admin	= User::findByUsername( 'demoadmin' );
			$user	= User::findByUsername( 'demouser' );
			$uadmin	= User::findByUsername( 'demouadmin' );

			$roles[] = [ $this->site->id, $admin->id, $adminRole->id, DateUtil::getDateTime(), DateUtil::getDateTime() ];
			$roles[] = [ $this->site->id, $user->id, $userRole->id, DateUtil::getDateTime(), DateUtil::getDateTime() ];
			$roles[] = [ $this->site->id, $uadmin->id, $userAdminRole->id, DateUtil::getDateTime(), DateUtil::getDateTime() ];
		}

		$this->batchInsert( $this->prefix . 'core_site_member', $columns, $roles );
	}

	private function insertCoreConfig() {

		$this->insert( $this->prefix . 'core_form', [
			'siteId' => $this->site->id,
			'createdBy' => $this->master->id, 'modifiedBy' => $this->master->id,
			'name' => 'Config Core', 'slug' => 'config-core',
			'type' => CoreGlobal::TYPE_SYSTEM,
			'description' => 'Core configuration form.',
			'success' => 'All configurations saved successfully.',
			'captcha' => false,
			'visibility' => Form::VISIBILITY_PROTECTED,
			'status' => Form::STATUS_ACTIVE, 'userMail' => false,'adminMail' => false,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		]);

		$config	= Form::findBySlugType( 'config-core', CoreGlobal::TYPE_SYSTEM );

		$columns = [ 'formId', 'name', 'label', 'type', 'compress', 'meta', 'active', 'validators', 'order', 'icon', 'htmlOptions' ];

		$fields	= [
			[ $config->id, 'locale_message', 'Locale Message', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Check for i18n support."}' ],
			[ $config->id, 'language','Language', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Language used on html tag.","placeholder":"Language"}' ],
			[ $config->id, 'locale','Locale', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Site default locale.","placeholder":"Locale"}' ],
			[ $config->id, 'charset','Charset', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Charset used on html head meta.","placeholder":"Charset"}' ],
			[ $config->id, 'site_title','Site Title', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Site title used in forming page title.","placeholder":"Site Title"}' ],
			[ $config->id, 'site_name','Site Name', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Site name used on footers etc.","placeholder":"Site Name"}' ],
			[ $config->id, 'site_url','Frontend URL', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Frontend URL","placeholder":"Frontend URL"}' ],
			[ $config->id, 'admin_url','Backend URL', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Backend URL","placeholder":"Backend URL"}' ],
			[ $config->id, 'resource_url','Resource URL', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Resource URL","placeholder":"Resource URL"}' ],
			[ $config->id, 'registration','Registration', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Check whether site registration is allowed."}' ],
			[ $config->id, 'login','Login', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Check whether site login is allowed."}' ],
			[ $config->id, 'change_email','Change Email', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Check whether email change is allowed for user profile."}' ],
			[ $config->id, 'change_username','Change Username', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Check whether username change is allowed for user profile."}' ],
			[ $config->id, 'change_mobile','Change Mobile', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Check whether mobile number change is allowed for user profile."}' ],
			[ $config->id, 'date_format','Date Format', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Date format used by the formatter.","placeholder":"Date Format"}' ],
			[ $config->id, 'time_format','Time Format', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Time format used by the formatter.","placeholder":"Time Format"}' ],
			[ $config->id, 'date_time_format','Date Time Format', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Date time format used by the formatter.","placeholder":"Time Format"}' ],
			[ $config->id, 'timezone','Timezone', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Time format used by the formatter.","placeholder":"Time Format"}' ],
			[ $config->id, 'auto_login','Auto Login', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Auto login on account confirmation and activation."}' ],
			[ $config->id, 'auto_load','Auto Load', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Auto load widgets etc using ajax."}' ]
		];

		$this->batchInsert( $this->prefix . 'core_form_field', $columns, $fields );
	}

	private function insertCacheConfig() {

		$this->insert( $this->prefix . 'core_form', [
			'siteId' => $this->site->id,
			'createdBy' => $this->master->id, 'modifiedBy' => $this->master->id,
			'name' => 'Config Cache', 'slug' => 'config-cache',
			'type' => CoreGlobal::TYPE_SYSTEM,
			'description' => 'Cache configuration form.',
			'success' => 'All configurations saved successfully.',
			'captcha' => false,
			'visibility' => Form::VISIBILITY_PROTECTED,
			'status' => Form::STATUS_ACTIVE, 'userMail' => false,'adminMail' => false,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		]);

		$config	= Form::findBySlugType( 'config-cache', CoreGlobal::TYPE_SYSTEM );

		$columns = [ 'formId', 'name', 'label', 'type', 'compress', 'meta', 'active', 'validators', 'order', 'icon', 'htmlOptions' ];

		$fields	= [
			[ $config->id, 'caching', 'Caching', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Enable cache"}' ],
			[ $config->id, 'cache_duration','Cache Duration', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Cache Duration in minutes for default and volatile cache","placeholder":"Cache Duration"}' ],
			[ $config->id, 'default_cache','Default Cache', FormField::TYPE_SELECT, false, true, true, NULL, 0, NULL, '{"title":"Default Cache","items":{"none":"Choose Cache Type","file":"File","database":"Database","memcached":"Memcached","redis":"Redis"}}' ],
			[ $config->id, 'primary_cache','Primary Cache', FormField::TYPE_SELECT, false, true, true, NULL, 0, NULL, '{"title":"Primary Cache","items":{"none":"Choose Cache Type","file":"File","database":"Database"}}' ],
			[ $config->id, 'secondary_cache','Secondary Cache', FormField::TYPE_SELECT, false, true, true, NULL, 0, NULL, '{"title":"Secondary Cache","items":{"none":"Choose Cache Type","elastic":"Elasticsearch","redis":"Redis"}}' ]
		];

		$this->batchInsert( $this->prefix . 'core_form_field', $columns, $fields );
	}

	private function insertMailConfig() {

		$this->insert( $this->prefix . 'core_form', [
			'siteId' => $this->site->id,
			'createdBy' => $this->master->id, 'modifiedBy' => $this->master->id,
			'name' => 'Config Mail', 'slug' => 'config-mail',
			'type' => CoreGlobal::TYPE_SYSTEM,
			'description' => 'Mail configuration form.',
			'success' => 'All configurations saved successfully.',
			'captcha' => false,
			'visibility' => Form::VISIBILITY_PROTECTED,
			'status' => Form::STATUS_ACTIVE, 'userMail' => false,'adminMail' => false,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		]);

		$config	= Form::findBySlugType( 'config-mail', CoreGlobal::TYPE_SYSTEM );

		$columns = [ 'formId', 'name', 'label', 'type', 'compress', 'meta', 'active', 'validators', 'order', 'icon', 'htmlOptions' ];

		$fields	= [
			[ $config->id, 'smtp','SMTP', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Check whether SMTP is required."}' ],
			[ $config->id, 'smtp_username','SMTP Username', FormField::TYPE_TEXT, false, true, true, NULL, 0, NULL , '{"title":"SMTP username.","placeholder":"SMTP Username"}' ],
			[ $config->id, 'smtp_password','SMTP Password', FormField::TYPE_PASSWORD, false, true, true, NULL, 0, NULL, '{"title":"SMTP password.","placeholder":"SMTP Password"}' ],
			[ $config->id, 'smtp_host','SMTP Host', FormField::TYPE_TEXT, false, true, true, NULL, 0, NULL, '{"title":"SMTP host.","placeholder":"SMTP Host"}' ],
			[ $config->id, 'smtp_port','SMTP Port', FormField::TYPE_TEXT, false, true, true, NULL, 0, NULL, '{"title":"SMTP port.","placeholder":"SMTP Port"}' ],
			[ $config->id, 'smtp_encryption','SMTP Encryption', FormField::TYPE_SELECT, false, true, true, NULL, 0, NULL, '{"title":"SMTP encryption.","items":{"none":"Choose Encryption","ssl":"SSL","tls":"TLS"}}' ],
			[ $config->id, 'debug','SMTP Debug', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Check whether SMTP debug is required."}' ],
			[ $config->id, 'sender_name','Sender Name', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Sender name.","placeholder":"Sender Name"}' ],
			[ $config->id, 'sender_email','Sender Email', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Sender email.","placeholder":"Sender Email"}' ],
			[ $config->id, 'contact_name','Contact Name', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Contact name.","placeholder":"Contact Name"}' ],
			[ $config->id, 'contact_email','Contact Email', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Contact email.","placeholder":"Contact Email"}' ],
			[ $config->id, 'info_name','Info Name', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Info name.","placeholder":"Info Name"}' ],
			[ $config->id, 'info_email','Info Email', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Info email.","placeholder":"Info Email"}' ]
		];

		$this->batchInsert( $this->prefix . 'core_form_field', $columns, $fields );
	}

	private function insertCommentsConfig() {

		$this->insert( $this->prefix . 'core_form', [
			'siteId' => $this->site->id,
			'createdBy' => $this->master->id, 'modifiedBy' => $this->master->id,
			'name' => 'Config Comment', 'slug' => 'config-comment',
			'type' => CoreGlobal::TYPE_SYSTEM,
			'description' => 'Comment configuration form.',
			'success' => 'All configurations saved successfully.',
			'captcha' => false,
			'visibility' => Form::VISIBILITY_PROTECTED,
			'status' => Form::STATUS_ACTIVE, 'userMail' => false,'adminMail' => false,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		]);

		$config	= Form::findBySlugType( 'config-comment', CoreGlobal::TYPE_SYSTEM );

		$columns = [ 'formId', 'name', 'label', 'type', 'compress', 'meta', 'active', 'validators', 'order', 'icon', 'htmlOptions' ];

		$fields	= [
			[ $config->id, 'comments', 'Comments', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Comments allowed."}' ],
			[ $config->id, 'comments_user', 'Comments User', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Comments need logged in user."}' ],
			[ $config->id, 'comments_recent', 'Comments Recent', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Show recent comments on top."}' ],
			[ $config->id, 'comments_limit','Comments Limit', FormField::TYPE_TEXT, false, true, true, 'required,number', 0, NULL, '{"title":"Page limit of comments.","placeholder":"Comments per page"}' ],
			[ $config->id, 'comments_email_admin', 'Comments Email Admin', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Trigger mail to admin for new comment."}' ],
			[ $config->id, 'comments_email_user', 'Comments Email User', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Trigger mail to user on approval."}' ],
			[ $config->id, 'comments_form_top', 'Comments Form Top', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Show the comment form on top of comments."}' ],
			[ $config->id, 'comments_auto', 'Comments Auto', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Auto approve a comment in case existing approved comment exist for user or email."}' ],
			[ $config->id, 'comments_anonymous', 'Comments Anonymous', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Flag to allow Anonymous comments."}' ],
			[ $config->id, 'comments_filter','Comments Filter', FormField::TYPE_TEXTAREA, false, true, true, NULL, 0, NULL, '{"title":"Comments filter having comma seperated words to trash in case words match.","placeholder":"Comments filter"}' ],
			[ $config->id, 'comments_all_fields', 'Comments All Fields', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Show all fields while submitting the form."}' ],
			[ $config->id, 'comments_labels', 'Comments Labels', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Show field labels while submitting the form."}' ],
			[ $config->id, 'comments_disqus', 'Comments DISQUS', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Enable DISQUS."}' ],
			[ $config->id, 'comments_disqus_forum','Comments DISQUS Forum', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"The forum id provided by DISQUS.","placeholder":"Forum Id"}' ]
		];

		$this->batchInsert( $this->prefix . 'core_form_field', $columns, $fields );
	}

	private function insertBackendConfig() {

		$this->insert( $this->prefix . 'core_form', [
			'siteId' => $this->site->id,
			'createdBy' => $this->master->id, 'modifiedBy' => $this->master->id,
			'name' => 'Config Backend', 'slug' => 'config-backend',
			'type' => CoreGlobal::TYPE_SYSTEM,
			'description' => 'Backend configuration form.',
			'success' => 'All configurations saved successfully.',
			'captcha' => false,
			'visibility' => Form::VISIBILITY_PROTECTED,
			'status' => Form::STATUS_ACTIVE, 'userMail' => false,'adminMail' => false,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		]);

		$config	= Form::findBySlugType( 'config-backend', CoreGlobal::TYPE_SYSTEM );

		$columns = [ 'formId', 'name', 'label', 'type', 'compress', 'meta', 'active', 'validators', 'order', 'icon', 'htmlOptions' ];

		$fields	= [
			[ $config->id, 'cmg_powered', 'CMG Powered', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Show Powered by CMSGears on login screen."}' ],
			[ $config->id, 'default_avatar', 'Default Avatar', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Default avatar for site elements."}' ],
			[ $config->id, 'user_avatar', 'User Avatar', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Default avatar for user."}' ],
			[ $config->id, 'mail_avatar', 'Mail Avatar', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Default avatar for mail."}' ],
			[ $config->id, 'default_banner', 'Default Banner', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Default banner for site elements."}' ],
			[ $config->id, 'page_banner', 'Page Banner', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Page banner for site elements."}' ],
			[ $config->id, 'mail_banner', 'Mail Banner', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Mail banner for site mails."}' ]
		];

		$this->batchInsert( $this->prefix . 'core_form_field', $columns, $fields );
	}

	private function insertFrontendConfig() {

		$this->insert( $this->prefix . 'core_form', [
			'siteId' => $this->site->id,
			'createdBy' => $this->master->id, 'modifiedBy' => $this->master->id,
			'name' => 'Config Frontend', 'slug' => 'config-frontend',
			'type' => CoreGlobal::TYPE_SYSTEM,
			'description' => 'Frontend configuration form.',
			'success' => 'All configurations saved successfully.',
			'captcha' => false,
			'visibility' => Form::VISIBILITY_PROTECTED,
			'status' => Form::STATUS_ACTIVE, 'userMail' => false,'adminMail' => false,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		]);

		$config	= Form::findBySlugType( 'config-frontend', CoreGlobal::TYPE_SYSTEM );

		$columns = [ 'formId', 'name', 'label', 'type', 'compress', 'meta', 'active', 'validators', 'order', 'icon', 'htmlOptions' ];

		$fields	= [
			[ $config->id, 'default_avatar', 'Default Avatar', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Site avatar for site elements."}' ],
			[ $config->id, 'user_avatar', 'User Avatar', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"User avatar for user elements."}' ],
			[ $config->id, 'mail_avatar', 'Mail Avatar', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Default avatar for mail."}' ],
			[ $config->id, 'default_banner', 'Default Banner', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Site banner for site elements."}' ],
			[ $config->id, 'page_banner', 'Page Banner', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Page banner for site elements."}' ],
			[ $config->id, 'mail_banner', 'Mail Banner', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Mail banner for site mails."}' ]
		];

		$this->batchInsert( $this->prefix . 'core_form_field', $columns, $fields );
	}

	private function insertDefaultConfig() {

		$defaultSite	= Yii::$app->migration->getDefaultSite();
		$defaultAdmin	= Yii::$app->migration->getDefaultAdmin();

		$primaryDomain	= $this->primaryDomain;
		$siteMaster		= $this->siteMaster;
		$siteContact	= Yii::$app->migration->getSiteContact();
		$siteInfo		= Yii::$app->migration->getSiteInfo();

		$timezone = Yii::$app->migration->getTimezone();

		$columns = [ 'modelId', 'name', 'label', 'type', 'active', 'valueType', 'value', 'data' ];

		$metas = [
			[ $this->site->id, 'locale_message', 'Locale Message', 'core', 1, 'flag', '0',NULL ],
			[ $this->site->id, 'language', 'Language', 'core', 1, 'text', 'en-US',NULL ],
			[ $this->site->id, 'locale', 'Locale', 'core', 1, 'text', 'en_US',NULL ],
			[ $this->site->id, 'charset', 'Charset', 'core', 1, 'text', 'UTF-8',NULL ],
			[ $this->site->id, 'site_title', 'Site Title', 'core', 1, 'text', $this->siteTitle,NULL ],
			[ $this->site->id, 'site_name','Site Name','core', 1, 'text', $this->siteName,NULL ],
			[ $this->site->id, 'site_url', 'Site Url', 'core', 1, 'text', $defaultSite,NULL ],
			[ $this->site->id, 'admin_url', 'Admin Url', 'core', 1, 'text', $defaultAdmin,NULL ],
			[ $this->site->id, 'resource_url', 'Resource Url', 'core', 1, 'text', $defaultSite,NULL ],
			[ $this->site->id, 'registration','Registration','core', 1, 'flag','1',NULL ],
			[ $this->site->id, 'login','Login','core', 1, 'flag','1',NULL ],
			[ $this->site->id, 'change_email','Change Email','core', 1, 'flag','1',NULL ],
			[ $this->site->id, 'change_username','Change Username','core', 1, 'flag','1',NULL ],
			[ $this->site->id, 'change_mobile','Change Mobile','core', 1, 'flag','1',NULL ],
			[ $this->site->id, 'date_format','Date Format','core', 1, 'text','yyyy-MM-dd',NULL ],
			[ $this->site->id, 'time_format','Time Format','core', 1, 'text','HH:mm:ss',NULL ],
			[ $this->site->id, 'date_time_format','Date Time Format','core', 1, 'text','yyyy-MM-dd HH:mm:ss',NULL ],
			[ $this->site->id, 'timezone','Timezone','core', 1, 'text', $timezone,NULL ],
			[ $this->site->id, 'auto_login','Auto Login','core', 1, 'flag','0',NULL ],
			[ $this->site->id, 'auto_load','Auto Load','core', 1, 'flag','0',NULL ],
			[ $this->site->id, 'caching','Caching','cache', 1, 'flag','0',NULL ],
			[ $this->site->id, 'cache_duration','Cache Type','cache', 1, 'text',NULL,NULL ],
			[ $this->site->id, 'default_cache','Default Cache','cache', 1, 'text',NULL,NULL ],
			[ $this->site->id, 'primary_cache','Primary Cache','cache', 1, 'text',NULL,NULL ],
			[ $this->site->id, 'secondary_cache','Secondary Cache','cache', 1, 'text',NULL,NULL ],
			[ $this->site->id, 'smtp','SMTP','mail', 1, 'flag','0',NULL ],
			[ $this->site->id, 'smtp_username','SMTP Username','mail', 1, 'text','',NULL ],
			[ $this->site->id, 'smtp_password','SMTP Password','mail', 1, 'text','',NULL ],
			[ $this->site->id, 'smtp_host','SMTP Host','mail', 1, 'text','',NULL ],
			[ $this->site->id, 'smtp_port','SMTP Port','mail', 1, 'text','587',NULL ],
			[ $this->site->id, 'smtp_encryption','SMTP Encryption','mail', 1, 'text','tls',NULL ],
			[ $this->site->id, 'debug','Debug','mail', 1, 'flag','1',NULL ],
			[ $this->site->id, 'sender_name','Sender Name','mail', 1, 'text','Admin',NULL ],
			[ $this->site->id, 'sender_email','Sender Email','mail', 1, 'text', "$siteMaster@$primaryDomain",NULL ],
			[ $this->site->id, 'contact_name','Contact Name','mail', 1, 'text','Contact Us',NULL ],
			[ $this->site->id, 'contact_email','Contact Email','mail', 1, 'text', "$siteContact@$primaryDomain",NULL ],
			[ $this->site->id, 'info_name','Info Name','mail', 1, 'text','Info',NULL ],
			[ $this->site->id, 'info_email','Info Email','mail', 1, 'text',"$siteInfo@$primaryDomain",NULL ],
			[ $this->site->id, 'comments','Comments','comment', 1, 'flag','1',NULL ],
			[ $this->site->id, 'comments_user','Comments User','comment', 1, 'flag','0',NULL ],
			[ $this->site->id, 'comments_recent','Comments Recent','comment', 1, 'flag','0',NULL ],
			[ $this->site->id, 'comments_limit','Comments Limit','comment', 1, 'text', EntityService::PAGE_LIMIT,NULL ],
			[ $this->site->id, 'comments_email_admin','Comments Email Admin','comment', 1, 'flag','1',NULL ],
			[ $this->site->id, 'comments_email_user','Comments Email User','comment', 1, 'flag','1',NULL ],
			[ $this->site->id, 'comments_form_top','Comments Form Top','comment', 1, 'flag','1',NULL ],
			[ $this->site->id, 'comments_auto','Comments Auto','comment', 1, 'flag','1',NULL ],
			[ $this->site->id, 'comments_anonymous', 'Comments Anonymous','comment', 1, 'flag','0',NULL ],
			[ $this->site->id, 'comments_filter','Comments Filter','comment', 1, 'text', NULL,NULL ],
			[ $this->site->id, 'comments_all_fields','Comments All Fields','comment', 1, 'flag','0',NULL ],
			[ $this->site->id, 'comments_labels','Comments Labels','comment', 1, 'flag','0',NULL ],
			[ $this->site->id, 'comments_disqus','Comments DISQUS','comment', 1, 'flag','0',NULL ],
			[ $this->site->id, 'comments_disqus_forum','Comments DISQUS Forum','comment', 1, 'text', NULL,NULL ],
			[ $this->site->id, 'cmg_powered','CMG Powered','backend', 1, 'flag','1',NULL ],
			[ $this->site->id, 'default_avatar', 'Default Avatar','backend', 1, 'text', 'avatar-site.png',NULL ],
			[ $this->site->id, 'user_avatar','User Avatar','backend', 1, 'text', 'avatar-user.png',NULL ],
			[ $this->site->id, 'mail_avatar','Mail Avatar','backend', 1, 'text', 'avatar-mail.png',NULL ],
			[ $this->site->id, 'default_banner','Default Banner','backend', 1, 'text', 'banner-site.jpg',NULL ],
			[ $this->site->id, 'page_banner','Page Banner','backend', 1, 'text', 'banner-page.jpg',NULL ],
			[ $this->site->id, 'mail_banner','Mail Banner','backend', 1, 'text', 'banner-mail.jpg',NULL ],
			[ $this->site->id, 'default_avatar', 'Default Avatar','frontend', 1, 'text', 'avatar-site.png',NULL ],
			[ $this->site->id, 'user_avatar','User Avatar','frontend', 1, 'text', 'avatar-user.png',NULL ],
			[ $this->site->id, 'mail_avatar','User Avatar','frontend', 1, 'text', 'avatar-mail.png',NULL ],
			[ $this->site->id, 'default_banner','Default Banner','frontend', 1, 'text', 'banner-site.jpg',NULL ],
			[ $this->site->id, 'page_banner','Page Banner','frontend', 1, 'text', 'banner-page.jpg',NULL ],
			[ $this->site->id, 'mail_banner','Page Banner','frontend', 1, 'text', 'banner-mail.jpg',NULL ]
		];

		$this->batchInsert( $this->prefix . 'core_site_meta', $columns, $metas );
	}

	private function insertCategories() {

		$this->insert( $this->prefix . 'core_category', [
			'siteId' => $this->site->id,
			'createdBy' => $this->master->id, 'modifiedBy' => $this->master->id,
			'name' => 'Gender', 'slug' => 'gender',
			'type' => CoreGlobal::TYPE_OPTION_GROUP, 'icon' => null,
			'description' => 'Gender category with available options.',
			'featured' => false,
			'lValue' => 1, 'rValue' => 2,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		]);

		$category = Category::findBySlugType( 'gender', CoreGlobal::TYPE_OPTION_GROUP );

		$columns = [ 'categoryId', 'name', 'value', 'icon' ];

		$options	= [
			[ $category->id, 'Male', 'male', null ],
			[ $category->id, 'Female', 'female', null ],
			[ $category->id, 'Other', 'other', null ]
		];

		$this->batchInsert( $this->prefix . 'core_option', $columns, $options );
	}

	private function insertNotificationTemplates() {

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'icon', 'type', 'description', 'active', 'renderer', 'fileRender', 'layout', 'layoutGroup', 'viewPath', 'createdAt', 'modifiedAt', 'content', 'data' ];

		$templates = [
			// Users
			[ $this->master->id, $this->master->id, 'New User', 'new-user', null, 'notification', 'Trigger Notification to Admin, when new user is registered.', true, 'twig', 0, null, false, null, DateUtil::getDateTime(), DateUtil::getDateTime(), 'A new user "{{ $model->name, $model->email }}" has been registered.', '{"config":{"admin":"1","user":"0","adminEmail":"0","userEmail":"0"}}' ]
		];

		$this->batchInsert( $this->prefix . 'core_template', $columns, $templates );
	}

	public function down() {

		echo "m160621_014408_core_data will be deleted with m160621_014408_core.\n";
	}

}
