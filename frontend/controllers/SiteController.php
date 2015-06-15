<?php
namespace cmsgears\core\frontend\controllers;

// Yii Imports
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\frontend\config\WebGlobalCore;

use cmsgears\core\common\models\forms\Login;
use cmsgears\core\frontend\models\forms\Register;
use cmsgears\core\frontend\models\forms\ResetPassword;
use cmsgears\core\frontend\models\forms\ForgotPassword;

use cmsgears\core\common\services\SiteMemberService;
use cmsgears\core\frontend\services\UserService;

class SiteController extends BaseController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->layout	= WebGlobalCore::LAYOUT_PUBLIC;
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'logout' => [ 'permission' => CoreGlobal::PERM_USER ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get','post']
                ]
            ]
        ];
    }

	// yii\base\Controller

    public function actions() {

		if ( !Yii::$app->user->isGuest ) {

			$this->layout	= WebGlobalCore::LAYOUT_PRIVATE;
		}

        return [
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ]
        ];
    }

	// SiteController

    public function actionIndex() {

		$this->checkHome();

		$this->layout	= WebGlobalCore::LAYOUT_LANDING;

		// Render Page
        return $this->render( WebGlobalCore::PAGE_INDEX );
    }

    public function actionRegister() {

		// Send user to home if already logged in
		$this->checkHome();

		// Create Form Model
		$model = new Register();

		// Load and Validate Form Model
		if( $model->load( Yii::$app->request->post() ) && $model->validate() ) {

			// Register User
			$user 	= UserService::register( $model );

			if( isset( $user ) ) {

				// Add User to current Site 
				SiteMemberService::create( $user );

				// Send Register Mail
				Yii::$app->cmgCoreMailer->sendRegisterMail( $this->getCoreProperties(), $this->getMailProperties(), $user );

				// Set Flash Message
				Yii::$app->session->setFlash( "success", Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REGISTER ) );
	
				// Refresh the Page
				return $this->refresh();
			}
		}

        return $this->render( WebGlobalCore::PAGE_REGISTER, [
        	'model' => $model
        ]);
    }

    public function actionConfirmAccount( $token, $email ) {

		// Unset Flash Message
		Yii::$app->session->setFlash( "message", null );

		// Send user to home if already logged in
		$this->checkHome();

		if( isset( $token ) && isset( $email ) ) {

			$user	= UserService::findByEmail( $email );

			if( isset( $user ) && UserService::verify( $user, $token ) ) {

				// Send Register Mail
				Yii::$app->cmgCoreMailer->sendVerifyUserMail( $this->getCoreProperties(), $this->getMailProperties(), $user );

				// Set Success Message
				Yii::$app->session->setFlash( "message", Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_ACCOUNT_CONFIRM ) );
			}
			else {

				// Set Failure Message
				Yii::$app->session->setFlash( "message", Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_ACCOUNT_CONFIRM ) );
			}
		}
		else {

			// Set Failure Message
			Yii::$app->session->setFlash( "message", MYii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_ACCOUNT_CONFIRM ) );
		}

        return $this->render( WebGlobalCore::PAGE_ACCOUNT_CONFIRM );
    }

    public function actionActivateAccount( $token, $email ) {

		// Unset Flash Message
		Yii::$app->session->setFlash( "message", null );

		// Send user to home if already logged in
		$this->checkHome();

		$model = new ResetPassword();

		// If valid token found
		if( isset( $token ) && isset( $email ) ) {

			$user	= UserService::findByEmail( $email );

			// If valid user found
			if( isset( $user ) && strcmp( $user->getVerifyToken(), $token ) == 0 ) {

				// Load and Validate Form Model
				if( $model->load( Yii::$app->request->post() ) && $model->validate() ) {

					// Activate User
					if( UserService::activate( $user ) ) {
	
						// Send Register Mail
						Yii::$app->cmgCoreMailer->sendActivateUserMail( $this->getCoreProperties(), $this->getMailProperties(), $model );

						// Set Success Message
						Yii::$app->session->setFlash( "message", Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_ACCOUNT_CONFIRM ) );
					}
				}
			}
			else {

				// Set Failure Message
				Yii::$app->session->setFlash( "message", Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_ACCOUNT_CONFIRM ) );
			}
		}
		else {

			// Set Failure Message
			Yii::$app->session->setFlash( "message", Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_ACCOUNT_CONFIRM ) );
		}

        return $this->render( WebGlobalCore::PAGE_ACCOUNT_ACTIVATE, [ "model" => $model ] );
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

				// Send Forgot Password Mail
				Yii::$app->cmgCoreMailer->sendPasswordResetMail( $this->getCoreProperties(), $this->getMailProperties(), $user );

				// Set Flash Message
				Yii::$app->session->setFlash( "success", Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_FORGOT_PASSWORD ) );

				// Refresh the Page
				return $this->refresh();
			}
			else {

				$model->addError( 'email', Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_USER_NOT_EXIST ) );
			}
		}

        return $this->render( WebGlobalCore::PAGE_FORGOT_PASSWORD, [
        	'model' => $model
        ]);
    }

    public function actionResetPassword( $token, $email ) {

		// Unset Flash Message
		Yii::$app->session->setFlash( "message", null );

		// Send user to home if already logged in
		$this->checkHome();

		$model = new ResetPassword();
		
		// If valid token found
		if( isset( $token ) && isset( $email ) ) {

			$user	= UserService::findByEmail( $email );

			// If valid user found
			if( isset( $user ) && strcmp( $user->resetToken, $token ) == 0 ) {

				// Load and Validate Form Model
				if( $model->load( Yii::$app->request->post() ) && $model->validate() ) {

					if( UserService::resetPassword( $user, $model ) ) {

						// Set Success Message
						Yii::$app->session->setFlash( "message", Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_RESET_PASSWORD ) );
					}
				}
				else {
					
					$model->email = $email;
				}
			}
			else {

				// Set Failure Message
				Yii::$app->session->setFlash( "message", Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_PASSWORD_RESET ) );
			}
		}
		else {

			// Set Failure Message
			Yii::$app->session->setFlash( "message", Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_PASSWORD_RESET ) );
		}

        return $this->render( WebGlobalCore::PAGE_RESET_PASSWORD, [ "model" => $model ] );
    }

	public function actionLogin() {

		// Send user to home if already logged in
		$this->checkHome();

		// Create Form Model
        $model 			= new Login();

		// Load and Validate Form Model
		if( $model->load( Yii::$app->request->post() )  && $model->login() ) {

			$user	= Yii::$app->user->getIdentity();
			$role	= $user->role;
			
			// Redirect user to home set by admin
			if( isset( $role ) && isset( $role->homeUrl ) ) {

				$this->redirect( [ $role->homeUrl ] );
			}
			// Redirect user to home set by app config
			else {

				$this->redirect( [ Yii::$app->cmgCore->getLoginRedirectPage() ] );
			}
		}

    	return $this->render( WebGlobalCore::PAGE_LOGIN, [
    		'model' => $model
    	]);
    }

    public function actionLogout() {

		// Logout User
        Yii::$app->user->logout();
		
		// Redirect to home page
		$this->redirect( [ Yii::$app->cmgCore->getLogoutRedirectPage() ] );
    }

	/**
	 * The method check whether user is logged in and send to respective home page.
	 */
	private function checkHome() {

		// Send user to home if already logged in
	    if ( !Yii::$app->user->isGuest ) {

			$user	= Yii::$app->user->getIdentity();
			$role	= $user->role;

			// Redirect user to home
			if( isset( $role ) && isset( $role->homeUrl ) ) {

				$this->redirect( [ $role->homeUrl ] );
			}
			// Redirect user to home set by app config
			else {

				$this->redirect( [ Yii::$app->cmgCore->getLoginRedirectPage() ] );
			}
	    }
	}
}

?>