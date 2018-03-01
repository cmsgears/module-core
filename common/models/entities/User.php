<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\entities;

// Yii Imports
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

use yii\base\NotSupportedException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\config\CoreProperties;

use cmsgears\core\common\models\interfaces\base\IApproval;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\base\Entity;
use cmsgears\core\common\models\mappers\SiteMember;
use cmsgears\core\common\models\resources\Option;

use cmsgears\core\common\models\traits\base\ApprovalTrait;
use cmsgears\core\common\models\traits\mappers\AddressTrait;
use cmsgears\core\common\models\traits\mappers\FileTrait;
use cmsgears\core\common\models\traits\resources\ContentTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;
use cmsgears\core\common\models\traits\resources\MetaTrait;
use cmsgears\core\common\models\traits\resources\SocialLinkTrait;
use cmsgears\core\common\models\traits\resources\VisualTrait;

/**
 * User Entity - The primary class.
 *
 * @property integer $id
 * @property integer $localeId
 * @property integer $genderId
 * @property integer $avatarId
 * @property integer $bannerId
 * @property integer $templateId
 * @property integer $status
 * @property string $email
 * @property string $username
 * @property string $passwordHash
 * @property string $type
 * @property string $title
 * @property string $firstName
 * @property string $middleName
 * @property string $lastName
 * @property string $message
 * @property string $description
 * @property string $dob
 * @property string $mobile
 * @property string $phone
 * @property string $timeZone
 * @property string $avatarUrl
 * @property string $websiteUrl
 * @property string $verifyToken
 * @property string $resetToken
 * @property string $registeredAt
 * @property datetime $lastLoginAt
 * @property datetime $lastActivityAt
 * @property string $authKey
 * @property string $accessToken
 * @property string $accessTokenType
 * @property datetime $tokenCreatedAt
 * @property datetime $tokenAccessedAt
 * @property string $content
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class User extends Entity implements IdentityInterface, IApproval {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	/**
	 * It will be used when user need to go through admin approval process and required to submit
	 * registration application for approval in order to continue with the application.
	 */
	const STATUS_VERIFIED	= 100;

	const REG_TYPE_DEFAULT	= 0;
	const REG_TYPE_SNS 		= 1;

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $permissions = [];

	// Protected --------------

	// Private ----------------

	private $modelType	= CoreGlobal::TYPE_USER;

	// Traits ------------------------------------------------------

	use AddressTrait;
	use ApprovalTrait;
	use ContentTrait;
	use DataTrait;
	use FileTrait;
	use GridCacheTrait;
	use MetaTrait;
	use SocialLinkTrait;
	use VisualTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// yii\web\IdentityInterface

	/**
	 * @inheritdoc
	 */
	public function getId() {

		return $this->id;
	}

	/**
	 * @inheritdoc
	 */
	public function getAuthKey() {

		return $this->authKey;
	}

	/**
	 * @inheritdoc
	 */
	public function validateAuthKey( $authKey ) {

		return $this->authKey === $authKey;
	}

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	/**
	 * @inheritdoc
	 */
	public function rules() {

		// Model Rules
		$rules = [
			// Required, Safe
			[ 'email', 'required' ],
			[ [ 'id', 'content', 'data', 'gridCache' ], 'safe' ],
			// Email
			[ 'email', 'email' ],
			[ 'email', 'validateEmailCreate', 'on' => [ 'create' ] ],
			[ 'email', 'validateEmailUpdate', 'on' => [ 'update', 'profile' ] ],
			[ 'email', 'validateEmailChange', 'on' => [ 'profile' ] ],
			// Username
			[ 'username', 'alphanumdotu' ],
			[ 'username', 'validateUsernameCreate', 'on' => [ 'create' ] ],
			[ 'username', 'validateUsernameUpdate', 'on' => [ 'update', 'profile' ] ],
			[ 'username', 'validateUsernameChange', 'on' => [ 'profile' ] ],
			// Text Limit
			[ [ 'type', 'title' ], 'string', 'min' => 1, 'max' => Yii::$app->core->smallText ],
			[ 'accessTokenType', 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ [ 'username', 'passwordHash', 'mobile', 'phone', 'verifyToken', 'resetToken', 'authKey', 'accessToken' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ [ 'email', 'firstName', 'middleName', 'lastName', 'timeZone' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ [ 'message', 'avatarUrl', 'websiteUrl' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 1, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'localeId', 'genderId', 'templateId' ], 'number', 'integerOnly' => true, 'min' => 0, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'id', 'avatarId', 'bannerId', 'status' ], 'number', 'integerOnly' => true ],
			[ [ 'avatarUrl', 'websiteUrl' ], 'url' ],
			[ 'dob', 'date' ],
			[ 'gridCacheValid', 'boolean' ],
			[ [ 'registeredAt', 'lastLoginAt', 'lastActivityAt', 'tokenCreatedAt', 'tokenAccessedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'email', 'username', 'message', 'description', 'mobile', 'phone', 'firstName', 'middleName', 'lastName', 'avatarUrl', 'websiteUrl' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'localeId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LOCALE ),
			'genderId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GENDER ),
			'avatarId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AVATAR ),
			'bannerId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_BANNER ),
			'templateId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TEMPLATE ),
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'email' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
			'username' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USERNAME ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'firstName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FIRSTNAME ),
			'middleName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MIDDLENAME ),
			'lastName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LASTNAME ),
			'message' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MESSAGE ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'dob' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DOB ),
			'mobile' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MOBILE ),
			'phone' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PHONE ),
			'timeZone' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TIME_ZONE ),
			'avatarUrl' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AVATAR_URL ),
			'websiteUrl' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_WEBSITE ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
		];
	}

	// yii\db\BaseActiveRecord

	/**
	 * @inheritdoc
	 */
	public function beforeSave( $insert ) {

		if( parent::beforeSave( $insert ) ) {

			if( $this->localeId <= 0 ) {

				$this->localeId = null;
			}

			if( $this->genderId <= 0 ) {

				$this->genderId = null;
			}

			if( $this->timeZone <= 0 ) {

				$this->timeZone = null;
			}

			return true;
		}

		return false;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	/**
	 * Validates user email to ensure that only one user exist with the given email.
	 *
	 * @param type $attribute
	 * @param type $params
	 * @return void
	 */
	public function validateEmailCreate( $attribute, $params ) {

		if( !$this->hasErrors() ) {

			if( self::isExistByEmail( $this->email ) ) {

				$this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EMAIL_EXIST ) );
			}
		}
	}

	/**
	 * Validates user email to ensure that only one user exist with the given email.
	 *
	 * @param type $attribute
	 * @param type $params
	 * @return void
	 */
	public function validateEmailUpdate( $attribute, $params ) {

		if( !$this->hasErrors() ) {

			$existingUser = self::findByEmail( $this->email );

			if( isset( $existingUser ) && $this->id != $existingUser->id ) {

				$this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EMAIL_EXIST ) );
			}
		}
	}

	/**
	 * Validates user email to ensure that it does not allow to change while changing user profile.
	 *
	 * @param type $attribute
	 * @param type $params
	 * @return void
	 */
	public function validateEmailChange( $attribute, $params ) {

		if( !$this->hasErrors() ) {

			$properties		= CoreProperties::getInstance();
			$existingUser	= self::findById( $this->id );

			if( isset( $existingUser ) && $existingUser->email !== $this->email && !$properties->isChangeEmail() ) {

				$this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_CHANGE_EMAIL ) );
			}
		}
	}

	/**
	 * Validates user email to ensure that only one user exist with the given username.
	 *
	 * @param type $attribute
	 * @param type $params
	 * @return void
	 */
	public function validateUsernameCreate( $attribute, $params ) {

		if( !$this->hasErrors() ) {

			if( self::isExistByUsername( $this->username ) ) {

				$this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_USERNAME_EXIST ) );
			}
		}
	}

	/**
	 * Validates user email to ensure that only one user exist with the given username.
	 *
	 * @param type $attribute
	 * @param type $params
	 * @return void
	 */
	public function validateUsernameUpdate( $attribute, $params ) {

		if( !$this->hasErrors() ) {

			$existingUser = self::findByUsername( $this->username );

			if( isset( $existingUser ) && $this->id != $existingUser->id ) {

				$this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_USERNAME_EXIST ) );
			}
		}
	}

	/**
	 * Validates user email to ensure that it does not allow to change while changing user profile.
	 *
	 * @param type $attribute
	 * @param type $params
	 * @return void
	 */
	public function validateUsernameChange( $attribute, $params ) {

		if( !$this->hasErrors() ) {

			$properties		= CoreProperties::getInstance();
			$existingUser	= self::findById( $this->id );

			if( isset( $existingUser ) && $existingUser->username !== $this->username  && !$properties->isChangeUsername() ) {

				$this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_CHANGE_USERNAME ) );
			}
		}
	}

	/**
	 * Validates user password while login.
	 *
	 * @param type $password
	 * @return boolean
	 */
	public function validatePassword( $password ) {

		if( isset( $password ) && isset( $this->passwordHash ) ) {

			return Yii::$app->getSecurity()->validatePassword( $password, $this->passwordHash );
		}

		return false;
	}

	// User ----------------------------------

	/**
	 * Returns all the site member mappings.
	 *
	 * @return \cmsgears\core\common\models\mappers\SiteMember[]
	 */
	public function getSiteMembers() {

		return $this->hasMany( SiteMember::class, [ "userId" => 'id' ] );
	}

	/**
	 * Returns site member mapping of active site.
	 *
	 * It's useful in multi-site environment to get the member of current site.
	 *
	 * @return \cmsgears\core\common\models\mappers\SiteMember
	 */
	public function getActiveSiteMember() {

		$site		= Yii::$app->core->site;

		return $this->hasOne( SiteMember::class, [ "userId" => 'id' ] )->where( [ "siteId" => $site->id ] );
	}

	/**
	 * Returns role assigned to user for active site.
	 *
	 * @return Role Assigned to User.
	 */
	public function getRole() {

		$roleTable			= CoreTables::getTableName( CoreTables::TABLE_ROLE );
		$siteTable			= CoreTables::getTableName( CoreTables::TABLE_SITE );
		$siteMemberTable	= CoreTables::getTableName( CoreTables::TABLE_SITE_MEMBER );

		return Role::find()
			->leftJoin( $siteMemberTable, "$siteMemberTable.roleId = $roleTable.id" )
			->leftJoin( $siteTable, "$siteTable.id = $siteMemberTable.siteId" )
			->where( "$siteMemberTable.userId=:id AND $siteTable.slug=:slug", [ ':id' => $this->id, ':slug' => Yii::$app->core->getSiteSlug() ] )
			->one();
	}

	/**
	 * Returns locale selected by user.
	 *
	 * @return Locale
	 */
	public function getLocale() {

		return $this->hasOne( Locale::class, [ 'id' => 'localeId' ] );
	}

	/**
	 * Returns gender selected by user.
	 *
	 * @return \cmsgears\core\common\models\resources\Option From Category 'gender'.
	 */
	public function getGender() {

		return $this->hasOne( Option::class, [ 'id' => 'genderId' ] );
	}

	/**
	 * Returns string representation of gender.
	 *
	 * @return string
	 */
	public function getGenderStr() {

		$option = $this->gender;

		if( isset( $option ) ) {

			return $option->value;
		}

		return '';
	}

	/**
	 * Returns full name of user using first name, middle name and last name.
	 *
	 * @return string
	 */
	public function getFullName() {

		$name = $this->firstName;

		if( !empty( $this->middleName ) ) {

			$name = "$name $this->middleName";
		}

		if( !empty( $this->lastName ) ) {

			$name = "$name $this->lastName";
		}

		return $name;
	}

	/**
	 * Returns name of user using first name, middle name and last name.
	 *
	 * It returns username or user id of email in case name is not set for the user.
	 *
	 * @return string
	 */
	public function getName() {

		$name = $this->getFullName();

		if( empty( $name ) ) {

			$name	= $this->username;

			if( empty( $name ) ) {

				$name	= preg_split( '/@/', $this->email );
				$name	= $name[ 0 ];
			}
		}

		return $name;
	}

	/**
	 * Update user status to verified in case it's not done yet.
	 *
	 * @return void
	 */
	public function verify() {

		if( $this->status <= User::STATUS_VERIFIED ) {

			$this->status = User::STATUS_VERIFIED;
		}
	}

	/**
	 * Check whether user is verified.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isVerified( $strict = true ) {

		if( $strict ) {

			return $this->status == self::STATUS_VERIFIED;
		}

		return $this->status >= self::STATUS_VERIFIED;
	}

	/**
	 * Generate and set user password using Yii security component.
	 *
	 * @param string $password
	 * @return void
	 */
	public function generatePassword( $password ) {

		$this->passwordHash = Yii::$app->security->generatePasswordHash( $password );
	}

	/**
	 * Generate and set user access token using the yii security mechanism.
	 */

	/**
	 * Generate and set user access token using Yii security component.
	 *
	 * @return void
	 */
	public function generateAccessToken() {

		$this->accessToken = Yii::$app->security->generateRandomString();
	}

	/**
	 * Generate and set user verification token using Yii security component.
	 *
	 * @return void
	 */
	public function generateVerifyToken() {

		$this->verifyToken = Yii::$app->security->generateRandomString();
	}

	/**
	 * Unset verification token once user verify the account.
	 *
	 * @return void
	 */
	public function unsetVerifyToken() {

		$this->verifyToken = null;
	}

	/**
	 * Check whether verification token is valid.
	 *
	 * @param string $token
	 * @return boolean
	 */
	public function isVerifyTokenValid( $token ) {

		return $this->verifyToken === $token;
	}

	/**
	 * Generate and set password reset token using Yii security component.
	 *
	 * @return void
	 */
	public function generateResetToken() {

		$this->resetToken = Yii::$app->security->generateRandomString();
	}

	/**
	 * Unset reset token once user verify the account.
	 *
	 * @return void
	 */
	public function unsetResetToken() {

		$this->resetToken = null;
	}

	/**
	 * Check whether reset token is valid.
	 *
	 * @param string $token
	 * @return boolean
	 */
	public function isResetTokenValid( $token ) {

		return strcmp( $this->resetToken, $token ) == 0;
	}

	/**
	 * Generate and set authorization key using Yii security component.
	 *
	 * @return void
	 */
	public function generateAuthKey() {

		$this->authKey = Yii::$app->security->generateRandomString();
	}

	/**
	 * Load the permissions assigned to the user.
	 *
	 * The loaded permissions will be queried for access to specific features.
	 *
	 * @throws \yii\web\ForbiddenHttpException
	 * @return void
	 */
	public function loadPermissions() {

		$role = $this->role;

		if( isset( $role ) ) {

			$this->permissions = $role->getPermissionsSlugList();
		}
		else {

			throw new ForbiddenHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NO_ACCESS ) );
		}
	}

	/**
	 * Check whether given permission is assigned to the user.
	 *
	 * @param string $permission Permission slug
	 * @return boolean
	 */
	public function isPermitted( $permission ) {

		return in_array( $permission, $this->permissions );
	}

	// Static Methods ----------------------------------------------

	// Yii interfaces ------------------------

	// yii\web\IdentityInterface

	/**
	 * Finds user identity using the given id and also loads the available permissions if
	 * RBAC is enabled for the application.
	 *
	 * @param integer $id
	 * @throws \yii\web\NotFoundHttpException
	 * @return User based on given id.
	 */
	public static function findIdentity( $id ) {

		// Find valid User
		$user = static::findById( $id );

		// Load User Permissions
		if( isset( $user ) ) {

			if( Yii::$app->core->isRbac() ) {

				$user->loadPermissions();
			}

			return $user;
		}

		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	/**
	 * Finds user identity using the given access token and also loads the available
	 * permissions if RBAC is enabled for the application.
	 *
	 * @param string $token
	 * @param string $type
	 * @throws \yii\web\NotFoundHttpException
	 * @throws \yii\base\NotSupportedException
	 * @return a valid user based on given token and type
	 */
	public static function findIdentityByAccessToken( $token, $type = null ) {

		// TODO: Also check access token validity using apiValidDays config

		if( Yii::$app->core->isApis() ) {

			// Find valid User
			$user	= static::findByAccessToken( $token );

			// Load User Permissions
			if( isset( $user ) ) {

				if( Yii::$app->core->isRbac() ) {

					$user->loadPermissions();
				}

				return $user;
			}

			throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
		}

		throw new NotSupportedException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_APIS_DISABLED ) );
	}

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_USER );
	}

	// CMG parent classes --------------------

	// User ----------------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'avatar', 'role', 'locale', 'gender' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the user with role.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with role.
	 */
	public static function queryWithRole( $config = [] ) {

		$config[ 'relations' ]	= [ 'avatar', 'role' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the user with locale.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with locale.
	 */
	public static function queryWithLocale( $config = [] ) {

		$config[ 'relations' ]	= [ 'avatar', 'locale' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the user with gender.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with gender.
	 */
	public static function queryWithGender( $config = [] ) {

		$config[ 'relations' ]	= [ 'avatar', 'gender' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the user with site members.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with site members.
	 */
	public static function queryWithSiteMembers( $config = [] ) {

		$config[ 'relations' ]	= [ 'avatar', 'siteMembers', 'siteMembers.site', 'siteMembers.role' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the user with site members and permissions.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with site members and permissions.
	 */
	public static function queryWithSiteMembersPermissions( $config = [] ) {

		$config[ 'relations' ]	= [ 'avatar', 'siteMembers', 'siteMembers.site', 'siteMembers.role', 'siteMembers.role.permissions' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	/**
	 * Find and return the user by access token.
	 *
	 * @param string $token
	 * @return User
	 */
	public static function findByAccessToken( $token ) {

		return self::find()->where( 'accessToken=:token', [ ':token' => $token ] )->one();
	}

	/**
	 * Find and return the user by email.
	 *
	 * @param string $email
	 * @return User
	 */
	public static function findByEmail( $email ) {

		return self::find()->where( 'email=:email', [ ':email' => $email ] )->one();
	}

	/**
	 * Check whether user exist for given email.
	 *
	 * @param string $email
	 * @return boolean
	 */
	public static function isExistByEmail( $email ) {

		$user = self::find()->where( 'email=:email', [ ':email' => $email ] )->one();

		return isset( $user );
	}

	/**
	 * Find and return the user by username.
	 *
	 * @param string $username
	 * @return User
	 */
	public static function findByUsername( $username ) {

		return self::find()->where( 'username=:username', [ ':username' => $username ] )->one();
	}

	/**
	 * Check whether user exist for given username.
	 *
	 * @param string $username
	 * @return boolean
	 */
	public static function isExistByUsername( $username ) {

		$user = self::find()->where( 'username=:username', [ ':username' => $username ] )->one();

		return isset( $user );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}

User::$statusMap[ User::STATUS_VERIFIED ] = 'Verified';

User::$revStatusMap[ 'verified' ] = User::STATUS_VERIFIED;
