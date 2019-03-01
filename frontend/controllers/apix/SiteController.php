<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\helpers\Url;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\Login;
use cmsgears\core\common\models\forms\ForgotPassword;
use cmsgears\core\common\models\forms\Register;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * SiteController handles the ajax requests specific to site and user.
 *
 * @since 1.0.0
 */
class SiteController extends \cmsgears\core\frontend\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $userService;

	protected $siteMemberService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permissions
		$this->crudPermission = CoreGlobal::PERM_USER;

		// Services
		$this->modelService = Yii::$app->factory->get( 'siteService' );
		$this->userService	= Yii::$app->factory->get( 'userService' );

		$this->siteMemberService = Yii::$app->factory->get( 'siteMemberService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		return [
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'submit-feedback' => [ 'post' ],
					'submit-testimonial' => [ 'post' ],
					'register' => [ 'post' ],
					'login' => [ 'post' ],
					'forgot-password' => [ 'post' ],
					'check-user' => [ 'get' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'submit-feedback' => [ 'class' => 'cmsgears\core\common\actions\comment\Feedback' ],
			'submit-testimonial' => [ 'class' => 'cmsgears\core\common\actions\comment\Testimonial' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// SiteController ------------------------

	public function actionRegister() {

		$coreProperties = $this->getCoreProperties();

		// Create Form Model
		$model = new Register();

		// Load and Validate Form Model
		if( $coreProperties->isRegistration() && $model->load( Yii::$app->request->post(), 'Register' ) && $model->validate() ) {

			// Register User
			$user = $this->userService->register( $model );

			if( isset( $user ) ) {

				// Add User to current Site
				$this->siteMemberService->create( $user );

				// Send Register Mail
				Yii::$app->coreMailer->sendRegisterMail( $user );

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REGISTER ) );
			}
		}

		// Generate Errors
		$errors = AjaxUtil::generateErrorMessage( $model );

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
	}

	public function actionLogin( $redirect = null ) {

		// Remember url for redirect on login
		if( isset( $redirect ) ) {

			Url::remember( $redirect, CoreGlobal::REDIRECT_LOGIN );
		}

		// Create Form Model
		$model = new Login();

		// Load and Validate Form Model
		if( $model->load( Yii::$app->request->post(), 'Login' )	 && $model->login() ) {

			$siteId		= Yii::$app->core->getSiteId();
			$user		= Yii::$app->user->getIdentity();
			$role		= $user->role;
			$storedLink	= Url::previous( CoreGlobal::REDIRECT_LOGIN );

			$siteMember = $this->siteMemberService->getBySiteIdUserId(  $siteId, $user->id );

			if( !isset( $siteMember ) ) {

				$this->siteMemberService->create( $user );
			}
			// Redirect user to home
			if( isset( $model->redirectUrl ) ) {

				$homeUrl = $model->redirectUrl;
			}
			else if( isset( $storedLink ) ) {

				$homeUrl = $storedLink;
			}
			// Redirect user to home set by admin
			else if( isset( $role ) && isset( $role->homeUrl ) ) {

				$homeUrl = Url::to( [ "/$role->homeUrl" ], true );
			}
			// Redirect user to home set by app config
			else {

				$homeUrl = Url::to( [ Yii::$app->core->getLoginRedirectPage() ], true );
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

			$user = $this->userService->getByEmail( $model->email );

			// Trigger Reset Password
			if( isset( $user ) && $this->userService->forgotPassword( $user ) ) {

				$user = $this->userService->getByEmail( $model->email );

				// Load User Permissions
				$user->loadPermissions();

				// Send Forgot Password Mail
				Yii::$app->coreMailer->sendPasswordResetMail( $user );

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

		$user = Yii::$app->user->getIdentity();

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
