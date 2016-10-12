<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

use yii\base\NotSupportedException;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\config\CoreProperties;

use cmsgears\core\common\models\interfaces\IApproval;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Option;
use cmsgears\core\common\models\mappers\SiteMember;

use cmsgears\core\common\models\traits\interfaces\ApprovalTrait;
use cmsgears\core\common\models\traits\resources\MetaTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\VisualTrait;
use cmsgears\core\common\models\traits\mappers\AddressTrait;
use cmsgears\core\common\models\traits\mappers\FileTrait;

/**
 * User Entity - The primary class.
 *
 * @property long $id
 * @property long $localeId
 * @property long $genderId
 * @property long $avatarId
 * @property short $status
 * @property string $email
 * @property string $username
 * @property string $passwordHash
 * @property string $firstName
 * @property string $lastName
 * @property string $dob
 * @property string $phone
 * @property string $avatarUrl
 * @property string $websiteUrl
 * @property string $verifyToken
 * @property string $resetToken
 * @property string $registeredAt
 * @property datetime $lastLoginAt
 * @property datetime $lastActivityAt
 * @property string $authKey
 * @property string $accessToken
 * @property datetime $tokenCreatedAt
 * @property datetime $tokenAccessedAt
 * @property string $content
 * @property string $data
 */
class User extends \cmsgears\core\common\models\base\Entity implements IdentityInterface, IApproval {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const STATUS_VERIFIED	= 100; // Used when user is required to submit registration application for approval process.

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $mParentType	= CoreGlobal::TYPE_USER;
	public $permissions = [];

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use AddressTrait;
	use ApprovalTrait;
	use MetaTrait;
	use DataTrait;
	use FileTrait;
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

		// model rules
		$rules = [
			[ [ 'email' ], 'required' ],
			[ [ 'id', 'phone', 'content', 'data' ], 'safe' ],

			[ 'email', 'email' ],
			[ 'email', 'validateEmailCreate', 'on' => [ 'create' ] ],
			[ 'email', 'validateEmailUpdate', 'on' => [ 'update', 'profile' ] ],
			[ 'email', 'validateEmailChange', 'on' => [ 'profile' ] ],

			[ 'username', 'alphanumdotu' ],
			[ 'username', 'validateUsernameCreate', 'on' => [ 'create' ] ],
			[ 'username', 'validateUsernameUpdate', 'on' => [ 'update', 'profile' ] ],
			[ 'username', 'validateUsernameChange', 'on' => [ 'profile' ] ],

			[ [ 'id', 'localeId', 'genderId', 'avatarId', 'status' ], 'number', 'integerOnly' => true ],
			[ [ 'avatarUrl', 'websiteUrl' ], 'url' ],
			[ [ 'email', 'username', 'passwordHash', 'firstName', 'lastName', 'phone', 'avatarUrl', 'websiteUrl', 'verifyToken', 'resetToken', 'authKey', 'accessToken' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'dob', 'date', 'format' => Yii::$app->formatter->dateFormat ],
			[ [ 'registeredAt', 'lastLoginAt', 'lastActivityAt', 'tokenCreatedAt', 'tokenAccessedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		// trim if required
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'email', 'username', 'phone', 'firstName', 'lastName', 'avatarUrl', 'websiteUrl' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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
			'status' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'email' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
			'username' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USERNAME ),
			'firstName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FIRSTNAME ),
			'lastName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LASTNAME ),
			'dob' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DOB ),
			'phone' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PHONE ),
			'avatarUrl' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AVATAR_URL ),
			'websiteUrl' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_WEBSITE )
		];
	}

	// yii\db\BaseActiveRecord

	public function beforeSave( $insert ) {

		if( parent::beforeSave( $insert ) ) {

			if( $this->genderId <= 0 ) {

				$this->genderId = null;
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
	 */
	public function validateEmailChange( $attribute, $params ) {

		if( !$this->hasErrors() ) {

			$properties		= CoreProperties::getInstance();
			$existingUser	= self::findById( $this->id );

			if( isset( $existingUser ) && strcmp( $existingUser->email, $this->email ) != 0 && !$properties->isChangeEmail() ) {

				$this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_CHANGE_EMAIL ) );
			}
		}
	}

	/**
	 * Validates user email to ensure that only one user exist with the given username.
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
	 */
	public function validateUsernameChange( $attribute, $params ) {

		if( !$this->hasErrors() ) {

			$properties		= CoreProperties::getInstance();
			$existingUser	= self::findById( $this->id );

			if( isset( $existingUser ) && strcmp( $existingUser->username, $this->username ) != 0  && !$properties->isChangeUsername() ) {

				$this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_CHANGE_USERNAME ) );
			}
		}
	}

	/**
	 * Validates user password while login
	 */
	public function validatePassword( $password ) {

		if( isset( $password ) && isset( $this->passwordHash ) ) {

			return Yii::$app->getSecurity()->validatePassword( $password, $this->passwordHash );
		}

		return false;
	}

	// User ----------------------------------

	/**
	 * @return array - SiteMember
	 */
	public function getSiteMembers() {

		return $this->hasMany( SiteMember::className(), [ "userId" => 'id' ] );
	}

	/**
	 * @return SiteMember
	 */
	public function getActiveSiteMember() {

		$site		= Yii::$app->core->site;

		return $this->hasOne( SiteMember::className(), [ "userId" => 'id' ] )->where( [ "siteId" => $site->id ] );
	}

	/**
	 * @return Role - assigned to User.
	 */
	public function getRole() {

		$roleTable			= CoreTables::TABLE_ROLE;
		$siteTable			= CoreTables::TABLE_SITE;
		$siteMemberTable	= CoreTables::TABLE_SITE_MEMBER;

		return Role::find()
					->leftJoin( $siteMemberTable, "$siteMemberTable.roleId = $roleTable.id" )
					->leftJoin( $siteTable, "$siteTable.id = $siteMemberTable.siteId" )
					->where( "$siteMemberTable.userId=:id AND $siteTable.slug=:slug", [ ':id' => $this->id, ':slug' => Yii::$app->core->getSiteSlug() ] );
	}

	/**
	 * @return Locale - assigned to User.
	 */
	public function getLocale() {

		return $this->hasOne( Locale::className(), [ 'id' => 'localeId' ] );
	}

	/**
	 * @return Option - from Category 'gender' assigned to user.
	 */
	public function getGender() {

		return $this->hasOne( Option::className(), [ 'id' => 'genderId' ] );
	}

	/**
	 * @return string representation of gender.
	 */
	public function getGenderStr() {

		$option = $this->gender;

		if( isset( $option ) ) {

			return $option->value;
		}

		return '';
	}

	/**
	 * @return user name from email in case it's not set by user
	 */
	public function getName() {

		$name	= $this->firstName . ' ' . $this->lastName;

		if( !isset( $name ) || strlen( $name ) <= 1 ) {

			$name	= $this->username;

			if( !isset( $name ) || strlen( $name ) <= 1 ) {

				$name	= preg_split( '/@/', $this->email );
				$name	= $name[ 0 ];
			}
		}

		return $name;
	}

	// Verify only if new or verified
	public function verify() {

		if( $this->status <= User::STATUS_VERIFIED ) {

			$this->status = User::STATUS_VERIFIED;
		}
	}

	public function isVerified( $strict = true ) {

		if( $strict ) {

			return $this->status == self::STATUS_VERIFIED;
		}

		return $this->status >= self::STATUS_VERIFIED;
	}

	/**
	 * Generate and set user password using the yii security mechanism.
	 */
	public function generatePassword( $password ) {

		$this->passwordHash = Yii::$app->security->generatePasswordHash( $password );
	}

	/**
	 * Generate and set user access token using the yii security mechanism.
	 */
	public function generateAccessToken() {

		$this->accessToken = Yii::$app->security->generateRandomString();
	}

	/**
	 * Generate and set user verification token using the yii security mechanism.
	 */
	public function generateVerifyToken() {

		$this->verifyToken = Yii::$app->security->generateRandomString();
	}

	/**
	 * unset user verification token on user verification.
	 */
	public function unsetVerifyToken() {

		$this->verifyToken = null;
	}

	public function isVerifyTokenValid( $token ) {

		return strcmp( $this->verifyToken, $token ) == 0;
	}

	/**
	 * Generate and set user password reset token using the yii security mechanism.
	 */
	public function generateResetToken() {

		$this->resetToken = Yii::$app->security->generateRandomString();
	}

	/**
	 * unset user reset token on user password change.
	 */
	public function unsetResetToken() {

		$this->resetToken = null;
	}

	public function isResetTokenValid( $token ) {

		return strcmp( $this->resetToken, $token ) == 0;
	}

	/**
	 * Generate and set user auth key using the yii security mechanism.
	 */
	public function generateAuthKey() {

		$this->authKey = Yii::$app->security->generateRandomString();
	}

	/**
	 * Load the permissions assigned to user.
	 */
	public function loadPermissions() {

		$role = $this->role;

		if( isset( $role ) ) {

			$this->permissions	= $role->getPermissionsSlugList();
		}
		else {

			throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_ALLOWED ) );
		}
	}

	/**
	 * @return boolean whether a permission is assigned to the user.
	 */
	public function isPermitted( $permission ) {

		return in_array( $permission, $this->permissions );
	}

	// Static Methods ----------------------------------------------

	// Yii interfaces ------------------------

	// yii\web\IdentityInterface

	/**
	 * The method finds the user identity using the given id and also loads the available permissions if rbac is enabled for the application.
	 * @param integer $id
	 * @return a valid user based on given id.
	 */
	public static function findIdentity( $id ) {

		// Find valid User
		$user	= static::findById( $id );

		// Load User Permissions
		if( isset( $user ) ) {

			if( Yii::$app->core->isRbac() ) {

				$user->loadPermissions();
			}
		}
		else {

			throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
		}

		return $user;
	}

	/**
	 * The method finds the user identity using the given access token and also loads the available permissions if rbac is enabled for the application.
	 * @param string $token
	 * @param string $type
	 * @return a valid user based on given token and type
	 */
	public static function findIdentityByAccessToken( $token, $type = null ) {

		if( Yii::$app->core->isApis() ) {

			// Find valid User
			$user	= static::findByAccessToken( $token );

			// Load User Permissions
			if( isset( $user ) ) {

				if( Yii::$app->core->isRbac() ) {

					$user->loadPermissions();
				}
			}
			else {

				throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
			}

			return $user;
		}

		throw new NotSupportedException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_APIS_DISABLED ) );
	}

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @return string - db table name
	 */
	public static function tableName() {

		return CoreTables::TABLE_USER;
	}

	// CMG parent classes --------------------

	// User ----------------------------------

	// Read - Query -----------

	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'avatar', 'role', 'locale', 'gender' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryWithRole( $config = [] ) {

		$config[ 'relations' ]	= [ 'avatar', 'role' ];

		return parent::queryWithAll( $config );
	}

	public static function queryWithLocale( $config = [] ) {

		$config[ 'relations' ]	= [ 'avatar', 'locale' ];

		return parent::queryWithAll( $config );
	}

	public static function queryWithGender( $config = [] ) {

		$config[ 'relations' ]	= [ 'avatar', 'gender' ];

		return parent::queryWithAll( $config );
	}

	public static function queryWithSiteMembers( $config = [] ) {

		$config[ 'relations' ]	= [ 'avatar', 'siteMembers', 'siteMembers.site', 'siteMembers.role' ];

		return parent::queryWithAll( $config );
	}

	public static function queryWithSiteMembersPermissions( $config = [] ) {

		$config[ 'relations' ]	= [ 'avatar', 'siteMembers', 'siteMembers.site', 'siteMembers.role', 'siteMembers.role.permissions' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	/**
	 * @param string $token
	 * @return User - by accessToken
	 */
	public static function findByAccessToken( $token ) {

		return self::find()->where( 'accessToken=:token', [ ':token' => $token ] )->one();
	}

	/**
	 * @param string $email
	 * @return User - by email
	 */
	public static function findByEmail( $email ) {

		return self::find()->where( 'email=:email', [ ':email' => $email ] )->one();
	}

	/**
	 * @param string $email
	 * @return User - check whether user exist by email
	 */
	public static function isExistByEmail( $email ) {

		$user = self::find()->where( 'email=:email', [ ':email' => $email ] )->one();

		return isset( $user );
	}

	/**
	 * @param string $username
	 * @return User - by username
	 */
	public static function findByUsername( $username ) {

		return self::find()->where( 'username=:username', [ ':username' => $username ] )->one();
	}

	/**
	 * @param string $username
	 * @return User - check whether user exist by username
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
