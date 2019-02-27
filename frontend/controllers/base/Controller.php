<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\frontend\controllers\base;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\frontend\config\SiteProperties;

/**
 * Base Controller of all frontend controllers.
 *
 * @since 1.0.0
 */
abstract class Controller extends \cmsgears\core\common\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $siteProperties;

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Default Layout
		$this->layout = Yii::$app->core->defaultLayout;

		// Default Permission
		$this->crudPermission = CoreGlobal::PERM_USER;
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

			$this->siteProperties = SiteProperties::getInstance();
		}

		return $this->siteProperties;
	}

}
