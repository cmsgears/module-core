<?php
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

use cmsgears\core\common\utilities\DateUtil;

class m160621_014408_core_data extends \yii\db\Migration {

	public $prefix;

	private $site;
	private $locale;

	private $master;

	private $siteName;
	private $siteTitle;

	private $primaryDomain;

	private $defaultSite;
	private $defaultAdmin;

	public function init() {

		$this->prefix		= 'cmg_';

		// Get the values via config
		$this->siteName		= Yii::$app->migration->getSiteName();
		$this->siteTitle	= Yii::$app->migration->getSiteTitle();

		$this->primaryDomain	= Yii::$app->migration->getPrimaryDomain();

		$this->defaultSite	= Yii::$app->migration->getDefaultSite();
		$this->defaultAdmin	= Yii::$app->migration->getDefaultAdmin();
	}

    public function up() {

		// Create main site
		$this->insertMainSite();
		$this->insertLocale();

		// Create default users
		$this->insertDefaultUsers();

		// Create RBAC and Site Members
		$this->insertRolePermission();
		$this->insertSiteMembers();

		// Create various config
		$this->insertCoreConfig();
		$this->insertMailConfig();
		$this->insertBackendConfig();
		$this->insertFrontendConfig();

		// Init default config
		$this->insertDefaultConfig();

		// Default Categories and Options
		$this->insertCategories();
    }

	private function insertMainSite() {

		$this->insert( $this->prefix . 'core_site', [
            'name' => CoreGlobal::SITE_MAIN, 'slug' => CoreGlobal::SITE_MAIN, 'order' => 0, 'active' => true
        ]);

		$this->site	= Site::findBySlug( CoreGlobal::SITE_MAIN );

		Yii::$app->core->setSite( $this->site );
	}

	private function insertLocale() {

		$this->insert( $this->prefix . 'core_locale', [
            'code' => 'en_US', 'name' =>'English US'
        ]);

		$this->locale = Locale::findByCode( 'en_US' );
	}

	private function insertDefaultUsers() {

		// Default password for all test users is test#123
		// Super Admin i.e. demomaster must change password on first login and remove other users if required.

		$columns = [ 'localeId', 'status', 'email', 'username', 'passwordHash', 'firstName', 'lastName', 'registeredAt', 'lastLoginAt', 'authKey' ];

		$users	= [
			[ $this->locale->id, User::STATUS_ACTIVE, "demomaster@$this->primaryDomain", 'demomaster', '$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W', 'demo', 'master', DateUtil::getDateTime(), DateUtil::getDateTime(), 'JuL37UBqGpjnA7kaPiRnlsiWRwbRvXx7' ],
			[ $this->locale->id, User::STATUS_ACTIVE, "demoadmin@$this->primaryDomain", 'demoadmin','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','admin', DateUtil::getDateTime(), DateUtil::getDateTime(), 'SQ1LLCWEPva4IKuQklILLGDpmUTGzq8E' ],
			[ $this->locale->id, User::STATUS_ACTIVE, "demouser@$this->primaryDomain", 'demouser','$2y$13$Ut5b2RskRpGA9Q0nKSO6Xe65eaBHdx/q8InO8Ln6Lt3HzOK4ECz8W','demo','user', DateUtil::getDateTime(), DateUtil::getDateTime(), '-jG5ExHS0Y39ucSxHhl3PZ4xmPsfvQFC' ]
		];

		$this->batchInsert( $this->prefix . 'core_user', $columns, $users );

		$this->master	= User::findByUsername( 'demomaster' );
	}

	private function insertRolePermission() {

		// Roles

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'homeUrl', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$roles = [
			[ $this->master->id, $this->master->id, 'Super Admin', 'super-admin', 'dashboard', CoreGlobal::TYPE_SYSTEM, null, 'The Super Admin have all the permisisons to perform operations on the admin site and website.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Admin','admin','dashboard', CoreGlobal::TYPE_SYSTEM, null, 'The Admin have all the permisisons to perform operations on the admin site and website except RBAC module.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'User', 'user', null, CoreGlobal::TYPE_SYSTEM, null, 'The role User is limited to website users.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'User Manager', 'user-manager', 'dashboard', CoreGlobal::TYPE_SYSTEM, null, 'The role User Manager is limited to manage site users from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
		];

		$this->batchInsert( $this->prefix . 'core_role', $columns, $roles );

		$superAdminRole		= Role::findBySlugType( 'super-admin', CoreGlobal::TYPE_SYSTEM );
		$adminRole			= Role::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$userRole			= Role::findBySlugType( 'user', CoreGlobal::TYPE_SYSTEM );
		$userManagerRole	= Role::findBySlugType( 'user-manager', CoreGlobal::TYPE_SYSTEM );

		// Permissions

		$columns = [ 'createdBy', 'modifiedBy', 'name', 'slug', 'type', 'icon', 'description', 'createdAt', 'modifiedAt' ];

		$permissions = [
			[ $this->master->id, $this->master->id, 'Admin', 'admin', CoreGlobal::TYPE_SYSTEM, NULL, 'The permission admin is to distinguish between admin and site user. It is a must have permission for admins.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'User', 'user', CoreGlobal::TYPE_SYSTEM, NULL, 'The permission user is to distinguish between admin and site user. It is a must have permission for users.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Core', 'core', CoreGlobal::TYPE_SYSTEM, NULL, 'The permission core is to manage settings, drop downs, world countries, galleries and newsletters from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'Identity', 'identity', CoreGlobal::TYPE_SYSTEM, NULL, 'The permission identity is to manage users from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->master->id, $this->master->id, 'RBAC', 'rbac', CoreGlobal::TYPE_SYSTEM, NULL, 'The permission rbac is to manage roles and permissions from admin.', DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_permission', $columns, $permissions );

		$adminPerm			= Permission::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$userPerm			= Permission::findBySlugType( 'user', CoreGlobal::TYPE_SYSTEM );
		$corePerm			= Permission::findBySlugType( 'core', CoreGlobal::TYPE_SYSTEM );
		$identityPerm		= Permission::findBySlugType( 'identity', CoreGlobal::TYPE_SYSTEM );
		$rbacPerm			= Permission::findBySlugType( 'rbac', CoreGlobal::TYPE_SYSTEM );

		// RBAC Mapping

		$columns = [ 'roleId', 'permissionId' ];

		$mappings = [
			[ $superAdminRole->id, $adminPerm->id ], [ $superAdminRole->id, $userPerm->id ], [ $superAdminRole->id, $corePerm->id ], [ $superAdminRole->id, $identityPerm->id ], [ $superAdminRole->id, $rbacPerm->id ],
			[ $adminRole->id, $adminPerm->id ], [ $adminRole->id, $userPerm->id ], [ $adminRole->id, $corePerm->id ],
			[ $userRole->id, $userPerm->id ],
			[ $userManagerRole->id, $adminPerm->id ], [ $userManagerRole->id, $userPerm->id ], [ $userManagerRole->id, $identityPerm->id ]
		];

		$this->batchInsert( $this->prefix . 'core_role_permission', $columns, $mappings );
	}

	private function insertSiteMembers() {

		$superAdminRole		= Role::findBySlugType( 'super-admin', CoreGlobal::TYPE_SYSTEM );
		$adminRole			= Role::findBySlugType( 'admin', CoreGlobal::TYPE_SYSTEM );
		$userRole			= Role::findBySlugType( 'user', CoreGlobal::TYPE_SYSTEM );

		$master	= User::findByUsername( 'demomaster' );
		$admin	= User::findByUsername( 'demoadmin' );
		$user	= User::findByUsername( 'demouser' );

		$columns = [ 'siteId', 'userId', 'roleId', 'createdAt', 'modifiedAt' ];

		$roles = [
			[ $this->site->id, $master->id, $superAdminRole->id, DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->site->id, $admin->id, $adminRole->id, DateUtil::getDateTime(), DateUtil::getDateTime() ],
			[ $this->site->id, $user->id, $userRole->id, DateUtil::getDateTime(), DateUtil::getDateTime() ]
		];

		$this->batchInsert( $this->prefix . 'core_site_member', $columns, $roles );
	}

	private function insertCoreConfig() {

		$this->insert( $this->prefix . 'core_form', [
            'siteId' => $this->site->id,
            'createdBy' => $this->master->id, 'modifiedBy' => $this->master->id,
            'name' => 'Config Core', 'slug' => 'config-core',
            'type' => CoreGlobal::TYPE_SYSTEM,
            'description' => 'Core configuration form.',
            'successMessage' => 'All configurations saved successfully.',
            'captcha' => false,
            'visibility' => Form::VISIBILITY_PROTECTED,
            'active' => true, 'userMail' => false,'adminMail' => false,
            'createdAt' => DateUtil::getDateTime(),
            'modifiedAt' => DateUtil::getDateTime()
        ]);

		$config	= Form::findBySlug( 'config-core' );

		$columns = [ 'formId', 'name', 'label', 'type', 'compress', 'validators', 'order', 'icon', 'htmlOptions' ];

		$fields	= [
			[ $config->id, 'locale_message', 'Locale Message', FormField::TYPE_TOGGLE, false, 'required', 0, NULL, '{\"title\":\"Check for i18n support.\"}' ],
			[ $config->id, 'language','Language', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Language used on html tag.\",\"placeholder\":\"Language\"}' ],
			[ $config->id, 'locale','Locale', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Site default locale.\",\"placeholder\":\"Locale\"}' ],
			[ $config->id, 'charset','Charset', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Charset used on html head meta.\",\"placeholder\":\"Charset\"}' ],
			[ $config->id, 'site_title','Site Title', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Site title used in forming page title.\",\"placeholder\":\"Site Title\"}' ],
			[ $config->id, 'site_name','Site Name', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Site name used on footers etc.\",\"placeholder\":\"Site Name\"}' ],
			[ $config->id, 'site_url','Frontend URL', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Frontend URL\",\"placeholder\":\"Frontend URL\"}' ],
			[ $config->id, 'admin_url','Backend URL', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Backend URL\",\"placeholder\":\"Backend URL\"}' ],
			[ $config->id, 'registration','Registration', FormField::TYPE_TOGGLE, false, 'required', 0, NULL, '{\"title\":\"Check whether site registration is allowed.\"}' ],
			[ $config->id, 'change_email','Change Email', FormField::TYPE_TOGGLE, false, 'required', 0, NULL, '{\"title\":\"Check whether email change is allowed for user profile.\"}' ],
			[ $config->id, 'change_username','Change Username', FormField::TYPE_TOGGLE, false, 'required', 0, NULL, '{\"title\":\"Check whether username change is allowed for user profile.\"}' ],
			[ $config->id, 'date_format','Date Format', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Date format used by the formatter.\",\"placeholder\":\"Date Format\"}' ],
			[ $config->id, 'time_format','Time Format', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Time format used by the formatter.\",\"placeholder\":\"Time Format\"}' ],
			[ $config->id, 'date_time_format','Date Time Format', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Date time format used by the formatter.\",\"placeholder\":\"Time Format\"}' ],
			[ $config->id, 'timezone','Timezone', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Time format used by the formatter.\",\"placeholder\":\"Time Format\"}' ]
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
            'successMessage' => 'All configurations saved successfully.',
            'captcha' => false,
            'visibility' => Form::VISIBILITY_PROTECTED,
            'active' => true, 'userMail' => false,'adminMail' => false,
            'createdAt' => DateUtil::getDateTime(),
            'modifiedAt' => DateUtil::getDateTime()
        ]);

		$config	= Form::findBySlug( 'config-mail' );

		$columns = [ 'formId', 'name', 'label', 'type', 'compress', 'validators', 'order', 'icon', 'htmlOptions' ];

		$fields	= [
			[ $config->id, 'smtp','SMTP', FormField::TYPE_TOGGLE, false, 'required', 0, NULL, '{\"title\":\"Check whether SMTP is required.\"}' ],
			[ $config->id, 'smtp_username','SMTP Username', FormField::TYPE_TEXT, false, NULL, 0, NULL , '{\"title\":\"SMTP username.\",\"placeholder\":\"SMTP Username\"}' ],
			[ $config->id, 'smtp_password','SMTP Password', FormField::TYPE_PASSWORD, false, NULL, 0, NULL, '{\"title\":\"SMTP password.\",\"placeholder\":\"SMTP Password\"}' ],
			[ $config->id, 'smtp_host','SMTP Host', FormField::TYPE_TEXT, false, NULL, 0, NULL, '{\"title\":\"SMTP host.\",\"placeholder\":\"SMTP Host\"}' ],
			[ $config->id, 'smtp_port','SMTP Port', FormField::TYPE_TEXT, false, NULL, 0, NULL, '{\"title\":\"SMTP port.\",\"placeholder\":\"SMTP Port\"}' ],
			[ $config->id, 'smtp_encryption','SMTP Encryption', FormField::TYPE_TEXT, false, NULL, 0, NULL, '{\"title\":\"SMTP encryption.\",\"placeholder\":\"SMTP Encryption\"}' ],
			[ $config->id, 'debug','SMTP Debug', FormField::TYPE_TOGGLE, false, 'required', 0, NULL, '{\"title\":\"Check whether SMTP debug is required.\"}' ],
			[ $config->id, 'sender_name','Sender Name', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Sender name.\",\"placeholder\":\"Sender Name\"}' ],
			[ $config->id, 'sender_email','Sender Email', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Sender email.\",\"placeholder\":\"Sender Email\"}' ],
			[ $config->id, 'contact_name','Contact Name', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Contact name.\",\"placeholder\":\"Contact Name\"}' ],
			[ $config->id, 'contact_email','Contact Email', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Contact email.\",\"placeholder\":\"Contact Email\"}' ],
			[ $config->id, 'info_name','Info Name', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Info name.\",\"placeholder\":\"Info Name\"}' ],
			[ $config->id, 'info_email','Info Email', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Info email.\",\"placeholder\":\"Info Email\"}' ]
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
            'successMessage' => 'All configurations saved successfully.',
            'captcha' => false,
            'visibility' => Form::VISIBILITY_PROTECTED,
            'active' => true, 'userMail' => false,'adminMail' => false,
            'createdAt' => DateUtil::getDateTime(),
            'modifiedAt' => DateUtil::getDateTime()
        ]);

		$config	= Form::findBySlug( 'config-backend' );

		$columns = [ 'formId', 'name', 'label', 'type', 'compress', 'validators', 'order', 'icon', 'htmlOptions' ];

		$fields	= [
			[ $config->id, 'theme', 'Theme', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Current theme.\",\"placeholder\":\"Theme\"}' ]
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
            'successMessage' => 'All configurations saved successfully.',
            'captcha' => false,
            'visibility' => Form::VISIBILITY_PROTECTED,
            'active' => true, 'userMail' => false,'adminMail' => false,
            'createdAt' => DateUtil::getDateTime(),
            'modifiedAt' => DateUtil::getDateTime()
        ]);

		$config	= Form::findBySlug( 'config-frontend' );

		$columns = [ 'formId', 'name', 'label', 'type', 'compress', 'validators', 'order', 'icon', 'htmlOptions' ];

		$fields	= [
			[ $config->id, 'theme', 'Theme', FormField::TYPE_TEXT, false, 'required', 0, NULL, '{\"title\":\"Current theme.\",\"placeholder\":\"Theme\"}' ]
		];

		$this->batchInsert( $this->prefix . 'core_form_field', $columns, $fields );
	}

	private function insertDefaultConfig() {

		$columns = [ 'modelId', 'name', 'label', 'type', 'valueType', 'value' ];

		$attributes	= [
			[ $this->site->id, 'locale_message', 'Locale Message', 'core', 'flag', '0' ],
			[ $this->site->id, 'language', 'Language', 'core', 'text', 'en-US' ],
			[ $this->site->id, 'locale', 'Locale', 'core', 'text', 'en_US'],
			[ $this->site->id, 'charset', 'Charset', 'core', 'text', 'UTF-8' ],
			[ $this->site->id, 'site_title', 'Site Title', 'core', 'text', $this->siteTitle ],
			[ $this->site->id, 'site_name','Site Name','core', 'text', $this->siteName ],
			[ $this->site->id, 'site_url', 'Site Url', 'core', 'text', $this->defaultSite ],
			[ $this->site->id, 'admin_url', 'Admin Url', 'core', 'text', $this->defaultAdmin ],
			[ $this->site->id, 'registration','Registration','core','flag','1' ],
			[ $this->site->id, 'change_email','Change Email','core','flag','1' ],
			[ $this->site->id, 'change_username','Change Username','core','flag','1' ],
			[ $this->site->id, 'date_format','Date Format','core','text','yyyy-MM-dd' ],
			[ $this->site->id, 'time_format','Time Format','core','text','HH:mm:ss' ],
			[ $this->site->id, 'date_time_format','Date Time Format','core','text','yyyy-MM-dd HH:mm:ss' ],
			[ $this->site->id, 'timezone','Timezone','core','text','UTC' ],
			[ $this->site->id, 'smtp','SMTP','mail','flag','0' ],
			[ $this->site->id, 'smtp_username','SMTP Username','mail','text','' ],
			[ $this->site->id, 'smtp_password','SMTP Password','mail','text','' ],
			[ $this->site->id, 'smtp_host','SMTP Host','mail','text','' ],
			[ $this->site->id, 'smtp_port','SMTP Port','mail','text','587' ],
			[ $this->site->id, 'smtp_encryption','SMTP Encryption','mail','text','tls' ],
			[ $this->site->id, 'debug','Debug','mail','flag','1' ],
			[ $this->site->id, 'sender_name','Sender Name','mail','text','Admin' ],
			[ $this->site->id, 'sender_email','Sender Email','mail','text', "demoadmin@$this->primaryDomain" ],
			[ $this->site->id, 'contact_name','Contact Name','mail','text','Contact Us' ],
			[ $this->site->id, 'contact_email','Contact Email','mail','text', "democontact@$this->primaryDomain" ],
			[ $this->site->id, 'info_name','Info Name','mail','text','Info' ],
			[ $this->site->id, 'info_email','Info Email','mail','text',"demoinfo@$this->primaryDomain" ]
		];

		$this->batchInsert( $this->prefix . 'core_site_attribute', $columns, $attributes );
	}

	private function insertCategories() {

		$this->insert( $this->prefix . 'core_category', [
            'siteId' => $this->site->id,
            'name' => 'Gender', 'slug' => 'gender',
            'type' => CoreGlobal::TYPE_OPTION_GROUP, 'icon' => null,
            'description' => 'Gender category with available options.',
            'featured' => false,
            'lValue' => 1, 'rValue' => 2
        ]);

		$category	= Category::findBySlugType( 'gender', CoreGlobal::TYPE_OPTION_GROUP );

		$columns = [ 'categoryId', 'name', 'value', 'icon' ];

		$options	= [
			[ $category->id, 'Male', 'Male', null ],
			[ $category->id, 'Female', 'Female', null ],
			[ $category->id, 'Other', 'Other', null ]
		];

		$this->batchInsert( $this->prefix . 'core_option', $columns, $options );
	}

    public function down() {

        echo "m160621_014408_core_data will be deleted with m160621_014408_core.\n";
    }
}

?>