<?php
namespace cmsgears\core\frontend\controllers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\frontend\config\WebGlobalCore;

use cmsgears\core\common\models\forms\Login;
use cmsgears\core\common\models\forms\Register;

class SiteController extends \cmsgears\core\common\controllers\SiteController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $siteMemberService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->layout				= WebGlobalCore::LAYOUT_PUBLIC;

		$this->siteMemberService	= Yii::$app->factory->get( 'siteMemberService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		$behaviours	= parent::behaviors();

		$behaviours[ 'verbs' ][ 'actions' ][ 'index' ]			= [ 'get' ];
		$behaviours[ 'verbs' ][ 'actions' ][ 'register' ]		= [ 'get', 'post' ];
		$behaviours[ 'verbs' ][ 'actions' ][ 'confirmAccount' ]	= [ 'get', 'post' ];

		return $behaviours;
	}

	// yii\base\Controller ----

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

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// SiteController ------------------------

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

		// Create Form Model
		$model = new Register();

		// Load and Validate Form Model
		if( $coreProperties->isRegistration() && $model->load( Yii::$app->request->post() ) && $model->validate() ) {

			// Register User
			$coreProperties = $this->getCoreProperties();
			$user			= $this->userService->register( $model );

			if( isset( $user ) ) {

				// Add User to current Site
				$this->siteMemberService->create( $user );

				// Send Register Mail
				Yii::$app->coreMailer->sendRegisterMail( $user );

				// Set Flash Message
				Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REGISTER ) );

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

			$coreProperties = $this->getCoreProperties();
			$user			= $this->userService->getByEmail( $email );

			if( isset( $user ) ) {

				if( $this->userService->verify( $user, $token ) ) {

					// Send Verify Mail
					Yii::$app->coreMailer->sendVerifyUserMail( $user );

					// Set Success Message
					Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_ACCOUNT_CONFIRM ) );

					// Autologin
					if( $coreProperties->isAutoLogin() ) {

						Yii::$app->user->login( $user, 3600 * 24 * 30 );
					}

					return $this->render( WebGlobalCore::PAGE_ACCOUNT_CONFIRM );
				}
			}
		}

		// Set Failure Message
		Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_ACCOUNT_CONFIRM ) );

		return $this->render( WebGlobalCore::PAGE_ACCOUNT_CONFIRM );
	}
}
