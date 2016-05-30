<?php
namespace cmsgears\core\common\models\forms;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;
use yii\validators\FilterValidator;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\User;

use cmsgears\core\common\services\entities\UserService;

use cmsgears\core\common\utilities\MessageUtil;
use cmsgears\core\common\utilities\DateUtil;

class Login extends \yii\base\Model {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	public $email;
	public $password;
	public $rememberMe;
	public $admin;
    public $redirectUrl;

	// Private Variables -------------------

    private $_user;

	// Constructor and Initialisation ------------------------------

	public function __construct( $admin = false )  {

		$this->admin	= $admin;
		$this->_user 	= false;
	}

	// Instance Methods --------------------------------------------

	// yii\base\Model

	public function rules() {

		$trim		= [];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'email', 'password' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];
		}

		$rules =  [
			[ [ 'email', 'password' ], 'required' ],
			[ 'rememberMe', 'boolean' ],
			[ [ 'redirectUrl' ], 'safe' ],
			// Disabled email validation to allow both email and username for login.
			//[ 'email', 'email' ],
			[ 'email', 'validateUser' ],
			[ 'password', 'validatePassword' ]
		];

		if( Yii::$app->cmgCore->trimFieldValue ) {

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	public function attributeLabels() {

		return [
			'email' => 'Email',
			'password' => 'Password',
		];
	}

	// LoginForm

    public function getUser() {

		// Find user having email or username
        if( empty( $this->_user ) ) {

            $this->_user = UserService::findByEmail( $this->email );

			if( empty( $this->_user ) ) {

				$this->_user = UserService::findByUsername( $this->email );
			}
        }

        return $this->_user;
    }

    public function validateUser( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$user = $this->user;

            if( !isset( $user ) ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_USER_NOT_EXIST ) );
            }
			else {

				if( !$this->hasErrors() && !$user->isConfirmed() ) {

					$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_USER_VERIFICATION ) );
				}

				if( !$this->hasErrors() && $user->isBlocked() ) {

					$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_BLOCKED ) );
				}
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

				if( !$user->isPermitted( CoreGlobal::PERM_ADMIN ) ) {

					$this->addError( "email", Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_ALLOWED ) );

					return false;
				}
			}

			$user->lastLoginAt 	= DateUtil::getDateTime();
			$user->save();

            return Yii::$app->user->login( $user, $this->rememberMe ? 3600 * 24 * 30 : 0 );
        }

		return false;
    }
}

?>