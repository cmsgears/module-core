<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\controllers\base;

// Yii Imports
use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\config\CoreProperties;
use cmsgears\core\common\config\CacheProperties;
use cmsgears\core\common\config\MailProperties;

/**
 * The base controller for all the application types i.e. frontend, console and backend.
 *
 * @since 1.0.0
 */
abstract class Controller extends \yii\web\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	/**
	 * Top level CRUD permission to be applied by default on selected actions. It can
	 * be replaced by child classes.
	 *
	 * @var string
	 */
	public $crudPermission;

	/**
	 * The model service for primary model if applicable. It can be obtained either via
	 * factory component or instantiated within controller constructor or init method.
	 *
	 * @var \cmsgears\core\common\services\interfaces\base\IActiveRecordService
	 */
	public $modelService;

	/**
	 * The primary model in action. It can be discovered while applying a filter and other
	 * filters and action can use it directly.
	 *
	 * @var \cmsgears\core\common\models\base\ActiveRecord
	 */
	public $model;

	/**
	 * The base URL to form the sub relative URLs.
	 *
	 * @var type string
	 */
	public $baseUrl;

	/**
	 * The base path to process apix requests.
	 *
	 * @var string
	 */
	public $apixBase;

	/**
	 * It provide information to display active tab on sidebar.
	 *
	 * @var array
	 */
	public $sidebar;

	/**
	 * We need return url in cases where view need to provide links to move back to
	 * previous page. It's also useful when we need to redirect user to previous page
	 * on form success. It's an alternate to breadcrumb, but limited to single action.
	 *
	 * @var string
	 */
	public $returnUrl;

	/**
	 * It store the breadcrumbs for actions.
	 *
	 * @var array
	 */
	public $breadcrumbs;

	// Protected --------------

	/**
	 * It can be used while adding, updating or deleting the primary model. The child
	 * class must override these methods and set the scenario before calling parent class method.
	 *
	 * @var string
	 */
	protected $scenario;

	// Private ----------------

	/**
	 * Core properties
	 *
	 * @var \cmsgears\core\common\config\CoreProperties
	 */
	private $coreProperties;

	/**
	 * Cache properties
	 *
	 * @var \cmsgears\core\common\config\CoreProperties
	 */
	private $cacheProperties;

	/**
	 * Mail properties
	 *
	 * @var \cmsgears\core\common\config\CoreProperties
	 */
	private $mailProperties;

	// Constructor and Initialisation ------------------------------

	// For development purpose only - Publish assets for each request
	public function beforeAction( $action ) {

		if( defined( 'YII_DEBUG' ) && YII_DEBUG ) {

			Yii::$app->assetManager->forceCopy = true;
		}

		// TODO: Enable it carefully considering performance factors

		// Log user's last activity to trace when user was last active
		//Yii::$app->factory->get( 'userService' )->logLastActivity();

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

			$this->coreProperties = CoreProperties::getInstance();
		}

		return $this->coreProperties;
	}

	public function getCacheProperties() {

		if( !isset( $this->cacheProperties ) ) {

			$this->cacheProperties = CacheProperties::getInstance();
		}

		return $this->cacheProperties;
	}

	public function getMailProperties() {

		if( !isset( $this->mailProperties ) ) {

			$this->mailProperties = MailProperties::getInstance();
		}

		return $this->mailProperties;
	}

	public function getBreadcrumbs() {

		$action	= Yii::$app->controller->action->id;

		if( isset( $this->breadcrumbs[ 'base' ] ) ) {

			if( isset( $this->breadcrumbs[ 'subchild' ] ) ){
				
				$parent = ArrayHelper::merge( $this->breadcrumbs[ 'base' ], $this->breadcrumbs[ 'child' ] );
				$child  = ArrayHelper::merge( $parent, $this->breadcrumbs[ 'subchild' ] );
				
				return ArrayHelper::merge( $child, $this->breadcrumbs[ $action ] );
			}
			else if( isset( $this->breadcrumbs[ 'child' ] ) ){
				
				$parent = ArrayHelper::merge( $this->breadcrumbs[ 'base' ], $this->breadcrumbs[ 'child' ] );
				
				return ArrayHelper::merge( $parent, $this->breadcrumbs[ $action ] );
			}

			return ArrayHelper::merge( $this->breadcrumbs[ 'base' ], $this->breadcrumbs[ $action ] );
		}
		else {

			return $this->breadcrumbs[ $action ];
		}
	}

	/**
	 * The method check whether user is logged in and send to respective home page.
	 */
	protected function checkHome() {

		// Send user to home if already logged in
		if ( !Yii::$app->user->isGuest ) {

			$siteMemberService = Yii::$app->factory->get( 'siteMemberService' );

			$user		= Yii::$app->core->getUser();
			$role		= $user->role;
			$storedLink	= Url::previous( CoreGlobal::REDIRECT_LOGIN );

			$siteId = Yii::$app->core->getSiteId();

			$siteMember = $siteMemberService->getBySiteIdUserId( $siteId, $user->id );

			// Auto-Register site member
			if( !isset( $siteMember ) && Yii::$app->core->isAutoSiteMember() ) {

				$siteMemberService->createByParams( [ 'userId' => $user->id ] );
			}

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
