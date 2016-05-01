<?php
namespace cmsgears\core\frontend\controllers;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\frontend\config\WebGlobalCore;

use cmsgears\core\common\models\forms\Login;
use cmsgears\core\frontend\models\forms\Register;

use cmsgears\core\common\services\mappers\SiteMemberService;
use cmsgears\core\frontend\services\entities\UserService;

class SiteController extends \cmsgears\core\common\controllers\SiteController {

	// Constructor and Initialisation ------------------------------

    /**
     * @inheritdoc
     */
 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->layout	= WebGlobalCore::LAYOUT_PUBLIC;
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

        $behaviours	= parent::behaviors();

		$behaviours[ 'verbs' ][ 'actions' ][ 'index' ]			= [ 'get' ];
		$behaviours[ 'verbs' ][ 'actions' ][ 'register' ]		= [ 'get', 'post' ];
		$behaviours[ 'verbs' ][ 'actions' ][ 'confirmAccount' ]	= [ 'get', 'post' ];

		return $behaviours;
    }

	// yii\base\Controller ---------------

    public function actions() {

		if ( !Yii::$app->user->isGuest ) {

			$this->layout	= WebGlobalCore::LAYOUT_PRIVATE;
		}

        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'error' => [
                'class' => 'yii\web\ErrorAction'
            ]
        ];
    }

	// SiteController --------------------

    public function actionIndex() {

		$this->layout	= WebGlobalCore::LAYOUT_LANDING;

		// Render Page
        return $this->render( WebGlobalCore::PAGE_INDEX );
    }

	public function actionLogin() {

		return parent::actionLogin( false );
	}

    public function actionRegister() {

		// Send user to home if already logged in
		$this->checkHome();

		$coreProperties = $this->getCoreProperties();

		// Create Form Model
		$model = new Register();

		// Load and Validate Form Model
		if( $coreProperties->isRegistration() && $model->load( Yii::$app->request->post() ) && $model->validate() ) {

			// Register User
			$user 	= UserService::register( $model );

			if( isset( $user ) ) {

				// Add User to current Site
				SiteMemberService::create( $user );

				// Send Register Mail
				Yii::$app->cmgCoreMailer->sendRegisterMail( $user );

				// Set Flash Message
				Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REGISTER ) );

				// Refresh the Page
				return $this->refresh();
			}
		}

        return $this->render( WebGlobalCore::PAGE_REGISTER, [ CoreGlobal::MODEL_GENERIC => $model ] );
    }

    public function actionConfirmAccount( $token, $email ) {

		// Send user to home if already logged in
		$this->checkHome();

		// Unset Flash Message
		Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, null );

		if( isset( $token ) && isset( $email ) ) {

			$user	= UserService::findByEmail( $email );

			if( isset( $user ) ) {

				$success = false;

				if( Yii::$app->cmgCore->isUserApproval() && UserService::verify( $user, $token, false ) ) {

					$success = true;
				}
				else if( UserService::verify( $user, $token ) ) {

					$success = true;
				}

				if( $success ) {

					// Send Verify Mail
					Yii::$app->cmgCoreMailer->sendVerifyUserMail( $user );

					// Set Success Message
					Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_ACCOUNT_CONFIRM ) );

					return $this->render( WebGlobalCore::PAGE_ACCOUNT_CONFIRM );
				}
			}
		}

		// Set Failure Message
		Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, MYii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_ACCOUNT_CONFIRM ) );

        return $this->render( WebGlobalCore::PAGE_ACCOUNT_CONFIRM );
    }
}

?>