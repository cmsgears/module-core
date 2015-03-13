<?php
namespace cmsgears\modules\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\web\IdentityInterface;

// CMG Imports
use cmsgears\modules\core\common\config\CoreGlobal;

use cmsgears\modules\core\common\utilities\MessageUtil;

class User extends ActiveRecord implements IdentityInterface {

	const STATUS_NEW		=  0;
	const STATUS_ACTIVE		= 10;
	const STATUS_BLOCKED	= 20;

	public static $statusMap = [
		self::STATUS_NEW => "New",
		self::STATUS_ACTIVE => "Active",
		self::STATUS_BLOCKED => "Blocked"
	];

	public static $statusMapUpdate = [
		self::STATUS_ACTIVE => "Active",
		self::STATUS_BLOCKED => "Blocked"
	];
	
	public $permissions	= [];

	// Instance Methods --------------------------------------------

	// db columns

	public function getRoleId() {
		
		return $this->user_role;
	}

	public function getRole() {

		return $this->hasOne( Role::className(), [ 'role_id' => 'user_role' ] );
	}

	public function setRoleId( $roleId ) {

		$this->user_role = $roleId;
	}

	public function getAvatarId() {

		return $this->user_avatar;
	}

	public function getAvatar() {

		return $this->hasOne( CmgFile::className(), [ 'file_id' => 'user_avatar' ] );
	}

	public function setAvatarId( $avatarId ) {

		$this->user_avatar = $avatarId;
	}

	public function getLocaleId() {
		
		return $this->user_locale;
	}

	public function getLocale() {
		
		return $this->hasOne( Locale::className(), [ 'locale_id' => 'user_locale' ] );
	}

	public function setLocaleId( $localeId ) {
		
		$this->user_locale = $localeId;
	}

	public function getGenderId() {
		
		return $this->user_gender;
	}

	public function getGender() {

		return $this->hasOne( Option::className(), [ 'option_id' => 'user_gender' ] );
	}

	public function getGenderStr() {

		$option = $this->hasOne( Option::className(), [ 'option_id' => 'user_gender' ] );
		$gender	= false;

		if( isset( $option ) ) {

			$gender = $option->value;
		}

		return $gender;
	}

	public function setGenderId( $genderId ) {

		$this->user_gender = $genderId;
	}

	public function getStatus() {

		return $this->user_status;
	}

	public function getStatusStr() {

		return self::$statusMap[ $this->user_status ];
	}

	public function setStatus( $status ) {

		$this->user_status = $status;
	}

	public function isNew() {
		
		return $this->user_status == self::STATUS_NEW;		
	}

	public function isConfirmed() {
		
		return $this->user_status > User::STATUS_NEW;	
	}

	public function isActive() {

		return $this->user_status == self::STATUS_ACTIVE;
	}

	public function isBlocked() {

		return $this->user_status == self::STATUS_BLOCKED;	
	}

	public function getEmail() {

		return $this->user_email;
	}

	public function setEmail( $email ) {

		$this->user_email = $email;
	}

	public function getPassword() {

		return $this->user_password_hash;
	}

	public function setPassword( $password ) {
		
		$this->user_password_hash = Yii::$app->security->generatePasswordHash( $password );
	}

	public function getUsername() {

		return $this->user_username;
	}

	public function setUsername( $username ) {

		$this->user_username = $username;
	}

	public function getFirstname() {

		return $this->user_firstname;
	}

	public function setFirstname( $firstname ) {

		$this->user_firstname = $firstname;
	}

	public function getLastname() {

		return $this->user_lastname;
	}

	public function setLastname( $lastname ) {

		$this->user_lastname = $lastname;
	}

	public function getDob() {

		return $this->user_dob;
	}

	public function setDob( $dob ) {

		$this->user_dob = $dob;
	}

	public function getPhone() {

		return $this->user_phone;
	}

	public function setPhone( $phone ) {

		$this->user_phone = $phone;
	}

	public function getNewsletter() {

		return $this->user_newsletter;
	}

	public function getNewsletterStr() {

		return $this->user_newsletter ? "yes" : "no";
	}

	public function setNewsletter( $newsletter ) {

		$this->user_newsletter = $newsletter;
	}

	public function getVerifyToken() {

		return $this->user_verify_token;
	}

    public function generateVerifyToken() {

        $this->user_verify_token = Yii::$app->getSecurity()->generateRandomString();
    }

    public function unsetVerifyToken() {

        $this->user_verify_token = null;
    }

	public function getResetToken() {

		return $this->user_reset_token;
	}

    public function generateResetToken() {

        $this->user_reset_token = Yii::$app->getSecurity()->generateRandomString();
    }

    public function unsetResetToken() {

        $this->user_reset_token = null;
    }

	public function getRegOn() {

		return $this->user_reg_on;
	}

	public function setRegOn( $regOn ) {

		$this->user_reg_on = $regOn;
	}

	public function getLastLogin() {

		return $this->user_last_login;
	}

	public function setLastLogin( $lastLogin ) {

		$this->user_last_login = $lastLogin;
	}

    public function getAccessToken() {

        return $this->user_access_token;
    }

    public function setAccessToken( $token ) {

        $this->user_access_token = $token;
    }

    public function getAccessTokenDate() {

        return $this->user_access_token_date;
    }

    public function setAccessTokenDate( $tokenDate ) {

        $this->user_access_token_date = $tokenDate;
    }

    public function generateAuthKey() {

        $this->user_auth_key = Yii::$app->getSecurity()->generateRandomString();
    }

	public function isPermitted( $permission ) {

		return in_array( $permission, $this->permissions );
	}

	// yii\web\IdentityInterface

    public function getId() {

        return $this->getPrimaryKey();
    }

    public function getAuthKey() {

        return $this->user_auth_key;
    }

    public function validateAuthKey( $authKey ) {

		return $this->getAuthKey() === $authKey;
    }

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'user_email' ], 'required' ],
            [ [ 'user_role', 'user_username' ], 'required', 'on' => [ 'create', 'update' ] ],
            [ 'user_email', 'email' ],
            [ 'user_email', 'validateEmailCreate', 'on' => [ 'create' ] ],
            [ 'user_email', 'validateEmailUpdate', 'on' => [ 'update' ] ],
            [ 'user_username', 'alphanumdotu' ],
            [ 'user_username', 'validateUsernameCreate', 'on' => [ 'create' ] ],
            [ 'user_username', 'validateUsernameUpdate', 'on' => [ 'update' ] ],
            [ [ 'user_firstname', 'user_lastname' ], 'alphanumspace' ],
			[ [ 'user_firstname', 'user_lastname', 'user_gender', 'user_status', 'user_phone', 'user_newsletter' ], 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'user_email' => 'Email',
			'user_role' => 'Role',
			'user_status' => 'Status',
			'user_username' => 'Nickname',
			'user_firstname' => 'First Name',
			'user_lastname' => 'Last Name',
			'user_gender' => 'Gender',
			'user_phone' => 'Phone',
			'user_newsletter' => 'Newsletter'
		];
	}
	
	// User

    public function validateEmailCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByEmail( $this->user_email ) ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_USER_EXIST ) );
            }
        }
    }

    public function validateEmailUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingUser = self::findByEmail( $this->user_email );

			if( $this->getId() != $existingUser->getId() && strcmp( $existingUser->user_email, $this->user_email) == 0 ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_USER_EXIST ) );
			}
        }
    }

    public function validateUsernameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByUsername( $this->user_username ) ) {

                $this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_USERNAME_EXIST ) );
            }
        }
    }
	
    public function validateUsernameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingUser = self::findByUsername( $this->user_username );

			if( $this->getId() != $existingUser->getId() && strcmp( $existingUser->user_username, $this->user_username ) == 0 ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_USERNAME_EXIST ) );
			}
        }
    }
	
	// Validation for login purpose
	public function validatePassword( $password ) {

		return Yii::$app->getSecurity()->validatePassword( $password, $this->user_password_hash );
	}

	// Get a valid name
    public function getName() {

		$name	= $this->user_firstname . " " . $this->user_lastname;

		if( !isset( $name ) || strlen( $name ) <= 2 ) {

			$name	= $this->user_username;

			if( !isset( $name ) || strlen( $name ) <= 2 ) {

				$name	= preg_split( "/@/", $this->user_email );
				$name	= $name[0];
			}
		}

		if( !isset( $name ) || strlen( $name ) <= 2 ) {

			$attributes	= $this->attributes();
			$name		= $attributes[ 'user_firstname' ] . " " . $attributes[ 'user_lastname' ];

			if( !isset( $name ) || strlen( $name ) <= 2 ) {

				$name	= $attributes[ 'user_username' ];

				if( !isset( $name ) || strlen( $name ) <= 2 ) {

					$name	= preg_split( "/@/", $attributes[ 'user_email' ] );
					$name	= $name[0];
				}
			}
		}

		return $name;
    }

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord
	
	public static function tableName() {
		
		return CoreTables::TABLE_USER;
	}

	// yii\web\IdentityInterface
	
    public static function findIdentity( $id ) {

		// Find valid User		
		$user 		= static::findOne( ['user_id' => $id] );

		// Load User Permissions
		if( isset( $user ) && Yii::$app->cmgCore->isRbac() ) {

			$role				= $user->role;
			$user->permissions	= $role->getPermissionsNameList();
		}

        return $user;
    }

    public static function findIdentityByAccessToken( $token, $type = null ) {

        throw new NotSupportedException( 'findIdentityByAccessToken is not implemented.' );
    }

	// User

	public static function findById( $id ) {

		return User::find()->where( 'user_id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByEmail( $email ) {

		return self::find()->where( 'user_email=:email', [ ':email' => $email ] )->one();
	}

	public static function isExistByEmail( $email ) {

		$user = self::find()->where( 'user_email=:email', [ ':email' => $email ] )->one();

		return isset( $user );
	}

	public static function findByUsername( $username ) {

		return self::find()->where( 'user_username=:username', [ ':username' => $username ] )->one();		
	}

	public static function isExistByUsername( $username ) {

		$user = self::find()->where( 'user_username=:username', [ ':username' => $username ] )->one();

		return isset( $user );
	}
}

?>