<?php
namespace cmsgears\listing\common\guidelines;

// Yii Imports
/**
 * Imports following below mentioned order:
 * 1. \Yii
 * 2. base
 * 3. data
 * 4. helpers
 * 5. Exceptions
 */
use \Yii;

// CMG Imports
/**
 * Imports following below mentioned order:
 * 1. Interfaces
 * 2. Models
 * 3. Services
 * 4. Utilities
 */
use cmsgears\core\common\config\CoreGlobal;

// Project Imports

class WidgetTemplate extends \cmsgears\core\common\base\Widget {

	// Variables ---------------------------------------------------

	// Constants/Statics --

	// Public -------------

	// Private/Protected --

	// Constructor and Initialisation ------------------------------

	// yii\base\Object -------------------

	/**
	 * @inheritdoc
	 */
    public function init() {

		parent::init();

		// Init Code
	}

	// Instance Methods --------------------------------------------

	// yii\base\Widget -------------------

	/**
	 * @inheritdoc
	 */
    public function run() {

		return $this->renderWidget();
    }

	// WidgetTemplate --------------------

	public function renderWidget( $config = [] ) {

		// Render Views
	}
}

?>