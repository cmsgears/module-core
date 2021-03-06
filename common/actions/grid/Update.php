<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\grid;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Update action update the given model available in grid.
 *
 * @since 1.0.0
 */
class Update extends \cmsgears\core\common\base\Action {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $modelService;

	public $config = [];

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->modelService = isset( $this->modelService ) ? $this->modelService : $this->controller->modelService;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Update --------------------------------

	public function run( $id ) {

		// Find Model
		$model = $this->modelService->getById( $id );

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$model = $this->modelService->update( $model );

			// Trigger Ajax Success
			if( $model ) {

				// Controller Model for post action
				$this->controller->model = $model;

				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
			}
		}

		// Generate Errors
		$errors = AjaxUtil::generateErrorMessage( $model );

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
	}

}
