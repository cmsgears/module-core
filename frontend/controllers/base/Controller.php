<?php
namespace cmsgears\core\frontend\controllers\base;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\frontend\config\WebGlobalCore;

use cmsgears\core\frontend\config\WebProperties;

class Controller extends \cmsgears\core\common\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $webProperties;

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->layout	= WebGlobalCore::LAYOUT_PRIVATE;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Controller ----------------------------

	public function getWebProperties() {

		if( !isset( $this->webProperties ) ) {

			$this->webProperties	= WebProperties::getInstance();
		}

		return $this->webProperties;
	}
}
