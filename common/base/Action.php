<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\base;

/**
 * The parent Action class to handle action requests for both regular and ajax requests.
 *
 * We can selectively process regular and ajax requests as mentioned below:
 *
 * public function run( $param1, $param2 ... ) {
 *
 *		// Do common processing
 *
 *		// Do request specific processing
 *		if( Yii::$app->request->isAjax ) {
 *
 *			// Do ajax processing and return result
 *
 *			// OR
 *
 *			return $this->runAjax( $param1, $param2 ... );
 *		}
 *		else {
 *
 *			// Do regular processing and return result
 *
 *			// OR
 *
 *			return $this->runRegular( $param1, $param2 ... );
 *		}
 * }
 *
 * @since 1.0.0
 */
class Action extends \yii\base\Action {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $viewPath;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		if( isset( $this->viewPath ) ) {

			$this->controller->setViewPath( $this->viewPath );
		}
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Action

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Action --------------------------------

}
