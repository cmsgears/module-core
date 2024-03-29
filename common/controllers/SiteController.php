<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\controllers;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\Login;
use cmsgears\core\common\models\forms\ForgotPassword;
use cmsgears\core\common\models\forms\ResetPassword;
use cmsgears\core\common\models\forms\OtpResetPassword;

class SiteController extends \cmsgears\core\common\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $userService;

	protected $admin;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permisison
		$this->crudPermission = CoreGlobal::PERM_USER;

		// Services
		$this->userService = Yii::$app->factory->get( 'userService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		return [
			'rbac' => [
				'class' => Yii::$app->core->getRbacFilterClass(),
				'actions' => [
					'logout' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'colors' => [ 'get' ],
					'theme' => [ 'get' ],
					'activate-account' => [ 'get', 'post' ],
					'forgot-password' => [ 'get', 'post' ],
					'reset-password' => [ 'get', 'post' ],
					'reset-password-otp' => [ 'get', 'post' ],
					'login' => [ 'get', 'post' ],
					'logout' => [ 'get' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// SiteController ------------------------

	/**
	 * It shows the colors available with the theme.
	 *
	 * @return string
	 */
	public function actionColors() {

		return $this->render( CoreGlobal::PAGE_COLORS );
	}

	/**
	 * It shows the UI components available with the theme.
	 *
	 * @return string
	 */
	public function actionTheme() {

		return $this->render( CoreGlobal::PAGE_THEME );
	}

	/**
	 * The users added by site admin can be activated by providing valid token and email. If
	 * activation link is still valid, user will be activated.
	 */
	public function actionActivateAccount( $token, $email ) {

		// Send user to home if already logged in
		$this->checkHome();

		// Unset Flash Message
		Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, null );

		$model = new ResetPassword();

		$model->email = $email;

		$user = $this->userService->getByEmail( $model->email );

		// Load and Validate Form Model
		if( isset( $user ) && !$user->isVerifyTokenValid( $token ) ) {

			// Set Failure Message
			Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_ACCOUNT_CONFIRM ) );

			return $this->render( CoreGlobal::PAGE_ACCOUNT_ACTIVATE, [ CoreGlobal::MODEL_GENERIC => $model, 'activated' => false ] );
		}
		// Load and Validate Form Model
		else if( $model->load( Yii::$app->request->post(), 'ResetPassword' ) && $model->validate() ) {

			$coreProperties = $this->getCoreProperties();

			$user = $this->userService->getByEmail( $model->email );

			// If valid user found
			if( isset( $user ) ) {

				// Load User Permissions
				$user->loadPermissions();

				// Activate User
				if( $this->userService->reset( $user, $token, $model, true ) ) {

					// Send Activation Mail
					Yii::$app->coreMailer->sendActivateUserMail( $user );

					// Set Success Message
					Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_ACCOUNT_ACTIVATE ) );

					// Auto Login
					if( $coreProperties->isAutoLogin() ) {

						Yii::$app->user->login( $user, 3600 * 24 * 30 );
					}

					return $this->render( CoreGlobal::PAGE_ACCOUNT_ACTIVATE, [ CoreGlobal::MODEL_GENERIC => $model, 'activated' => true ] );
				}

				// Set Failure Message
				Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_ACCOUNT_CONFIRM ) );

				return $this->render( CoreGlobal::PAGE_ACCOUNT_ACTIVATE, [ CoreGlobal::MODEL_GENERIC => $model, 'activated' => false ] );
			}
			else {

				// Set Failure Message
				Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_USER_NOT_EXIST ) );
			}
		}

		return $this->render( CoreGlobal::PAGE_ACCOUNT_ACTIVATE, [ CoreGlobal::MODEL_GENERIC => $model ] );
	}

	public function actionForgotPassword() {

		// Send user to home if already logged in
		$this->checkHome();

		// Create Form Model
		$model = new ForgotPassword();

		// Load and Validate Form Model
		if( $model->load( Yii::$app->request->post(), 'ForgotPassword' ) && $model->validate() ) {

			$user = $this->userService->getByEmail( $model->email );

			// Trigger Reset Password
			if( isset( $user ) && $this->userService->forgotPassword( $user ) ) {

				$user = $this->userService->getByEmail( $model->email );

				// Load User Permissions
				$user->loadPermissions();

				// Send Forgot Password Mail
				Yii::$app->coreMailer->sendPasswordResetMail( $user );

				// Set Flash Message
				Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_FORGOT_PASSWORD ) );

				// Refresh the Page
				return $this->refresh();
			}
			else {

				$model->addError( CoreGlobal::MODEL_EMAIL, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_USER_NOT_EXIST ) );
			}
		}

		return $this->render( CoreGlobal::PAGE_PASSWORD_FORGOT, [ CoreGlobal::MODEL_GENERIC => $model ] );
	}

	public function actionResetPassword( $token, $email ) {

		// Send user to home if already logged in
		$this->checkHome();

		// Unset Flash Message
		Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, null );

		$model = new ResetPassword();

		$model->email = $email;

		// Load and Validate Form Model
		if( $model->load( Yii::$app->request->post(), 'ResetPassword' ) && $model->validate() ) {

			$user = $this->userService->getByEmail( $model->email );

			// If valid user found
			if( isset( $user ) ) {

				if( $user->isResetTokenValid( $token ) ) {

					if( $this->userService->resetPassword( $user, $model ) ) {

						// Send Password Change Mail
						Yii::$app->coreMailer->sendPasswordChangeMail( $user );

						// Set Success Message
						Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_RESET_PASSWORD ) );

						return $this->render( CoreGlobal::PAGE_PASSWORD_RESET, [ CoreGlobal::MODEL_GENERIC => $model, 'updated' => true ] );
					}
				}
				else {

					// Set Failure Message
					Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_PASSWORD_RESET ) );
				}
			}
			else {

				// Set Failure Message
				Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_USER_NOT_EXIST ) );
			}
		}

		return $this->render( CoreGlobal::PAGE_PASSWORD_RESET, [ CoreGlobal::MODEL_GENERIC => $model ] );
	}

	public function actionResetPasswordOtp() {

		// Send user to home if already logged in
		$this->checkHome();

		// Unset Flash Message
		Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, null );

		$model	= new OtpResetPassword();
		$mobile	= Yii::$app->request->post( 'mobile' );
		$merror	= null;
		$oerror	= null;
		$status	= Yii::$app->request->post( 'status' );
		$user	= !empty( $mobile ) ? $this->userService->getByMobile( $mobile ) : null;
		$otp	= Yii::$app->core->getSessionParam( CoreGlobal::SESSION_RESET_PWD_OTP );

		// Validate Mobile
		if( !empty( $mobile ) && !isset( $user ) ) {

			$merror = 'User not found for given mobile number.';
		}
		// Trigger OTP
		else if( empty( $otp ) && isset( $user ) && !empty( $mobile ) && empty( $status ) ) {

			$otp = Yii::$app->smsManager->generateOtp();

			Yii::$app->core->setSessionParam( CoreGlobal::SESSION_RESET_PWD_OTP, $otp );

			// TODO: Handle errors generated by SMS provider
			if( Yii::$app->smsManager->sendOtp( $mobile, $otp, 'reset-password' ) ) {

				$status = 'success';
			}
		}

		// Load and Validate Form Model
		if( !empty( $mobile ) && !empty( $otp ) && $model->load( Yii::$app->request->post(), 'OtpResetPassword' ) && $model->validate() ) {

			// If valid user found
			if( isset( $user ) ) {

				// Valid OTP
				if( $model->otp == $otp ) {

					Yii::$app->core->clearSessionParam( CoreGlobal::SESSION_RESET_PWD_OTP );

					if( $this->userService->resetPassword( $user, $model ) ) {

						// Send Password Change Mail
						Yii::$app->coreMailer->sendPasswordChangeMail( $user );

						// Set Success Message
						Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_RESET_PASSWORD ) );

						return $this->render( CoreGlobal::PAGE_PASSWORD_RESET, [ CoreGlobal::MODEL_GENERIC => $model, 'updated' => true ] );
					}
				}
				else {

					// Set Failure Message
					$model->addError( 'otp', 'OTP does not match.' );
				}
			}
			else {

				// Set Failure Message
				Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_USER_NOT_EXIST ) );
			}
		}

		return $this->render( CoreGlobal::PAGE_PASSWORD_RESET_OTP, [
			CoreGlobal::MODEL_GENERIC => $model,
			'mobile' => $mobile,
			'merror' => $merror,
			'oerror' => $oerror,
			'status' => $status
		]);
	}

	/**
	 * The method checks whether user is logged in and send to home.
	 */
	public function actionLogin() {

		// Send user to home if already logged in
		$this->checkHome();

		// Create Form Model
		$model = new Login();

		$model->admin = $this->admin;

		// Load and Validate Form Model
		if( $model->load( Yii::$app->request->post(), 'Login' ) && $model->login() ) {

			// Redirect user to home
			$this->checkHome();
		}
		else {

			$model->password = null;
		}

		return $this->render( CoreGlobal::PAGE_LOGIN, [ CoreGlobal::MODEL_GENERIC => $model ] );
	}

	/**
	 * The method clears user session and cookies and redirect user to login.
	 */
	public function actionLogout() {

		// Logout User
		Yii::$app->user->logout();

		// Destroy Session
		Yii::$app->session->destroy();

		// Redirect User to appropriate page
		return $this->redirect( [ Yii::$app->core->getLogoutRedirectPage() ] );
	}

}
