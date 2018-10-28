<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\data\custom;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\Meta;

use cmsgears\core\common\actions\base\ModelAction;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Set add data key for given model supporting data trait.
 *
 * @since 1.0.0
 */
class Set extends ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Set -----------------------------------

	public function run( $ckey ) {

		$meta = new Meta();

		// Filter reserved keys
		$validKey = !in_array( $ckey, [ 'data', 'attributes', 'config', 'settings', 'plugins' ] );

		if( $validKey && $meta->load( Yii::$app->request->post(), 'Meta' ) && $meta->validate() ) {

			// Save Meta
			$this->modelService->updateDataCustomObj( $this->model, $ckey, $meta );

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $meta );
		}

		// Generate Errors
		$errors = AjaxUtil::generateErrorMessage( $meta );

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
	}

}
