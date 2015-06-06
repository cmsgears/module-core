<?php
namespace cmsgears\core\common\models\forms;

// Yii Imports
use \Yii;
use yii\base\Model;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\Permission;

use cmsgears\core\common\services\UserService;

use cmsgears\core\common\utilities\MessageUtil;
use cmsgears\core\common\utilities\DateUtil;

class Login extends Model {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	public $email;
	public $password;
	public $rememberMe;

	// Private Variables -------------------
	
	private $admin;
    private $_user;

	// Constructor and Initialisation ------------------------------

	public function __construct( $admin = false )  {
		
		$this->admin	= $admin;
		$this->_user 	= false;
	}

	// Instance Methods --------------------------------------------

	// yii\base\Model

	public function rules() {

		return  [
			[ [ 'email', 'password' ], 'required' ],
			[ 'rememberMe', 'boolean' ],
			[ 'email', 'email' ],
			[ 'email', 'validateUser' ],
			[ 'password', 'validatePassword' ]
		];
	}

	public function attributeLabels() {

		return [
			'email' => 'Email',
			'password' => 'Password',
		];
	}

	// LoginForm

    public function getUser() {

        if( $this->_user === false ) {

            $this->_user = UserService::findByEmail( $this->email );
        }

        return $this->_user;
    }

    public function validateUser( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( !$this->user ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_USER_NOT_EXIST ) );
            }

			if( !$this->hasErrors() && !$this->user->isConfirmed() ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_USER_VERIFICATION ) );
			}

			if( !$this->hasErrors() && $this->user->isBlocked() ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_BLOCKED ) );
			}
        }
    }

    public function validatePassword( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            $user = $this->getUser();

            if( $user && !$user->validatePassword( $this->password ) ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_LOGIN_FAILED ) );
            }
        }
    }

    public function login() {

        if ( $this->validate() ) {

			$user	= $this->user;

			if( $this->admin ) {

				$user->loadPermissions();

				if( ! $user->isPermitted( Permission::PERM_SITE_ADMIN ) ) {

					$this->addError( "email", MessageUtil::ERROR_NOT_ALLOWED );

					return false;
				}
			}

			$user->lastLoginAt 	= DateUtil::getMysqlDate();
			$user->save();

            return Yii::$app->user->login( $user, $this->rememberMe ? 3600 * 24 * 30 : 0 );
        }

		return false;
    }
}

?>