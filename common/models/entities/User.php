<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\Query;
use yii\web\IdentityInterface;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * User Entity - The primary class.
 *
 * @property integer $id
 * @property integer $roleId
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
 * @property string registeredOn
 * @property datetime lastLogin
 * @property datetime lastActivity
 * @property string accessToken
 * @property datetime accessTokenDate
 * @property string authKey
 */
class User extends CmgEntity implements IdentityInterface {
	
	/**
	 * The status types available for a User by default. 
	 * The status new is assigned for newly registered User.
	 * The status active will be set when user confirm their account or admin activate the account.
	 * The status blocked can be set by admin to block a particular user on false behaviour. 
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
	
	public $permissions	= [];

	// Instance Methods --------------------------------------------
	
	/**
	 * @return Role assigned to User.
	 */
	public function getRole() {

		return $this->hasOne( Role::className(), [ 'id' => 'roleId' ] );
	}

	/**
	 * @return CmgFile set for User avatar.
	 */
	public function getAvatar() {

		return $this->hasOne( CmgFile::className(), [ 'id' => 'avatarId' ] );
	}

	/**
	 * @return Locale assigned to User.
	 */
	public function getLocale() {
		
		return $this->hasOne( Locale::className(), [ 'id' => 'localeId' ] );
	}

	/**
	 * @return Option from Category 'gender' assigned to User.
	 */
	public function getGender() {

		return $this->hasOne( Option::className(), [ 'id' => 'genderId' ] );
	}
	
	/**
	 * @return string representation of gender.
	 */
	public function getGenderStr() {

		$option = $this->gender;
		$gender	= '';

		if( isset( $option ) ) {

			$gender = $option->value;
		}

		return $gender;
	}

	/**
	 * @return array - list of user meta
	 */
	public function getMetas() {

    	return $this->hasMany( UserMeta::className(), [ 'userId' => 'id' ] );
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
	public function setPassword( $password ) {

		$this->passwordHash = Yii::$app->security->generatePasswordHash( $password );
	}

	/**
	 * @return string representation of newsletter.
	 */
	public function getNewsletterStr() {

		return $this->newsletter ? "yes" : "no";
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

		$role				= $this->role;
		$this->permissions	= $role->getPermissionsNameList();
	}

	/**
	 * @return boolean whether a permission is assigned to the user.
	 */
	public function isPermitted( $permission ) {

		return in_array( $permission, $this->permissions );
	}

	// yii\web\IdentityInterface ----------

    public function getId() {

        return $this->id;
    }

    public function getAuthKey() {

        return $this->authKey;
    }

    public function validateAuthKey( $authKey ) {

		return $this->authKey === $authKey;
    }

	// yii\base\Model ---------------------

	public function rules() {

        return [
            [ [ 'email' ], 'required' ],
            [ [ 'id', 'roleId', 'localeId', 'genderId', 'avatarId', 'status', 'phone', 'newsletter' ], 'safe' ],
            [ [ 'roleId', 'username' ], 'required', 'on' => [ 'create', 'update' ] ],
            [ 'email', 'email' ],
            [ 'email', 'validateEmailCreate', 'on' => [ 'create' ] ],
            [ 'email', 'validateEmailUpdate', 'on' => [ 'update' ] ],
            [ 'username', 'alphanumdotu' ],
            [ 'username', 'validateUsernameCreate', 'on' => [ 'create' ] ],
            [ 'username', 'validateUsernameUpdate', 'on' => [ 'update' ] ],
            [ [ 'firstName', 'lastName' ], 'alphanumspace' ],
            [ 'dob', 'date', 'format'=>'yyyy-MM-dd' ],
            [ [ 'registeredAt', 'lastLogin', 'lastActivity', 'accessTokenCreatedAt', 'accessTokenAccessedAt' ], 'date', 'format' => 'yyyy-MM-dd HH:mm:ss' ]
        ];
    }

	public function attributeLabels() {

		return [
			'email' => 'Email',
			'roleId' => 'Role',
			'localeId' => 'Locale',
			'genderId' => 'Gender',
			'avatarId' => 'Avatar',
			'status' => 'Status',
			'username' => 'Username',
			'firstName' => 'First Name',
			'lastName' => 'Last Name',
			'dob' => 'Date of Birth',
			'phone' => 'Phone',
			'newsletter' => 'Newsletter'
		];
	}
	
	// User -------------------------------
	
	/**
	 * Validates user email to ensure that only one user exist with the given mail.
	 */
    public function validateEmailCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByEmail( $this->email ) ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EMAIL_EXIST ) );
            }
        }
    }

	/**
	 * Validates user email to ensure that only one user exist with the given mail.
	 */
    public function validateEmailUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingUser = self::findByEmail( $this->email );

			if( $this->id != $existingUser->id && strcmp( $existingUser->email, $this->email) == 0 ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EMAIL_EXIST ) );
			}
        }
    }

	/**
	 * Validates user email to ensure that only one user exist with the given username.
	 */
    public function validateUsernameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByUsername( $this->username ) ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_USERNAME_EXIST ) );
            }
        }
    }

	/**
	 * Validates user email to ensure that only one user exist with the given username.
	 */
    public function validateUsernameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingUser = self::findByUsername( $this->username );

			if( $this->id != $existingUser->id && strcmp( $existingUser->username, $this->username ) == 0 ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_USERNAME_EXIST ) );
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

		if( !isset( $name ) || strlen( $name ) <= 2 ) {

			$attributes	= $this->attributes();
			$name		= $attributes[ 'firstName' ] . " " . $attributes[ 'lastName' ];

			if( !isset( $name ) || strlen( $name ) <= 2 ) {

				$name	= $attributes[ 'username' ];

				if( !isset( $name ) || strlen( $name ) <= 2 ) {

					$name	= preg_split( "/@/", $attributes[ 'email' ] );
					$name	= $name[0];
				}
			}
		}

		return $name;
    }

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ----------------
	
	public static function tableName() {

		return CoreTables::TABLE_USER;
	}

	// yii\web\IdentityInterface ----------

	/**
	 * The method finds the user identity using the given id and also loads the available permissions if rbac is enabled for the application.
	 * @return a valid user based on given id.
	 */
    public static function findIdentity( $id ) {

		// Find valid User
		$user 	= static::findById( $id );

		// Load User Permissions
		if( isset( $user ) && Yii::$app->cmgCore->isRbac() ) {

			$user->loadPermissions();
		}

        return $user;
    }

    public static function findIdentityByAccessToken( $token, $type = null ) {
		
		if( Yii::$app->cmgCore->isApis() ) {

			// Find valid User
			$user 	= static::findByAccessToken( $token );
	
			// Load User Permissions
			if( isset( $user ) && Yii::$app->cmgCore->isRbac() ) {
	
				$user->loadPermissions();
			}

	        return $user;
		}

        throw new NotSupportedException( 'findIdentityByAccessToken is not implemented.' );
    }

	// User

	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByAccessToken( $token ) {

		return self::find()->where( 'accessToken=:token', [ ':token' => $token ] )->one();
	}

	public static function findByEmail( $email ) {

		return self::find()->where( 'email=:email', [ ':email' => $email ] )->one();
	}

	public static function isExistByEmail( $email ) {

		$user = self::find()->where( 'email=:email', [ ':email' => $email ] )->one();

		return isset( $user );
	}

	public static function findByUsername( $username ) {

		return self::find()->where( 'username=:username', [ ':username' => $username ] )->one();		
	}

	public static function isExistByUsername( $username ) {

		$user = self::find()->where( 'username=:username', [ ':username' => $username ] )->one();

		return isset( $user );
	}
}

?>