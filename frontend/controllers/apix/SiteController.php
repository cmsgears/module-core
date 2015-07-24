<?php
namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\frontend\models\forms\Register;
use cmsgears\core\common\models\forms\Login;

use cmsgears\core\common\services\SiteMemberService;
use cmsgears\core\frontend\services\UserService;

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
                    'login' => ['post']
                ]
            ]
        ];
    }

	// SiteController

    public function actionRegister() {

		// Create Form Model
		$model = new Register();

		// Load and Validate Form Model
		if( $model->load( Yii::$app->request->post(), 'Register' ) && $model->validate() ) {

			// Register User
			$user = UserService::register( $model );

			if( isset( $user ) ) {

				// Add User to current Site 
				SiteMemberService::create( $user );

				// Send Register Mail
				Yii::$app->cmgCoreMailer->sendRegisterMail( $this->getCoreProperties(), $this->getMailProperties(), $user );

				// Trigger Ajax Success
				AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REGISTER ) );
			}
		}
		else {

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $model );

			// Trigger Ajax Failure
        	AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
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
			AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $homeUrl );
		}
		else {

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $model );

			// Trigger Ajax Failure
        	AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}
    }
}

?>