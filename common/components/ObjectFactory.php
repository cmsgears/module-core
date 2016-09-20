<?php
namespace cmsgears\core\common\components;

// Yii Imports
use \Yii;
use yii\di\Container;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * It's the object factory to generate objects for given alias or interface and wraps up the object creation using Dependency Injection provided by Yii via it's Service Locator implementation.
 *
 * It's being used to resolve the services(both system and model), but any other classes can use it to dynamically resolve the dependencies.
 */
class ObjectFactory extends \yii\base\Component {

	// Variables ---------------------------------------------------

	// Global -----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	/**
	 * @var \yii\di\Container - The factory container. It will resolve service dependencies for all the services whether system or model centric.
	 */
	private $container;

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Initialise factory container
		$this->container	= new Container();
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// ObjectFactory -------------------------

	public function getContainer() {

		return $this->container;
	}

	public function get( $class, $params = [], $config = [] ) {

		return $this->container->get( $class, $params, $config );
	}
}
