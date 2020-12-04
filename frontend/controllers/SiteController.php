<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\frontend\controllers;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\frontend\config\SiteProperties;
use cmsgears\core\frontend\config\CoreGlobalWeb;

use cmsgears\core\common\models\forms\Register;
use cmsgears\core\common\models\resources\UserMeta;
use cmsgears\core\common\models\mappers\SiteMember;

/**
 * SiteController provides application actions. Most of these actions are specific to
 * system pages.
 *
 * @since 1.0.0
 */
class SiteController extends \cmsgears\core\common\controllers\SiteController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $siteMemberService;

	// Private ----------------

	private $siteProperties;

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Config
		$this->layout = CoreGlobalWeb::LAYOUT_PUBLIC;

		$this->admin = false;

		// Services
		$this->siteMemberService = Yii::$app->factory->get( 'siteMemberService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		$behaviours	= parent::behaviors();

		$behaviours[ 'verbs' ][ 'actions' ][ 'index' ] = [ 'get' ];
		$behaviours[ 'verbs' ][ 'actions' ][ 'maintenance' ] = [ 'get' ];
		$behaviours[ 'verbs' ][ 'actions' ][ 'register' ] = [ 'get', 'post' ];
		$behaviours[ 'verbs' ][ 'actions' ][ 'confirm-account' ] = [ 'get', 'post' ];
		$behaviours[ 'verbs' ][ 'actions' ][ 'join-site' ] = [ 'get', 'post' ];
		$behaviours[ 'verbs' ][ 'actions' ][ 'feedback' ] = [ 'get' ];
		$behaviours[ 'verbs' ][ 'actions' ][ 'testimonial' ] = [ 'get' ];

		return $behaviours;
	}

	// yii\base\Controller ----

	public function actions() {

		if( !Yii::$app->user->isGuest ) {

			$this->layout = CoreGlobalWeb::LAYOUT_PRIVATE;
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

	public function getSiteProperties() {

		if( !isset( $this->siteProperties ) ) {

			$this->siteProperties = SiteProperties::getInstance();
		}

		return $this->siteProperties;
	}

	public function actionIndex() {

		$this->layout = CoreGlobalWeb::LAYOUT_LANDING;

		return $this->render( CoreGlobalWeb::PAGE_INDEX );
	}

	public function actionMaintenance() {

		return $this->render( CoreGlobalWeb::PAGE_MAINTENANCE );
	}

	public function actionRegister() {

		// Send user to home if already logged in
		$this->checkHome();

		// Create Form Model
		$coreProperties = $this->getCoreProperties();

		$model = new Register();

		// Load and Validate Form Model
		if( $coreProperties->isRegistration() && $model->load( Yii::$app->request->post() ) && $model->validate() ) {

			// Register User
			$user = $this->userService->register( $model );

			if( isset( $user ) ) {

				// Add User to current Site
				$this->siteMemberService->createByParams( [ 'userId' => $user->id ] );

				// Default Settings
				$metaService = Yii::$app->factory->get( 'userMetaService' );

				$metaService->initByNameType( $user->id, CoreGlobal::META_RECEIVE_EMAIL, CoreGlobal::SETTINGS_NOTIFICATION, UserMeta::VALUE_TYPE_FLAG );
				$metaService->initByNameType( $user->id, CoreGlobal::META_RECEIVE_EMAIL, CoreGlobal::SETTINGS_REMINDER, UserMeta::VALUE_TYPE_FLAG );

				// Send Register Mail
				Yii::$app->coreMailer->sendRegisterMail( $user );

				// Trigger New User Notification
				$this->userService->notifyAdmin( $user, [
					'template' => CoreGlobal::TPL_NOTIFY_USER_NEW,
					'adminLink' => "core/user/update?id={$user->id}"
				]);

				// Set Flash Message
				Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REGISTER ) );

				// Refresh the Page
				return $this->refresh();
			}
		}

		return $this->render( CoreGlobalWeb::PAGE_REGISTER, [ CoreGlobal::MODEL_GENERIC => $model ] );
	}

	public function actionConfirmAccount( $token, $email ) {

		// Send user to home if already logged in
		$this->checkHome();

		// Unset Flash Message
		Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, null );

		if( isset( $token ) && isset( $email ) ) {

			$coreProperties = $this->getCoreProperties();

			$user = $this->userService->getByEmail( $email );

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

					return $this->render( CoreGlobalWeb::PAGE_ACCOUNT_CONFIRM, [ 'confirmed' => true ] );
				}

				// Set Failure Message
				Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_ACCOUNT_CONFIRM ) );

				return $this->render( CoreGlobalWeb::PAGE_ACCOUNT_CONFIRM, [ 'confirmed' => false ] );
			}
		}

		// Set Failure Message
		Yii::$app->session->setFlash( CoreGlobal::FLASH_GENERIC, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_ACCOUNT_CONFIRM ) );

		return $this->render( CoreGlobalWeb::PAGE_ACCOUNT_CONFIRM );
	}

	public function actionJoinSite() {

		$model	= new SiteMember();
		$user	= Yii::$app->core->getUser();

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$siteId		= Yii::$app->core->getSiteId();
			$siteMember = $this->siteMemberService->findBySiteIdUserId( $siteId, $user->id );

			if( !isset( $siteMember ) ) {

				$siteMember = $this->siteMemberService->create( $user );

				if( $siteMember ) {

					$this->checkHome();
				}
			}
		}

		return $this->render( CoreGlobal::PAGE_SITE_MEMBER, [ 'user' => $user, 'model' => $model ] );
	}

	public function actionFeedback() {

		$this->layout = CoreGlobalWeb::LAYOUT_PUBLIC;

		return $this->render( CoreGlobalWeb::PAGE_FEEDBACK );
	}

	public function actionTestimonial() {

		$this->layout = CoreGlobalWeb::LAYOUT_PUBLIC;

		return $this->render( CoreGlobalWeb::PAGE_TESTIMONIAL );
	}

}
