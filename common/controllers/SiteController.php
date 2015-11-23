<?php
namespace cmsgears\core\common\controllers;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\Login;
use cmsgears\core\common\models\forms\ForgotPassword;
use cmsgears\core\common\models\forms\ResetPassword;

use cmsgears\core\frontend\services\UserService;

class SiteController extends BaseController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	/**
	 * The users added by site admin can be activated by providing valid token and email. If activation link is still valid, user will be activated.
	 */
    public function actionActivateAccount( $token, $email ) {

		// Unset Flash Message
		Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, null );

		// Send user to home if already logged in
		$this->checkHome();

		$model 			= new ResetPassword();
		$model->email	= $email;

		// If valid token found
		if( isset( $token ) && isset( $email ) ) {

			// Load and Validate Form Model
			if( $model->load( Yii::$app->request->post( 'ResetPassword' ) ) && $model->validate() ) {

				$user	= UserService::findByEmail( $model->email );

				// If valid user found
				if( isset( $user ) ) {

					if( $user->isVerifyTokenValid( $token ) ) {

						// Activate User
						if( UserService::activate( $user, $model ) ) {
		
							// Send Register Mail
							Yii::$app->cmgCoreMailer->sendActivateUserMail( $user );
	
							// Set Success Message
							Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_ACCOUNT_CONFIRM ) );
						}
					}
					else {
		
						// Set Failure Message
						Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_ACCOUNT_CONFIRM ) );
					}
				}
				else {
	
					// Set Failure Message
					Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_USER_NOT_EXIST ) );
				}
			}
		}
		else {

			// Set Failure Message
			Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_ACCOUNT_CONFIRM ) );
		}

        return $this->render( CoreGlobal::PAGE_ACCOUNT_ACTIVATE, [ CoreGlobal::MODEL_GENERIC => $model ] );
    }

    public function actionForgotPassword() {

		// Send user to home if already logged in
		$this->checkHome();

		// Create Form Model
		$model = new ForgotPassword();

		// Load and Validate Form Model
		if( $model->load( Yii::$app->request->post() ) && $model->validate() ) {
			
			$user	= UserService::findByEmail( $model->email );
			
			// Trigger Reset Password
			if( isset( $user ) && UserService::forgotPassword( $user ) ) {

				$user	= UserService::findByEmail( $model->email );

				// Load User Permissions
				$user->loadPermissions();

				// Send Forgot Password Mail
				Yii::$app->cmgCoreMailer->sendPasswordResetMail( $user );

				// Set Flash Message
				Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_FORGOT_PASSWORD ) );

				// Refresh the Page
				return $this->refresh();
			}
			else {

				$model->addError( CoreGlobal::MODEL_EMAIL, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_USER_NOT_EXIST ) );
			}
		}

        return $this->render( CoreGlobal::PAGE_PASSWORD_FORGOT, [ CoreGlobal::MODEL_GENERIC => $model ] );
    }

    public function actionResetPassword( $token, $email ) {

		// Unset Flash Message
		Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, null );

		// Send user to home if already logged in
		$this->checkHome();

		$model 			= new ResetPassword();
		$model->email	= $email;

		// If valid token found
		if( isset( $token ) && isset( $email ) ) {

			// Load and Validate Form Model
			if( $model->load( Yii::$app->request->post() ) && $model->validate() ) {
	
				$user	= UserService::findByEmail( $model->email );

				// If valid user found
				if( isset( $user ) ) {

					if( $user->isResetTokenValid( $token ) ) {

						if( UserService::resetPassword( $user, $model ) ) {
	
							// Set Success Message
							Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_RESET_PASSWORD ) );
						}
					}
					else {
		
						// Set Failure Message
						Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_PASSWORD_RESET ) );
					}
				}
				else {
	
					// Set Failure Message
					Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_USER_NOT_EXIST ) );
				}
			}
		}
		else {

			// Set Failure Message
			Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_PASSWORD_RESET ) );
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
		if( $model->load( Yii::$app->request->post() )  && $model->login() ) {

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
    	$this->redirect( [ Yii::$app->cmgCore->getLogoutRedirectPage() ] );
    }

	/**
	 * The method check whether user is logged in and send to respective home page.
	 */
	protected function checkHome() {

		// Send user to home if already logged in
	    if ( !Yii::$app->user->isGuest ) {

			$user	= Yii::$app->user->getIdentity();
			$role	= $user->role;

			// Redirect user to home
			if( isset( $role ) && isset( $role->homeUrl ) ) {

				$this->redirect( [ "/$role->homeUrl" ] );
			}
			// Redirect user to home set by app config
			else {

				$this->redirect( [ Yii::$app->cmgCore->getLoginRedirectPage() ] );
			}
	    }
	}
}

?>