<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;
use yii\base\NotSupportedException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\traits\MetaTrait;
use cmsgears\core\common\models\traits\FileTrait;
use cmsgears\core\common\models\traits\AddressTrait;

/**
 * User Entity - The primary class.
 *
 * @property integer $id
 * @property integer localeId
 * @property integer genderId
 * @property integer avatarId
 * @property short $status
 * @property string $email
 * @property string $username
 * @property string $passwordHash
 * @property string $firstName
 * @property string $lastName
 * @property string $dob
 * @property string $phone
 * @property boolean $newsletter
 * @property string verifyToken
 * @property string resetToken
 * @property string registeredAt
 * @property datetime lastLoginAt
 * @property datetime lastActivityAt
 * @property string authKey 
 * @property string accessToken
 * @property datetime accessTokenCreatedAt
 * @property datetime accessTokenAccessedAt
 */
class User extends CmgEntity implements IdentityInterface {

	/**
	 * The status types available for a User by default.
	 *  
	 * 1. new - assigned for newly registered User.
	 * 2. active - It will be set when user confirm their account or admin activate the account.
	 * 3. blocked - It can be set by admin to block a particular user on false behaviour. 
	 */
	const STATUS_NEW		=    0;
	const STATUS_ACTIVE		=  500;
	const STATUS_BLOCKED	= 1000;

	/**
	 * The status map having string form of status.
	 */
	public static $statusMap = [
		self::STATUS_NEW => "New",
		self::STATUS_ACTIVE => "Active",
		self::STATUS_BLOCKED => "Blocked"
	];

	/**
	 * The status map having string form of status available for admin to make status change.
	 */
	public static $statusMapUpdate = [
		self::STATUS_ACTIVE => "Active",
		self::STATUS_BLOCKED => "Blocked"
	];

	use MetaTrait;

	public $metaType	= CoreGlobal::TYPE_USER;

	use FileTrait;

	public $fileType	= CoreGlobal::TYPE_USER;

	use AddressTrait;

	public $addressType	= CoreGlobal::TYPE_USER;

	public $permissions	= [];

	public $newsletter;

	// Instance Methods --------------------------------------------

	/**
	 * @return Site Member - assigned to User.
	 */
	public function getSiteMember() {

    	return $this->hasOne( SiteMember::className(), [ 'userId' => 'id' ] );
	}

	/**
	 * @return Role - assigned to User.
	 */
	public function getRole() {

		$roleTable			= CoreTables::TABLE_ROLE;
		$siteTable 			= CoreTables::TABLE_SITE;
		$siteMemberTable	= CoreTables::TABLE_SITE_MEMBER;

		return Role::find()
					->leftJoin( $siteMemberTable, "$siteMemberTable.roleId = $roleTable.id" )
					->leftJoin( $siteTable, "$siteTable.id = $siteMemberTable.siteId" )
					->where( "$siteMemberTable.userId=:id AND $siteTable.name=:name", [ ':id' => $this->id, ':name' => Yii::$app->cmgCore->getSiteName() ] );
	}

	/**
	 * @return CmgFile - set for User avatar.
	 */
	public function getAvatar() {

		return $this->hasOne( CmgFile::className(), [ 'id' => 'avatarId' ] );
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
	 * @return string representation of status.
	 */
	public function getStatusStr() {

		return self::$statusMap[ $this->status ];
	}

	/**
	 * @return boolean whether user is new.
	 */
	public function isNew() {
		
		return $this->status == self::STATUS_NEW;		
	}

	/**
	 * @return boolean whether user is confirmed.
	 */
	public function isConfirmed() {
		
		return $this->status > User::STATUS_NEW;	
	}

	/**
	 * @return boolean whether user is active.
	 */
	public function isActive() {

		return $this->status == self::STATUS_ACTIVE;
	}

	/**
	 * @return boolean whether user is blocked by admin.
	 */
	public function isBlocked() {

		return $this->status == self::STATUS_BLOCKED;	
	}

	/**
	 * Generate and set user password using the yii security mechanism.
	 */
	public function generatePassword( $password ) {

		$this->passwordHash = Yii::$app->security->generatePasswordHash( $password );
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

			throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_ALLOWED ) );
		}
	}

	/**
	 * @return boolean whether a permission is assigned to the user.
	 */
	public function isPermitted( $permission ) {

		return in_array( $permission, $this->permissions );
	}

	// yii\web\IdentityInterface ----------

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

	// yii\base\Model ---------------------

    /**
     * @inheritdoc
     */
	public function rules() {

		$trim		= [];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'email', 'username', 'phone', 'firstName', 'lastName' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];
		}

        $rules = [
            [ [ 'email', 'username' ], 'required' ],
            [ [ 'id', 'localeId', 'genderId', 'avatarId', 'status', 'phone', 'newsletter' ], 'safe' ],
            [ 'email', 'email' ],
            [ 'email', 'validateEmailCreate', 'on' => [ 'create' ] ],
            [ 'email', 'validateEmailUpdate', 'on' => [ 'update' ] ],
            [ 'username', 'alphanumdotu' ],
            [ 'username', 'validateUsernameCreate', 'on' => [ 'create' ] ],
            [ 'username', 'validateUsernameUpdate', 'on' => [ 'update' ] ],
            [ [ 'firstName', 'lastName' ], 'alphanumspace' ],
            [ [ 'id', 'localeId', 'genderId', 'avatarId', 'status', 'newsletter' ], 'number', 'integerOnly' => true ],
            [ 'dob', 'date', 'format' => Yii::$app->formatter->dateFormat ],
            [ [ 'registeredAt', 'lastLoginAt', 'lastActivityAt', 'accessTokenCreatedAt', 'accessTokenAccessedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'localeId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_LOCALE ),
			'genderId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_GENDER ),
			'avatarId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_AVATAR ),
			'status' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_STATUS ),
			'email' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
			'username' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_USERNAME ),
			'firstName' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_FIRSTNAME ),
			'lastName' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_LASTNAME ),
			'dob' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DOB ),
			'phone' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PHONE ),
			'newsletter' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NEWSLETTER )
		];
	}

	// User -------------------------------

	/**
	 * Validates user email to ensure that only one user exist with the given email.
	 */
    public function validateEmailCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByEmail( $this->email ) ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EMAIL_EXIST ) );
            }
        }
    }

	/**
	 * Validates user email to ensure that only one user exist with the given email.
	 */
    public function validateEmailUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingUser = self::findByEmail( $this->email );

			if( $this->id != $existingUser->id && strcmp( $existingUser->email, $this->email ) == 0 ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EMAIL_EXIST ) );
			}
        }
    }

	/**
	 * Validates user email to ensure that only one user exist with the given username.
	 */
    public function validateUsernameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByUsername( $this->username ) ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_USERNAME_EXIST ) );
            }
        }
    }

	/**
	 * Validates user email to ensure that only one user exist with the given username.
	 */
    public function validateUsernameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingUser = self::findByUsername( $this->username );

			if( isset( $existingUser ) && $this->id != $existingUser->id && strcmp( $existingUser->username, $this->username ) == 0 ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_USERNAME_EXIST ) );
			}
        }
    }

	/** 
	 * Validates user password while login
	 */
	public function validatePassword( $password ) {

		return Yii::$app->getSecurity()->validatePassword( $password, $this->passwordHash );
	}

	/**
	 * @return user name from email in case it's not set by user
	 */
    public function getName() {

		$name	= $this->firstName . " " . $this->lastName;

		if( !isset( $name ) || strlen( $name ) <= 2 ) {

			$name	= $this->username;

			if( !isset( $name ) || strlen( $name ) <= 2 ) {

				$name	= preg_split( "/@/", $this->email );
				$name	= $name[0];
			}
		}

		return $name;
    }

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ----------------

	/**
	 * @return string - db table name
	 */
	public static function tableName() {

		return CoreTables::TABLE_USER;
	}

	// yii\web\IdentityInterface ----------

	/**
	 * The method finds the user identity using the given id and also loads the available permissions if rbac is enabled for the application.
	 * @param integer $id
	 * @return a valid user based on given id.
	 */
    public static function findIdentity( $id ) {

		// Find valid User
		$user 	= static::findById( $id );

		// Load User Permissions
		if( isset( $user ) ) {

			if( Yii::$app->cmgCore->isRbac() ) {

				$user->loadPermissions();
			}
		}
		else {

    		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
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
		
		if( Yii::$app->cmgCore->isApis() ) {

			// Find valid User
			$user 	= static::findByAccessToken( $token );
	
			// Load User Permissions
			if( isset( $user ) ) {
				
				if( Yii::$app->cmgCore->isRbac() ) {
					
					$user->loadPermissions();
				}
			}
			else {

        		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
			}

	        return $user;
		}

        throw new NotSupportedException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_APIS_DISABLED ) );
    }

	// User -------------------------------

	/**
	 * @return ActiveRecord - with site member and role.
	 */
	public static function findWithSiteMember() {

		return self::find()->joinWith( 'siteMember' )->joinWith( 'siteMember.site' )->joinWith( 'siteMember.role' );
	}

	/**
	 * @return ActiveRecord - with site member, role and permission. It works by specifying a filter for permission name.
	 */
	public static function findWithSiteMemberPermission() {

		return self::find()->joinWith( 'siteMember' )->joinWith( 'siteMember.site' )->joinWith( 'siteMember.role' )->joinWith( 'siteMember.role.permissions' );
	}

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
}

?>