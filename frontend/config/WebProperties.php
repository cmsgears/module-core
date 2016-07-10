<?php
namespace cmsgears\core\frontend\config;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class WebProperties extends \cmsgears\core\common\config\CmgProperties {

	// Variables ---------------------------------------------------

	// Global -----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private static $instance;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// WebProperties -------------------------

	public static function getInstance() {

		if( !isset( self::$instance ) ) {

			self::$instance	= new WebProperties();

			self::$instance->init( CoreGlobal::CONFIG_FRONTEND );
		}

		return self::$instance;
	}

	// Properties

}
