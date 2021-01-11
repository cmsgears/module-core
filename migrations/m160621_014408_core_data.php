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
			'order' => 0, 'active' => true, 'primary' => true,
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
			[ $this->master->id, $this->master->id, 'Super Admin', CoreGlobal::ROLE_SUPER_ADMIN, 'dashboard', NULL, CoreGlobal::TYPE_SYSTEM, NULL, 'The Super Admin have all the permisisons to perform operations on the admin site and website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Admin', CoreGlobal::ROLE_ADMIN,'dashboard', NULL, CoreGlobal::TYPE_SYSTEM, NULL, 'The Admin have all the permisisons to perform operations on the admin site and website except RBAC module.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'User', CoreGlobal::ROLE_USER, NULL, NULL, CoreGlobal::TYPE_SYSTEM, NULL, 'The role User is limited to website users.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'User Admin', CoreGlobal::ROLE_USER_ADMIN, 'dashboard', NULL, CoreGlobal::TYPE_SYSTEM, NULL, 'The role User Admin is limited to manage site users from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_role', $columns, $roles );

		$superAdminRole	= Role::findBySlugType( CoreGlobal::ROLE_SUPER_ADMIN, CoreGlobal::TYPE_SYSTEM );
		$adminRole		= Role::findBySlugType( CoreGlobal::ROLE_ADMIN, CoreGlobal::TYPE_SYSTEM );
		$userRole		= Role::findBySlugType( CoreGlobal::ROLE_USER, CoreGlobal::TYPE_SYSTEM );
		$userAdminRole	= Role::findBySlugType( CoreGlobal::ROLE_USER_ADMIN, CoreGlobal::TYPE_SYSTEM );

		// Permissions

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			[ $this->master->id, $this->master->id, 'Admin', CoreGlobal::PERM_ADMIN, CoreGlobal::TYPE_SYSTEM, NULL, 'The permission admin is to distinguish between admin and app user. It is a must have permission for admins.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'User', CoreGlobal::PERM_USER, CoreGlobal::TYPE_SYSTEM, NULL, 'The permission user is to distinguish between admin and app user. It is a must have permission for app users.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Core', CoreGlobal::PERM_CORE, CoreGlobal::TYPE_SYSTEM, NULL, 'The permission core is to manage sites, themes, testimonials, countries, drop downs and settings from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Identity', CoreGlobal::PERM_IDENTITY, CoreGlobal::TYPE_SYSTEM, NULL, 'The permission identity is to manage users from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'RBAC', CoreGlobal::PERM_RBAC, CoreGlobal::TYPE_SYSTEM, NULL, 'The permission rbac is to manage roles and permissions from admin. It also need identity permission.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Settings', CoreGlobal::PERM_SETTINGS, CoreGlobal::TYPE_SYSTEM, NULL, 'The permission settings is to manage settings from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		$adminPerm		= Permission::findBySlugType( CoreGlobal::PERM_ADMIN, CoreGlobal::TYPE_SYSTEM );
		$userPerm		= Permission::findBySlugType( CoreGlobal::PERM_USER, CoreGlobal::TYPE_SYSTEM );
		$corePerm		= Permission::findBySlugType( CoreGlobal::PERM_CORE, CoreGlobal::TYPE_SYSTEM );
		$identityPerm	= Permission::findBySlugType( CoreGlobal::PERM_IDENTITY, CoreGlobal::TYPE_SYSTEM );
		$rbacPerm		= Permission::findBySlugType( CoreGlobal::PERM_RBAC, CoreGlobal::TYPE_SYSTEM );
		$settingsPerm	= Permission::findBySlugType( CoreGlobal::PERM_SETTINGS, CoreGlobal::TYPE_SYSTEM );

		// RBAC Mapping

		$columns = [ 'roleId', 'permissionId' ];

		$mappings = [
			[ $superAdminRole->id, $adminPerm->id ], [ $superAdminRole->id, $userPerm->id ], [ $superAdminRole->id, $corePerm->id ], [ $superAdminRole->id, $identityPerm->id ], [ $superAdminRole->id, $rbacPerm->id ], [ $superAdminRole->id, $settingsPerm->id ],
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
			[ $this->master->id, $this->master->id, 'File Admin', CoreGlobal::ROLE_FILE_ADMIN, 'dashboard', NULL, CoreGlobal::TYPE_SYSTEM, NULL, 'The role File Admin is limited to manage files from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_role', $columns, $roles );

		$superAdminRole		= Role::findBySlugType( CoreGlobal::ROLE_SUPER_ADMIN, CoreGlobal::TYPE_SYSTEM );
		$adminRole			= Role::findBySlugType( CoreGlobal::ROLE_ADMIN, CoreGlobal::TYPE_SYSTEM );
		$fileAdminRole		= Role::findBySlugType( CoreGlobal::ROLE_FILE_ADMIN, CoreGlobal::TYPE_SYSTEM );

		// Permissions
		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'group', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			// Admin Permissions - Hard Coded
			[ $this->master->id, $this->master->id, 'Admin Files', CoreGlobal::PERM_FILE_ADMIN, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission admin files allows user to administer files from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		// Admin
		$adminPerm		= Permission::findBySlugType( CoreGlobal::PERM_ADMIN, CoreGlobal::TYPE_SYSTEM );
		$userPerm		= Permission::findBySlugType( CoreGlobal::PERM_USER, CoreGlobal::TYPE_SYSTEM );
		$fileAdminPerm	= Permission::findBySlugType( CoreGlobal::PERM_FILE_ADMIN, CoreGlobal::TYPE_SYSTEM );

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
			[ $this->master->id, $this->master->id, 'Gallery Admin', CoreGlobal::ROLE_GALLERY_ADMIN, 'dashboard', NULL, CoreGlobal::TYPE_SYSTEM, NULL, 'The role Gallery Admin is limited to manage galleries from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_role', $columns, $roles );

		$superAdminRole		= Role::findBySlugType( CoreGlobal::ROLE_SUPER_ADMIN, CoreGlobal::TYPE_SYSTEM );
		$adminRole			= Role::findBySlugType( CoreGlobal::ROLE_ADMIN, CoreGlobal::TYPE_SYSTEM );
		$galleryAdminRole	= Role::findBySlugType( CoreGlobal::ROLE_GALLERY_ADMIN, CoreGlobal::TYPE_SYSTEM );

		// Permissions
		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'group', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			// Admin Permissions - Hard Coded
			[ $this->master->id, $this->master->id, 'Admin Galleries', CoreGlobal::PERM_GALLERY_ADMIN, CoreGlobal::TYPE_SYSTEM, NULL, false, 'The permission admin galleries allows user to administer galleries from admin. It also need admin files permission.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		// Admin
		$adminPerm			= Permission::findBySlugType( CoreGlobal::PERM_ADMIN, CoreGlobal::TYPE_SYSTEM );
		$userPerm			= Permission::findBySlugType( CoreGlobal::PERM_USER, CoreGlobal::TYPE_SYSTEM );
		$galleryAdminPerm	= Permission::findBySlugType( CoreGlobal::PERM_GALLERY_ADMIN, CoreGlobal::TYPE_SYSTEM );

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
			'name' => 'Config Core', 'slug' => 'config-' . CoreGlobal::CONFIG_CORE,
			'type' => CoreGlobal::TYPE_SYSTEM,
			'description' => 'Core configuration form.',
			'success' => 'All configurations saved successfully.',
			'captcha' => false,
			'visibility' => Form::VISIBILITY_PROTECTED,
			'status' => Form::STATUS_ACTIVE, 'userMail' => false,'adminMail' => false,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		]);

		$config	= Form::findBySlugType( 'config-' . CoreGlobal::CONFIG_CORE, CoreGlobal::TYPE_SYSTEM );

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
			'name' => 'Config Cache', 'slug' => 'config-' . CoreGlobal::CONFIG_CACHE,
			'type' => CoreGlobal::TYPE_SYSTEM,
			'description' => 'Cache configuration form.',
			'success' => 'All configurations saved successfully.',
			'captcha' => false,
			'visibility' => Form::VISIBILITY_PROTECTED,
			'status' => Form::STATUS_ACTIVE, 'userMail' => false,'adminMail' => false,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		]);

		$config	= Form::findBySlugType( 'config-' . CoreGlobal::CONFIG_CACHE, CoreGlobal::TYPE_SYSTEM );

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
			'name' => 'Config Mail', 'slug' => 'config-' . CoreGlobal::CONFIG_MAIL,
			'type' => CoreGlobal::TYPE_SYSTEM,
			'description' => 'Mail configuration form.',
			'success' => 'All configurations saved successfully.',
			'captcha' => false,
			'visibility' => Form::VISIBILITY_PROTECTED,
			'status' => Form::STATUS_ACTIVE, 'userMail' => false,'adminMail' => false,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		]);

		$config	= Form::findBySlugType( 'config-' . CoreGlobal::CONFIG_MAIL, CoreGlobal::TYPE_SYSTEM );

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
			'name' => 'Config Comment', 'slug' => 'config-' . CoreGlobal::CONFIG_COMMENT,
			'type' => CoreGlobal::TYPE_SYSTEM,
			'description' => 'Comment configuration form.',
			'success' => 'All configurations saved successfully.',
			'captcha' => false,
			'visibility' => Form::VISIBILITY_PROTECTED,
			'status' => Form::STATUS_ACTIVE, 'userMail' => false,'adminMail' => false,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		]);

		$config	= Form::findBySlugType( 'config-' . CoreGlobal::CONFIG_COMMENT, CoreGlobal::TYPE_SYSTEM );

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
			'name' => 'Config Backend', 'slug' => 'config-' . CoreGlobal::CONFIG_ADMIN,
			'type' => CoreGlobal::TYPE_SYSTEM,
			'description' => 'Backend configuration form.',
			'success' => 'All configurations saved successfully.',
			'captcha' => false,
			'visibility' => Form::VISIBILITY_PROTECTED,
			'status' => Form::STATUS_ACTIVE, 'userMail' => false,'adminMail' => false,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		]);

		$config	= Form::findBySlugType( 'config-' . CoreGlobal::CONFIG_ADMIN, CoreGlobal::TYPE_SYSTEM );

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
			'name' => 'Config Frontend', 'slug' => 'config-' . CoreGlobal::CONFIG_FRONTEND,
			'type' => CoreGlobal::TYPE_SYSTEM,
			'description' => 'Frontend configuration form.',
			'success' => 'All configurations saved successfully.',
			'captcha' => false,
			'visibility' => Form::VISIBILITY_PROTECTED,
			'status' => Form::STATUS_ACTIVE, 'userMail' => false,'adminMail' => false,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		]);

		$config	= Form::findBySlugType( 'config-' . CoreGlobal::CONFIG_FRONTEND, CoreGlobal::TYPE_SYSTEM );

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
			[ $this->site->id, 'locale_message', 'Locale Message', CoreGlobal::CONFIG_CORE, 1, 'flag', '0',NULL ],
			[ $this->site->id, 'language', 'Language', CoreGlobal::CONFIG_CORE, 1, 'text', 'en-US',NULL ],
			[ $this->site->id, 'locale', 'Locale', CoreGlobal::CONFIG_CORE, 1, 'text', 'en_US',NULL ],
			[ $this->site->id, 'charset', 'Charset', CoreGlobal::CONFIG_CORE, 1, 'text', 'UTF-8',NULL ],
			[ $this->site->id, 'site_title', 'Site Title', CoreGlobal::CONFIG_CORE, 1, 'text', $this->siteTitle,NULL ],
			[ $this->site->id, 'site_name','Site Name',CoreGlobal::CONFIG_CORE, 1, 'text', $this->siteName,NULL ],
			[ $this->site->id, 'site_url', 'Site Url', CoreGlobal::CONFIG_CORE, 1, 'text', $defaultSite,NULL ],
			[ $this->site->id, 'admin_url', 'Admin Url', CoreGlobal::CONFIG_CORE, 1, 'text', $defaultAdmin,NULL ],
			[ $this->site->id, 'resource_url', 'Resource Url', CoreGlobal::CONFIG_CORE, 1, 'text', $defaultSite,NULL ],
			[ $this->site->id, 'registration','Registration', CoreGlobal::CONFIG_CORE, 1, 'flag','1',NULL ],
			[ $this->site->id, 'login','Login', CoreGlobal::CONFIG_CORE, 1, 'flag','1',NULL ],
			[ $this->site->id, 'change_email','Change Email', CoreGlobal::CONFIG_CORE, 1, 'flag','1',NULL ],
			[ $this->site->id, 'change_username','Change Username', CoreGlobal::CONFIG_CORE, 1, 'flag','1',NULL ],
			[ $this->site->id, 'change_mobile','Change Mobile', CoreGlobal::CONFIG_CORE, 1, 'flag','1',NULL ],
			[ $this->site->id, 'date_format','Date Format', CoreGlobal::CONFIG_CORE, 1, 'text','yyyy-MM-dd',NULL ],
			[ $this->site->id, 'time_format','Time Format', CoreGlobal::CONFIG_CORE, 1, 'text','HH:mm:ss',NULL ],
			[ $this->site->id, 'date_time_format','Date Time Format', CoreGlobal::CONFIG_CORE, 1, 'text','yyyy-MM-dd HH:mm:ss',NULL ],
			[ $this->site->id, 'timezone','Timezone', CoreGlobal::CONFIG_CORE, 1, 'text', $timezone,NULL ],
			[ $this->site->id, 'auto_login','Auto Login', CoreGlobal::CONFIG_CORE, 1, 'flag','0',NULL ],
			[ $this->site->id, 'auto_load','Auto Load', CoreGlobal::CONFIG_CORE, 1, 'flag','0',NULL ],
			[ $this->site->id, 'caching','Caching', CoreGlobal::CONFIG_CACHE, 1, 'flag','0',NULL ],
			[ $this->site->id, 'cache_duration','Cache Type', CoreGlobal::CONFIG_CACHE, 1, 'text',NULL,NULL ],
			[ $this->site->id, 'default_cache','Default Cache', CoreGlobal::CONFIG_CACHE, 1, 'text',NULL,NULL ],
			[ $this->site->id, 'primary_cache','Primary Cache', CoreGlobal::CONFIG_CACHE, 1, 'text',NULL,NULL ],
			[ $this->site->id, 'secondary_cache','Secondary Cache', CoreGlobal::CONFIG_CACHE, 1, 'text',NULL,NULL ],
			[ $this->site->id, 'smtp','SMTP', CoreGlobal::CONFIG_MAIL, 1, 'flag','0',NULL ],
			[ $this->site->id, 'smtp_username','SMTP Username', CoreGlobal::CONFIG_MAIL, 1, 'text','',NULL ],
			[ $this->site->id, 'smtp_password','SMTP Password', CoreGlobal::CONFIG_MAIL, 1, 'text','',NULL ],
			[ $this->site->id, 'smtp_host','SMTP Host', CoreGlobal::CONFIG_MAIL, 1, 'text','',NULL ],
			[ $this->site->id, 'smtp_port','SMTP Port', CoreGlobal::CONFIG_MAIL, 1, 'text','587',NULL ],
			[ $this->site->id, 'smtp_encryption','SMTP Encryption', CoreGlobal::CONFIG_MAIL, 1, 'text','tls',NULL ],
			[ $this->site->id, 'debug','Debug', CoreGlobal::CONFIG_MAIL, 1, 'flag','1',NULL ],
			[ $this->site->id, 'sender_name','Sender Name', CoreGlobal::CONFIG_MAIL, 1, 'text','Admin',NULL ],
			[ $this->site->id, 'sender_email','Sender Email', CoreGlobal::CONFIG_MAIL, 1, 'text', "$siteMaster@$primaryDomain",NULL ],
			[ $this->site->id, 'contact_name','Contact Name', CoreGlobal::CONFIG_MAIL, 1, 'text','Contact Us',NULL ],
			[ $this->site->id, 'contact_email','Contact Email', CoreGlobal::CONFIG_MAIL, 1, 'text', "$siteContact@$primaryDomain",NULL ],
			[ $this->site->id, 'info_name','Info Name', CoreGlobal::CONFIG_MAIL, 1, 'text','Info',NULL ],
			[ $this->site->id, 'info_email','Info Email', CoreGlobal::CONFIG_MAIL, 1, 'text',"$siteInfo@$primaryDomain",NULL ],
			[ $this->site->id, 'comments','Comments', CoreGlobal::CONFIG_COMMENT, 1, 'flag','1',NULL ],
			[ $this->site->id, 'comments_user','Comments User', CoreGlobal::CONFIG_COMMENT, 1, 'flag','0',NULL ],
			[ $this->site->id, 'comments_recent','Comments Recent', CoreGlobal::CONFIG_COMMENT, 1, 'flag','0',NULL ],
			[ $this->site->id, 'comments_limit','Comments Limit', CoreGlobal::CONFIG_COMMENT, 1, 'text', EntityService::PAGE_LIMIT,NULL ],
			[ $this->site->id, 'comments_email_admin','Comments Email Admin', CoreGlobal::CONFIG_COMMENT, 1, 'flag','1',NULL ],
			[ $this->site->id, 'comments_email_user','Comments Email User', CoreGlobal::CONFIG_COMMENT, 1, 'flag','1',NULL ],
			[ $this->site->id, 'comments_form_top','Comments Form Top', CoreGlobal::CONFIG_COMMENT, 1, 'flag','1',NULL ],
			[ $this->site->id, 'comments_auto','Comments Auto', CoreGlobal::CONFIG_COMMENT, 1, 'flag','1',NULL ],
			[ $this->site->id, 'comments_anonymous', 'Comments Anonymous', CoreGlobal::CONFIG_COMMENT, 1, 'flag','0',NULL ],
			[ $this->site->id, 'comments_filter','Comments Filter', CoreGlobal::CONFIG_COMMENT, 1, 'text', NULL,NULL ],
			[ $this->site->id, 'comments_all_fields','Comments All Fields', CoreGlobal::CONFIG_COMMENT, 1, 'flag','0',NULL ],
			[ $this->site->id, 'comments_labels','Comments Labels', CoreGlobal::CONFIG_COMMENT, 1, 'flag','0',NULL ],
			[ $this->site->id, 'comments_disqus','Comments DISQUS', CoreGlobal::CONFIG_COMMENT, 1, 'flag','0',NULL ],
			[ $this->site->id, 'comments_disqus_forum','Comments DISQUS Forum', CoreGlobal::CONFIG_COMMENT, 1, 'text', NULL,NULL ],
			[ $this->site->id, 'cmg_powered','CMG Powered', CoreGlobal::CONFIG_ADMIN, 1, 'flag','1',NULL ],
			[ $this->site->id, 'default_avatar', 'Default Avatar', CoreGlobal::CONFIG_ADMIN, 1, 'text', 'avatar-site.png',NULL ],
			[ $this->site->id, 'user_avatar','User Avatar', CoreGlobal::CONFIG_ADMIN, 1, 'text', 'avatar-user.png',NULL ],
			[ $this->site->id, 'mail_avatar','Mail Avatar', CoreGlobal::CONFIG_ADMIN, 1, 'text', 'avatar-mail.png',NULL ],
			[ $this->site->id, 'default_banner','Default Banner', CoreGlobal::CONFIG_ADMIN, 1, 'text', 'banner-site.jpg',NULL ],
			[ $this->site->id, 'page_banner','Page Banner', CoreGlobal::CONFIG_ADMIN, 1, 'text', 'banner-page.jpg',NULL ],
			[ $this->site->id, 'mail_banner','Mail Banner', CoreGlobal::CONFIG_ADMIN, 1, 'text', 'banner-mail.jpg',NULL ],
			[ $this->site->id, 'default_avatar', 'Default Avatar', CoreGlobal::CONFIG_FRONTEND, 1, 'text', 'avatar-site.png',NULL ],
			[ $this->site->id, 'user_avatar','User Avatar', CoreGlobal::CONFIG_FRONTEND, 1, 'text', 'avatar-user.png',NULL ],
			[ $this->site->id, 'mail_avatar','User Avatar', CoreGlobal::CONFIG_FRONTEND, 1, 'text', 'avatar-mail.png',NULL ],
			[ $this->site->id, 'default_banner','Default Banner', CoreGlobal::CONFIG_FRONTEND, 1, 'text', 'banner-site.jpg',NULL ],
			[ $this->site->id, 'page_banner','Page Banner', CoreGlobal::CONFIG_FRONTEND, 1, 'text', 'banner-page.jpg',NULL ],
			[ $this->site->id, 'mail_banner','Page Banner', CoreGlobal::CONFIG_FRONTEND, 1, 'text', 'banner-mail.jpg',NULL ]
		];

		$this->batchInsert( $this->prefix . 'core_site_meta', $columns, $metas );
	}

	private function insertCategories() {

		$this->insert( $this->prefix . 'core_category', [
			'siteId' => $this->site->id,
			'createdBy' => $this->master->id, 'modifiedBy' => $this->master->id,
			'name' => 'Gender', 'slug' => CoreGlobal::CATEGORY_GENDER,
			'type' => CoreGlobal::TYPE_OPTION_GROUP, 'icon' => null,
			'description' => 'Gender category with available options.',
			'featured' => false,
			'lValue' => 1, 'rValue' => 2,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		]);

		$this->insert( $this->prefix . 'core_category', [
			'siteId' => $this->site->id,
			'createdBy' => $this->master->id, 'modifiedBy' => $this->master->id,
			'name' => 'Marital Status', 'slug' => CoreGlobal::CATEGORY_MARITAL,
			'type' => CoreGlobal::TYPE_OPTION_GROUP, 'icon' => null,
			'description' => 'Marital Status category with available options.',
			'featured' => false,
			'lValue' => 1, 'rValue' => 2,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		]);

		$this->insert( $this->prefix . 'core_category', [
			'siteId' => $this->site->id,
			'createdBy' => $this->master->id, 'modifiedBy' => $this->master->id,
			'name' => 'NOK Relation', 'slug' => CoreGlobal::CATEGORY_NOK_RELATION,
			'type' => CoreGlobal::TYPE_OPTION_GROUP, 'icon' => null,
			'description' => 'NOK Relationionship category with available options.',
			'featured' => false,
			'lValue' => 1, 'rValue' => 2,
			'createdAt' => DateUtil::getDateTime(),
			'modifiedAt' => DateUtil::getDateTime()
		]);

		$genderCategory		= Category::findBySlugType( CoreGlobal::CATEGORY_GENDER, CoreGlobal::TYPE_OPTION_GROUP );
		$maritalCategory	= Category::findBySlugType( CoreGlobal::CATEGORY_MARITAL, CoreGlobal::TYPE_OPTION_GROUP );
		$nokCategory		= Category::findBySlugType( CoreGlobal::CATEGORY_NOK_RELATION, CoreGlobal::TYPE_OPTION_GROUP );

		$columns = [ 'categoryId', 'name', 'value', 'icon', 'active', 'order' ];

		$options = [
			[ $genderCategory->id, 'Male', 'male', null, 1, 0 ],
			[ $genderCategory->id, 'Female', 'female', null, 1, 0 ],
			[ $genderCategory->id, 'Other', 'other', null, 1, 1 ],
			[ $maritalCategory->id, 'Married', 'married', null, 1, 0 ],
			[ $maritalCategory->id, 'Unmarried', 'unmarried', null, 1, 0 ],
			[ $maritalCategory->id, 'Other', 'other', null, 1, 1 ],
			[ $nokCategory->id, 'Brother', 'brother', null, 1, 0 ],
			[ $nokCategory->id, 'Daughter', 'sister', null, 1, 0 ],
			[ $nokCategory->id, 'Father', 'sister', null, 1, 0 ],
			[ $nokCategory->id, 'Mother', 'sister', null, 1, 0 ],
			[ $nokCategory->id, 'Sister', 'sister', null, 1, 0 ],
			[ $nokCategory->id, 'Son', 'sister', null, 1, 0 ],
			[ $nokCategory->id, 'Spouse', 'sister', null, 1, 0 ],
			[ $nokCategory->id, 'Other', 'other', null, 1, 1 ]
		];

		$this->batchInsert( $this->prefix . 'core_option', $columns, $options );
	}

	private function insertNotificationTemplates() {

		$master = User::findByUsername( $this->siteMaster );

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'icon', 'type', 'active', 'description', 'classPath', 'dataForm', 'renderer', 'fileRender', 'layout', 'layoutGroup', 'viewPath', 'view', 'createdAt', 'modifiedAt', 'htmlOptions', 'message', 'content', 'data' ];

		$templates = [
			// Users
			[ $master->id, $master->id, 'New User', CoreGlobal::TPL_NOTIFY_USER_NEW, null, 'notification', true, 'Trigger Notification to Admin, when new user is registered.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, 'User registered - <b>{{model.email}}</b>', 'A new user <b>{{model.name}}</b>, <b>{{model.email}}</b> has been registered.{% if config.link %} Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %} Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"1","user":"0","direct":"0","adminEmail":"0","userEmail":"0","directEmail":"0"}}' ],
			[ $master->id, $master->id, 'Role Changed', CoreGlobal::TPL_NOTIFY_USER_ROLE, null, 'notification', true, 'Trigger Notification to User, when role is changed while updating user.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, 'User role changed from <b>{{oldRole}}</b> to <b>{{newRole}}</b>', 'Your role has been changed from <b>{{oldRole}}</b> to <b>{{newRole}}</b>.', '{"config":{"admin":"0","user":"1","direct":"0","adminEmail":"0","userEmail":"1","directEmail":"0"}}' ],
			// Comments
			[ $master->id, $master->id, 'Comment New', CoreGlobal::TPL_COMMENT_STATUS_NEW, null, 'notification', true, 'A new Comment is submitted.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{commentType}} submitted', '<b>{{parentType}}</b> - <b>{{parent.displayName}}</b> - {{commentType}} - has been submitted. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"1","user":"0","direct":"1","adminEmail":"1","userEmail":"0","directEmail":"1"}}' ],
			[ $master->id, $master->id, 'Comment Spam', CoreGlobal::TPL_COMMENT_STATUS_SPAM, null, 'notification', true, 'Admin marked the comment as spam.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{commentType}} spammed', '<b>{{parentType}}</b> - <b>{{parent.displayName}}</b> - {{commentType}} - has been marked as spam. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"0","user":"1","direct":"1","adminEmail":"0","userEmail":"1","directEmail":"1"}}' ],
			[ $master->id, $master->id, 'Comment Approved', CoreGlobal::TPL_COMMENT_STATUS_APPROVE, null, 'notification', true, 'Admin marked the comment as approved.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{commentType}} approved', '<b>{{parentType}}</b> - <b>{{parent.displayName}}</b> - {{commentType}} - has been approved. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"0","user":"1","direct":"1","adminEmail":"0","userEmail":"1","directEmail":"1"}}' ],
			[ $master->id, $master->id, 'Comment Trash', CoreGlobal::TPL_COMMENT_STATUS_TRASH, null, 'notification', true, 'Admin marked the comment as trash.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{commentType}} trashed', '<b>{{parentType}}</b> - <b>{{parent.displayName}}</b> - {{commentType}} - has been marked as trash. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"0","user":"1","direct":"1","adminEmail":"0","userEmail":"1","directEmail":"1"}}' ],
			[ $master->id, $master->id, 'Comment Deleted', CoreGlobal::TPL_COMMENT_STATUS_DELETE, null, 'notification', true, 'Admin deleted the comment.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{commentType}} deleted', '<b>{{parentType}}</b> - <b>{{parent.displayName}}</b> - {{commentType}} - has been deleted. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"0","user":"1","direct":"1","adminEmail":"0","userEmail":"1","directEmail":"1"}}' ],
			[ $master->id, $master->id, 'Comment Request Spam', CoreGlobal::TPL_COMMENT_REQUEST_SPAM, null, 'notification', true, 'User submitted request to mark spam.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{commentType}} request spam', '<b>{{parentType}}</b> - <b>{{parent.displayName}}</b> - {{commentType}} - has been requested to mark as spam. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"1","user":"0","direct":"1","adminEmail":"1","userEmail":"0","directEmail":"1"}}' ],
			[ $master->id, $master->id, 'Comment Request Approve', CoreGlobal::TPL_COMMENT_REQUEST_APPROVE, null, 'notification', true, 'User submitted request to approve.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{commentType}} request approve', '<b>{{parentType}}</b> - <b>{{parent.displayName}}</b> - {{commentType}} - has been requested to approve. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"1","user":"0","direct":"1","adminEmail":"1","userEmail":"0","directEmail":"1"}}' ],
			[ $master->id, $master->id, 'Comment Request Delete', CoreGlobal::TPL_COMMENT_REQUEST_DELETE, null, 'notification', true, 'User submitted request to delete.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, '{{commentType}} request delete', '<b>{{parentType}}</b> - <b>{{parent.displayName}}</b> - {{commentType}} - has been requested to delete. {% if config.link %}Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %}Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"1","user":"0","direct":"1","adminEmail":"1","userEmail":"0","directEmail":"1"}}' ],
			// Categories and Options
			[ $master->id, $master->id, 'Category Suggest', CoreGlobal::TPL_NOTIFY_SUGGEST_CATEGORY, null, 'notification', true, 'Trigger Notification to Admin, when user suggest category.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, 'Category Suggested - <b>{{name}}</b>', 'A new category <b>{{name}}</b> for type <b>{{type}}</b> has been suggested by <b>{{model.name}}, {{model.email}}</b>.{% if config.link %} Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %} Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"1","user":"0","direct":"0","adminEmail":"1","userEmail":"0","directEmail":"0"}}' ],
			[ $master->id, $master->id, 'Option Suggest', CoreGlobal::TPL_NOTIFY_SUGGEST_OPTION, null, 'notification', true, 'Trigger Notification to Admin, when new user suggest category option.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, 'Option Suggested - <b>{{name}}</b>', 'A new option <b>{{name}}</b> for category <b>{{category.name}}</b> has been suggested by <b>{{model.name}}, {{model.email}}</b>.{% if config.link %} Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %} Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"1","user":"0","direct":"0","adminEmail":"1","userEmail":"0","directEmail":"0"}}' ],
			// Feedbacks and Testimonials
			[ $master->id, $master->id, 'Feedback', CoreGlobal::TPL_NOTIFY_FEEDBACK, null, 'notification', true, 'Trigger Notification to Admin, when user submits feedback.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, 'Feedback Submitted - <b>{{model.name}}</b>', 'A new feedback has been submitted by <b>{{model.name}}, {{model.email}}</b>.{% if config.link %} Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %} Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"1","user":"0","direct":"0","adminEmail":"1","userEmail":"0","directEmail":"0"}}' ],
			[ $master->id, $master->id, 'Testimonial', CoreGlobal::TPL_NOTIFY_TESTIMONIAL, null, 'notification', true, 'Trigger Notification to Admin, when user submits testimonial.', null, null, 'twig', false, null, false, null, null, DateUtil::getDateTime(), DateUtil::getDateTime(), null, 'Testimonial Submitted - <b>{{model.name}}</b>', 'A new testimonial has been submitted by <b>{{model.name}}, {{model.email}}</b>.{% if config.link %} Please follow this <a href="{{config.link}}">link</a>.{% endif %}{% if config.adminLink %} Please follow this <a href="{{config.adminLink}}">link</a>.{% endif %}', '{"config":{"admin":"1","user":"0","direct":"0","adminEmail":"1","userEmail":"0","directEmail":"0"}}' ]
		];

		$this->batchInsert( $this->prefix . 'core_template', $columns, $templates );
	}

	public function down() {

		echo "m160621_014408_core_data will be deleted with m160621_014408_core.\n";
	}

}
