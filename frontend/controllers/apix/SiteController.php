<?php
namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\frontend\models\forms\ContactForm;
use cmsgears\core\frontend\models\forms\RegisterForm;
use cmsgears\core\common\models\forms\LoginForm;

use cmsgears\core\frontend\services\UserService;
use cmsgears\core\frontend\services\RoleService;

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
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post']
                ]
            ]
        ];
    }

	// SiteController

    public function actionRegister() {

		// Create Form Model
		$model = new RegisterForm();

		// Load and Validate Form Model
		if( $model->load( Yii::$app->request->post( "Register" ), "" ) && $model->validate() ) {

			// Register User
			$user = UserService::register( $model );

			if( isset( $user ) ) {

				// Assign default role
				$role	= RoleService::assignRole( $user, "user" );

				// Send Register Mail
				Yii::$app->cmgCoreMailer->sendRegisterMail( $this->getCoreProperties(), $this->getMailProperties(), $user );

				// Trigger Ajax Success
				AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::MESSAGE_REGISTER ) );
			}
		}
		else {

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $model );

			// Trigger Ajax Failure
        	AjaxUtil::generateFailure( Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}
    }

	public function actionLogin() {

		// Create Form Model
        $model 			= new LoginForm();

		// Load and Validate Form Model
		if( $model->load( Yii::$app->request->post( "Login" ), "" )  && $model->login() ) {

			// Trigger Ajax Success
			AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
		}
		else {

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $model );

			// Trigger Ajax Failure
        	AjaxUtil::generateFailure( Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}
    }

    public function actionLogout() {

        Yii::$app->user->logout();

		// Trigger Ajax Success
		AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
    }
}

?>