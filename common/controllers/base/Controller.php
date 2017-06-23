<?php
namespace cmsgears\core\common\controllers\base;

// Yii Imports
use Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\config\CoreProperties;
use cmsgears\core\common\config\CacheProperties;
use cmsgears\core\common\config\MailProperties;

abstract class Controller extends \yii\web\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Top level CRUD permission to be applied by default on selected actions. It can be replaced by child classes.
	public $crudPermission;

	// The model service for primary model if applicable. It can be obtained either via factory component or instantiated within controller constructor or init method.
	public $modelService;

	// The primary model in action. It can be discovered while applying a filter and other filters and action can use it directly.
	public $model;

	// It provide information to display active tab on sidebar.
	public $sidebar;

	// We need return url in cases where view need to provide links to move back to previous page. It's also useful when we need to redirect user to previous page on form success. It's an alternate to breadcrumb, but limited to single action.
	public $returnUrl;

	// Protected --------------

	/**
	 * It can be used while adding, updating or deleting the primary model. The child class must override these methods and set the scenario before calling parent class method.
	 */
	protected $scenario;

	// Private ----------------

	// Core and Mail properties.
	private $coreProperties;

	private $cacheProperties;

	private $mailProperties;

	// Constructor and Initialisation ------------------------------

	// For development purpose only - Publish assets for each request
	public function beforeAction( $action ) {

		if( defined( 'YII_DEBUG' ) && YII_DEBUG ) {

			Yii::$app->assetManager->forceCopy = true;
		}

		return parent::beforeAction( $action );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Controller ----------------------------

	public function getCoreProperties() {

		if( !isset( $this->coreProperties ) ) {

			$this->coreProperties	= CoreProperties::getInstance();
		}

		return $this->coreProperties;
	}

	public function getCacheProperties() {

		if( !isset( $this->cacheProperties ) ) {

			$this->cacheProperties	= CacheProperties::getInstance();
		}

		return $this->cacheProperties;
	}

	public function getMailProperties() {

		if( !isset( $this->mailProperties ) ) {

			$this->mailProperties	= MailProperties::getInstance();
		}

		return $this->mailProperties;
	}

	/**
	 * The method check whether user is logged in and send to respective home page.
	 */
	protected function checkHome() {

		// Send user to home if already logged in
		if ( !Yii::$app->user->isGuest ) {

			$user		= Yii::$app->user->getIdentity();
			$role		= $user->role;
			$storedLink	= Url::previous( CoreGlobal::REDIRECT_LOGIN );

			// Redirect user to stored link on login
			if( isset( $storedLink ) ) {

				Yii::$app->response->redirect( $storedLink )->send();
			}
			// Redirect user having role to home
			else if( isset( $role ) ) {

				// Switch according to app id
				$appAdmin		= Yii::$app->core->getAppAdmin();
				$appFrontend	= Yii::$app->core->getAppFrontend();

				// User is on admin app
				if( Yii::$app->id === $appAdmin && isset( $role->adminUrl ) ) {

					Yii::$app->response->redirect( [ "/$role->adminUrl" ] )->send();
				}
				// User is on frontend app
				else if( Yii::$app->id === $appFrontend && isset( $role->homeUrl ) ) {

					Yii::$app->response->redirect( [ "/$role->homeUrl" ] )->send();
				}
				// Redirect user to home set by app config
				else {

					Yii::$app->response->redirect( [ Yii::$app->core->getLoginRedirectPage() ] )->send();
				}
			}
			// Redirect user to home set by app config
			else {

				Yii::$app->response->redirect( [ Yii::$app->core->getLoginRedirectPage() ] )->send();
			}
		}
	}
}
