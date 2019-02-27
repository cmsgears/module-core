<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\forms;

// Yii Imports
use Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\models\forms\BaseForm;
use cmsgears\core\common\utilities\DateUtil;

/**
 * Used to login already registered users.
 *
 * @property string $email
 * @property string $password
 * @property boolean $rememberMe
 * @property boolean $admin
 * @property string $redirectUrl
 *
 * @since 1.0.0
 */
class Login extends BaseForm {

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

	public $interval;

	// Protected --------------

	// Private ----------------

	private $user;

	private $userService;

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function __construct( $admin = false, $config = [] )	 {

		$this->admin		= $admin;
		$this->user			= null;
		$this->userService	= Yii::$app->factory->get( 'userService' );

		$this->interval		= 3600 * 24 * 30;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	/**
	 * @inheritdoc
	 */
	public function rules() {

		// Model Rules
		$rules =  [
			[ [ 'email', 'password' ], 'required' ],
			[ 'rememberMe', 'boolean' ],
			[ [ 'redirectUrl' ], 'safe' ],
			// Disabled email validation to allow both email and username for login.
			//[ 'email', 'email' ],
			[ 'email', 'validateUser' ],
			[ 'password', 'validatePassword' ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'email', 'password' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'email' => 'Email',
			'password' => 'Password'
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	/**
	 * Check whether user exist and verified. It will add appropriate errors in case
	 * user is not found, not verified or blocked by admin.
	 *
	 * @param string $attribute
	 * @param array $params
	 */
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

	/**
	 * Check whether the password provided by user is valid.
	 *
	 * @param string $attribute
	 * @param array $params
	 */
	public function validatePassword( $attribute, $params ) {

		if( !$this->hasErrors() ) {

			$user = $this->getUser();

			if( $user && !$user->validatePassword( $this->password ) ) {

				$this->addError( $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_LOGIN_FAILED ) );
			}
		}
	}

	// Login ---------------------------------

	/**
	 * Find and return the user using given email or username.
	 *
	 * @return \cmsgears\core\common\models\entities\User
	 */
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

	/**
	 * Login and remember the user for pre-defined interval.
	 *
	 * @return boolean
	 */
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

			$user->lastLoginAt = DateUtil::getDateTime();

			$user->save();

			return Yii::$app->user->login( $user, $this->rememberMe ? $this->interval : 0 );
		}

		return false;
	}
}
