<?php
namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\Login;
use cmsgears\core\common\models\forms\ForgotPassword;
use cmsgears\core\common\models\forms\Register;

use cmsgears\core\common\services\mappers\SiteMemberService;
use cmsgears\core\common\services\entities\UserService;

use cmsgears\core\common\utilities\AjaxUtil;

class SiteController extends \cmsgears\core\frontend\controllers\base\Controller {

	// Constructor and Initialisation ------------------------------

	public function _construct( $id, $module, $config = [] )  {

		parent::_construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component

    public function behaviors() {

        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'register' => [ 'post' ],
                    'login' => [ 'post' ],
                    'forgotPassword' => [ 'post' ],
                    'checkUser' => [ 'get' ]
                ]
            ]
        ];
    }

	// SiteController

    public function actionRegister() {

		$coreProperties = $this->getCoreProperties();

		// Create Form Model
		$model = new Register();

		// Load and Validate Form Model
		if( $coreProperties->isRegistration() && $model->load( Yii::$app->request->post(), 'Register' ) && $model->validate() ) {

			// Register User
			$user = UserService::register( $model );

			if( isset( $user ) ) {

				// Add User to current Site
				SiteMemberService::create( $user );

				// Send Register Mail
				Yii::$app->core->mailer->sendRegisterMail( $user );

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REGISTER ) );
			}
		}

		// Generate Errors
		$errors = AjaxUtil::generateErrorMessage( $model );

		// Trigger Ajax Failure
    	return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
    }

	public function actionLogin() {

		// Create Form Model
        $model 			= new Login();

		// Load and Validate Form Model
		if( $model->load( Yii::$app->request->post(), 'Login' )  && $model->login() ) {

			$user		= Yii::$app->user->getIdentity();
			$role		= $user->role;
			$storedLink	= Url::previous( CoreGlobal::REDIRECT_LOGIN );

			// Redirect user to home
			if( isset( $model->redirectUrl ) ) {

                $homeUrl    = $model->redirectUrl;
            }
            else if( isset( $storedLink ) ) {

                $homeUrl = $storedLink;
            }
			// Redirect user to home set by admin
			else if( isset( $role ) && isset( $role->homeUrl ) ) {

				$homeUrl	= Url::to( [ "/$role->homeUrl" ], true );
			}
			// Redirect user to home set by app config
			else {

				$homeUrl	= Url::to( [ Yii::$app->cmgCore->getLoginRedirectPage() ], true );
			}

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $homeUrl );
		}

		// Generate Errors
		$errors = AjaxUtil::generateErrorMessage( $model );

		// Trigger Ajax Failure
    	return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
    }

	public function actionForgotPassword() {

		// Create Form Model
		$model = new ForgotPassword();

		// Load and Validate Form Model
		if( $model->load( Yii::$app->request->post(), 'ForgotPassword' ) && $model->validate() ) {

			$user	= UserService::findByEmail( $model->email );

			// Trigger Reset Password
			if( isset( $user ) && UserService::forgotPassword( $user ) ) {

				$user	= UserService::findByEmail( $model->email );

				// Load User Permissions
				$user->loadPermissions();

				// Send Forgot Password Mail
				Yii::$app->core->mailer->sendPasswordResetMail( $user );

				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_FORGOT_PASSWORD ) );
			}

			// Generate Errors
			$model->addError( 'email', Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_USER_NOT_EXIST ) );

			$errors = AjaxUtil::generateErrorMessage( $model );

			// Trigger Ajax Failure
        	return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}

		// Generate Errors
		$errors = AjaxUtil::generateErrorMessage( $model );

		// Trigger Ajax Failure
    	return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
    }

	public function actionCheckUser( $redirect = null ) {

		$user		= Yii::$app->user->getIdentity();

		if( isset( $user ) ) {

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $redirect );
		}

		// Remember url for redirect on login
		if( isset( $redirect ) ) {

			Url::remember( $redirect, CoreGlobal::REDIRECT_LOGIN );
		}

		// Trigger Ajax Failure
    	return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}
}
