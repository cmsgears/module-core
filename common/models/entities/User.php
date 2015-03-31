<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\Query;
use yii\web\IdentityInterface;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\MessageUtil;

class User extends CmgEntity implements IdentityInterface {

	const STATUS_NEW		=   0;
	const STATUS_ACTIVE		= 100;
	const STATUS_BLOCKED	= 200;

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

	public function getRole() {

		return $this->hasOne( Role::className(), [ 'id' => 'roleId' ] );
	}

	public function getAvatar() {

		return $this->hasOne( CmgFile::className(), [ 'id' => 'avatarId' ] );
	}

	public function getLocale() {
		
		return $this->hasOne( Locale::className(), [ 'id' => 'localeId' ] );
	}

	public function getGender() {

		return $this->hasOne( Option::className(), [ 'id' => 'genderId' ] );
	}

	public function getGenderStr() {

		$option = $this->hasOne( Option::className(), [ 'id' => 'genderId' ] );
		$gender	= false;

		if( isset( $option ) ) {

			$gender = $option->value;
		}

		return $gender;
	}

	public function getStatusStr() {

		return self::$statusMap[ $this->status ];
	}

	public function isNew() {
		
		return $this->status == self::STATUS_NEW;		
	}

	public function isConfirmed() {
		
		return $this->status > User::STATUS_NEW;	
	}

	public function isActive() {

		return $this->status == self::STATUS_ACTIVE;
	}

	public function isBlocked() {

		return $this->status == self::STATUS_BLOCKED;	
	}

	public function setPassword( $password ) {
		
		$this->passwordHash = Yii::$app->security->generatePasswordHash( $password );
	}

	public function getNewsletterStr() {

		return $this->newsletter ? "yes" : "no";
	}

    public function generateVerifyToken() {

        $this->verifyToken = Yii::$app->security->generateRandomString();
    }

    public function unsetVerifyToken() {

        $this->verifyToken = null;
    }

    public function generateResetToken() {

        $this->resetToken = Yii::$app->security->generateRandomString();
    }

    public function unsetResetToken() {

        $this->resetToken = null;
    }

    public function generateAuthKey() {

        $this->authKey = Yii::$app->security->generateRandomString();
    }

	public function loadPermissions() {

		$role				= $this->role;
		$this->permissions	= $role->getPermissionsNameList();
	}

	public function isPermitted( $permission ) {

		return in_array( $permission, $this->permissions );
	}

	// yii\web\IdentityInterface

    public function getId() {

        return $this->id;
    }

    public function getAuthKey() {

        return $this->authKey;
    }

    public function validateAuthKey( $authKey ) {

		return $this->authKey === $authKey;
    }

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'email' ], 'required' ],
            [ [ 'roleId', 'username' ], 'required', 'on' => [ 'create', 'update' ] ],
            [ 'email', 'email' ],
            [ 'email', 'validateEmailCreate', 'on' => [ 'create' ] ],
            [ 'email', 'validateEmailUpdate', 'on' => [ 'update' ] ],
            [ 'username', 'alphanumdotu' ],
            [ 'username', 'validateUsernameCreate', 'on' => [ 'create' ] ],
            [ 'username', 'validateUsernameUpdate', 'on' => [ 'update' ] ],
            [ [ 'firstName', 'lastName' ], 'alphanumspace' ],
			[ [ 'firstName', 'lastName', 'genderId', 'status', 'phone', 'newsletter' ], 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'email' => 'Email',
			'roleId' => 'Role',
			'status' => 'Status',
			'username' => 'Username',
			'firstName' => 'First Name',
			'lastName' => 'Last Name',
			'genderId' => 'Gender',
			'phone' => 'Phone',
			'newsletter' => 'Newsletter'
		];
	}
	
	// User

    public function validateEmailCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByEmail( $this->email ) ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EMAIL_EXIST ) );
            }
        }
    }

    public function validateEmailUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingUser = self::findByEmail( $this->email );

			if( $this->id != $existingUser->id && strcmp( $existingUser->email, $this->email) == 0 ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EMAIL_EXIST ) );
			}
        }
    }

    public function validateUsernameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByUsername( $this->username ) ) {

                $this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_USERNAME_EXIST ) );
            }
        }
    }
	
    public function validateUsernameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingUser = self::findByUsername( $this->username );

			if( $this->id != $existingUser->id && strcmp( $existingUser->username, $this->username ) == 0 ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_USERNAME_EXIST ) );
			}
        }
    }
	
	// Validation for login purpose
	public function validatePassword( $password ) {

		return Yii::$app->getSecurity()->validatePassword( $password, $this->passwordHash );
	}

	// Get a valid name
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

	// yii\db\ActiveRecord
	
	public static function tableName() {
		
		return CoreTables::TABLE_USER;
	}

	// yii\web\IdentityInterface
	
    public static function findIdentity( $id ) {

		// Find valid User
		$user 		= static::findOne( ['id' => $id ] );

		// Load User Permissions
		if( isset( $user ) && Yii::$app->cmgCore->isRbac() ) {

			$user->loadPermissions();
		}

        return $user;
    }

    public static function findIdentityByAccessToken( $token, $type = null ) {

        throw new NotSupportedException( 'findIdentityByAccessToken is not implemented.' );
    }

	// User

	public static function findById( $id ) {

		return User::find()->where( 'id=:id', [ ':id' => $id ] )->one();
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