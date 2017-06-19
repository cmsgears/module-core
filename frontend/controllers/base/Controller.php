<?php
namespace cmsgears\core\frontend\controllers\base;

// CMG Imports
use cmsgears\core\frontend\config\WebGlobalCore;

use cmsgears\core\frontend\config\SiteProperties;

class Controller extends \cmsgears\core\common\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $siteProperties;

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

	public function getSiteProperties() {

		if( !isset( $this->siteProperties ) ) {

			$this->siteProperties	= SiteProperties::getInstance();
		}

		return $this->siteProperties;
	}
}
