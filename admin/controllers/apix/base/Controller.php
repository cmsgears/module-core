<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\admin\controllers\apix\base;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\admin\config\AdminProperties;

/**
 * Base Controller of all APIX controllers.
 *
 * @since 1.0.0
 */
abstract class Controller extends \cmsgears\core\common\controllers\apix\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $adminProperties;

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Default Permission
		$this->crudPermission = CoreGlobal::PERM_ADMIN;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Controller ----------------------------

	public function getAdminProperties() {

		if( !isset( $this->adminProperties ) ) {

			$this->adminProperties = AdminProperties::getInstance();
		}

		return $this->adminProperties;
	}

	// Compatibility Call
	public function getSiteProperties() {

		return $this->getAdminProperties();
	}

}
