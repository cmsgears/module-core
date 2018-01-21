<?php
namespace cmsgears\core\admin\controllers\base;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\admin\config\AdminGlobalCore;

use cmsgears\core\admin\config\AdminProperties;

abstract class Controller extends \cmsgears\core\common\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $adminProperties;

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Default Layout
		$this->layout			= AdminGlobalCore::LAYOUT_PRIVATE;

		// Default Permission
		$this->crudPermission	= CoreGlobal::PERM_CORE;
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

			$this->adminProperties	= AdminProperties::getInstance();
		}

		return $this->adminProperties;
	}
}
