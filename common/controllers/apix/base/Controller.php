<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\controllers\apix;

// Yii Imports
use Yii;

// CMG Imports
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
	 * We need return url in cases where view need to provide links to move back to
	 * previous page. It's also useful when we need to redirect user to previous page
	 * on form success. It's an alternate to breadcrumb, but limited to single action.
	 *
	 * @var string
	 */
	public $returnUrl;

	// Protected --------------

	/**
	 * It can be used while adding, updating or deleting the primary model. The child
	 * class must override these methods and set the scenario before calling parent class method.
	 *
	 * @var string
	 */
	protected $scenario;

	/**
	 * Flag to check whether last active timestamp is supported by the controller.
	 *
	 * @var boolean
	 */
	protected $logLastActive;

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

	public function init() {

		parent::init();

		$this->logLastActive = false;
	}

	// For development purpose only - Publish assets for each request
	public function beforeAction( $action ) {

		// Log user's last activity to trace when user was last active
		if( $this->logLastActive ) {

			Yii::$app->factory->get( 'userService' )->logLastActivity();
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

}
