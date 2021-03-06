<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\base;

class Module extends \yii\base\Module {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $config = [];

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Module --------------------------------

	/**
	 * It can be used to generate the html content for sidebar and specific to a module.
	 */
	public function getSidebarHtml() {

		return '';
	}

	/**
	 * It can be used to generate the html content for user dashboard and specific to a module.
	 */
	public function getDashboardHtml() {

		return '';
	}

}
