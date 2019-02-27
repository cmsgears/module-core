<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\admin;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\base\Module as BaseModule;

/**
 * The Admin Module of Core Module.
 *
 * @since 1.0.0
 */
class Module extends BaseModule {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $controllerNamespace = 'cmsgears\core\admin\controllers';

	public $config = [ CoreGlobal::CONFIG_CORE, CoreGlobal::CONFIG_CACHE, CoreGlobal::CONFIG_MAIL, CoreGlobal::CONFIG_COMMENT, CoreGlobal::CONFIG_ADMIN, CoreGlobal::CONFIG_FRONTEND ];

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->setViewPath( '@cmsgears/module-core/admin/views' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Module --------------------------------

	public function getSidebarHtml() {

		$path	= Yii::getAlias( '@cmsgears' ) . '/module-core/admin/views/sidebar.php';

		return $path;
	}

}
