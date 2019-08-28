<?php
/**
 * This file is part of project Century Real Estate. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.centuryrealestate.in/
 * @copyright Copyright (c) 2018 Century Real Estate Holdings Pvt. Ltd.
 */

namespace cmsgears\core\api;

/**
 * The frontend module component of core module.
 *
 * @since 1.0.0
 */
class Module extends \cmsgears\core\common\base\Module {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $controllerNamespace = 'century\core\api\controllers';

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->setViewPath( '@century/module-core/api/views' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Module --------------------------------

}
