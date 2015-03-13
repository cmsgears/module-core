<?php
namespace cmsgears\modules\core\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\modules\core\common\config\CoreGlobal;

use cmsgears\modules\core\frontend\models\forms\ContactForm;
use cmsgears\modules\core\frontend\models\forms\RegisterForm;
use cmsgears\modules\core\common\models\forms\LoginForm;

use cmsgears\modules\core\frontend\services\UserService;
use cmsgears\modules\core\frontend\services\forms\FormService;

use cmsgears\modules\core\frontend\controllers\BaseController;

use cmsgears\modules\core\common\utilities\MessageUtil;
use cmsgears\modules\core\common\utilities\AjaxUtil;

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

				// Send Register Mail
				Yii::$app->cmgCoreMailer->sendRegisterMail( $this->getCoreProperties(), $this->getMailProperties(), $user );

				// Trigger Ajax Success
				AjaxUtil::generateSuccess( MessageUtil::getMessage( CoreGlobal::MESSAGE_REGISTER ) );
			}
		}
		else {

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $model );

			// Trigger Ajax Failure
        	AjaxUtil::generateFailure( MessageUtil::getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}
    }

	public function actionLogin() {

		// Create Form Model
        $model 			= new LoginForm();

		// Load and Validate Form Model
		if( $model->load( Yii::$app->request->post( "Login" ), "" )  && $model->login() ) {

			// Trigger Ajax Success
			AjaxUtil::generateSuccess( MessageUtil::getMessage( CoreGlobal::MESSAGE_REQUEST ) );
		}
		else {

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $model );

			// Trigger Ajax Failure
        	AjaxUtil::generateFailure( MessageUtil::getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}
    }

	// SiteController

    public function actionLogout() {

        Yii::$app->user->logout();

		// Trigger Ajax Success
		AjaxUtil::generateSuccess( MessageUtil::getMessage( CoreGlobal::MESSAGE_REQUEST ) );
    }
}

?>