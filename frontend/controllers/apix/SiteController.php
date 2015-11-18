<?php
namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\frontend\models\forms\Register;
use cmsgears\core\frontend\models\forms\Newsletter;
use cmsgears\core\common\models\forms\Login;
use cmsgears\core\common\models\forms\ForgotPassword;

use cmsgears\core\common\services\SiteMemberService;
use cmsgears\core\frontend\services\UserService;
use cmsgears\core\frontend\services\NewsletterMemberService;

use cmsgears\core\frontend\controllers\BaseController;

use cmsgears\core\common\utilities\AjaxUtil;

class SiteController extends BaseController {

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
                    'register' => ['post'],
                    'login' => ['post'],
                    'forgotPassword' => ['post'],
                    'newsletter' => ['post']
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
				Yii::$app->cmgCoreMailer->sendRegisterMail( $user );

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REGISTER ) );
			}
		}
		else {

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $model );

			// Trigger Ajax Failure
        	return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}
    }

	public function actionLogin() {

		// Create Form Model
        $model 			= new Login();

		// Load and Validate Form Model
		if( $model->load( Yii::$app->request->post(), 'Login' )  && $model->login() ) {

			$user		= Yii::$app->user->getIdentity();
			$role		= $user->role;

			// Redirect user to home set by admin
			if( isset( $role ) && isset( $role->homeUrl ) ) {

				$homeUrl	= Url::to( [ "/$role->homeUrl" ], true );
			}
			// Redirect user to home set by app config
			else {

				$homeUrl	= Url::to( [ Yii::$app->cmgCore->getLoginRedirectPage() ], true );
			}

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $homeUrl );
		}
		else {

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $model );

			// Trigger Ajax Failure
        	return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}
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
				Yii::$app->cmgCoreMailer->sendPasswordResetMail( $user ); 
				
				return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_FORGOT_PASSWORD ) );
				
				// Refresh the Page
				return $this->refresh();
			}
			else {

				//$model->addError( 'email', Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_USER_NOT_EXIST ) );
				
				// Generate Errors
				
				$model->addError( 'email', Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_USER_NOT_EXIST ) );
				
				$errors = AjaxUtil::generateErrorMessage( $model );
	
				// Trigger Ajax Failure
	        	return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
				
			}
		} 
		else {

			//$model->addError( 'email', Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_USER_NOT_EXIST ) );
			
			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $model );

			// Trigger Ajax Failure
        	return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
			
		}
    }

    public function actionNewsletter() {

		// Create Form Model
		$model = new Newsletter();

		// Load and Validate Form Model
		if( $model->load( Yii::$app->request->post(), 'Newsletter' ) && $model->validate() ) {

			if( NewsletterMemberService::signUp( $model ) ) {

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_NEWSLETTER_SIGNUP ) );
			}
		}
		else {

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $model );

			// Trigger Ajax Failure
        	return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}
    }
}

?>