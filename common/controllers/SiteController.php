<?php
namespace cmsgears\core\common\controllers;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\Login;
use cmsgears\core\common\models\forms\ForgotPassword;
use cmsgears\core\common\models\forms\ResetPassword;

class SiteController extends \cmsgears\core\common\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $userService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		$this->crudPermission	= CoreGlobal::PERM_USER;
		$this->userService 		= Yii::$app->factory->get( 'userService' );
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
                'class' => VerbFilter::className(),
                'actions' => [
                    'activateAccount' => [ 'get', 'post' ],
                    'forgotPassword' => [ 'get', 'post' ],
                    'resetPassword' => [ 'get', 'post' ],
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
	 * The users added by site admin can be activated by providing valid token and email. If activation link is still valid, user will be activated.
	 */
    public function actionActivateAccount( $token, $email ) {

		// Send user to home if already logged in
		$this->checkHome();

		// Unset Flash Message
		Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, null );

		$model 			= new ResetPassword();
		$model->email	= $email;

		// Load and Validate Form Model
		if( $model->load( Yii::$app->request->post(), 'ResetPassword' ) && $model->validate() ) {

			$user	= $this->userService->getByEmail( $model->email );

			// If valid user found
			if( isset( $user ) ) {

				// Activate User
				if( $this->userService->activate( $user, $token, $model ) ) {

					// Send Register Mail
					Yii::$app->coreMailer->sendActivateUserMail( $user );

					// Set Success Message
					Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_ACCOUNT_CONFIRM ) );
				}
				else {

					// Set Failure Message
					Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_ACCOUNT_CONFIRM ) );
				}
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

				$user	= $this->userService->getByEmail( $model->email );

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

		$model 			= new ResetPassword();
		$model->email	= $email;

		// Load and Validate Form Model
		if( $model->load( Yii::$app->request->post(), 'ResetPassword' ) && $model->validate() ) {

			$user	= $this->userService->getByEmail( $model->email );

			// If valid user found
			if( isset( $user ) ) {

				if( $user->isResetTokenValid( $token ) ) {

					if( $this->userService->resetPassword( $user, $model ) ) {

						// Set Success Message
						Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_RESET_PASSWORD ) );
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

	/**
	 * The method checks whether user is logged in and send to home.
	 */
	public function actionLogin( $admin = false ) {

		// Send user to home if already logged in
		$this->checkHome();

		// Create Form Model
        $model 			= new Login();
		$model->admin 	= $admin;

		// Load and Validate Form Model
		if( $model->load( Yii::$app->request->post(), 'Login' ) && $model->login() ) {

			// Redirect user to home
			$this->checkHome();
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
