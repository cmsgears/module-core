<?php
namespace cmsgears\core\common\models\forms;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\User;

use cmsgears\core\common\utilities\MessageUtil;
use cmsgears\core\common\utilities\DateUtil;

class Login extends \yii\base\Model {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $email;
	public $password;
	public $rememberMe;
	public $admin;
    public $redirectUrl;

	// Protected --------------

	// Private ----------------

	private $user;

	private $userService;

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function __construct( $admin = false, $config = [] )  {

		$this->admin		= $admin;
		$this->user 		= null;
		$this->userService 	= Yii::$app->factory->get( 'userService' );

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	public function rules() {

		$rules =  [
			[ [ 'email', 'password' ], 'required' ],
			[ 'rememberMe', 'boolean' ],
			[ [ 'redirectUrl' ], 'safe' ],
			// Disabled email validation to allow both email and username for login.
			//[ 'email', 'email' ],
			[ 'email', 'validateUser' ],
			[ 'password', 'validatePassword' ]
		];

		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'email', 'password' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

    public function validateUser( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$user = $this->getUser();

            if( !isset( $user ) ) {

				$this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_USER_NOT_EXIST ) );
            }
			else {

				if( !$this->hasErrors() && !$user->isVerified( false ) ) {

					$this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_USER_VERIFICATION ) );
				}

				if( !$this->hasErrors() && $user->isBlocked() ) {

					$this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_BLOCKED ) );
				}
			}
        }
    }

    public function validatePassword( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            $user = $this->getUser();

            if( $user && !$user->validatePassword( $this->password ) ) {

                $this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_LOGIN_FAILED ) );
            }
        }
    }

	// Login ---------------------------------

    public function getUser() {

		// Find user having email or username
        if( empty( $this->user ) ) {

            $this->user = $this->userService->getByEmail( $this->email );

			if( empty( $this->user ) ) {

				$this->user = $this->userService->getByUsername( $this->email );
			}
        }

        return $this->user;
    }

    public function login() {

        if ( $this->validate() ) {

			$user = $this->getUser();

			if( $this->admin ) {

				$user->loadPermissions();

				if( !$user->isPermitted( CoreGlobal::PERM_ADMIN ) ) {

					$this->addError( "email", Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_ALLOWED ) );

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
