<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\follower;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Bulk action applies bulk request for given parent model.
 *
 * @since 1.0.0
 */
class Bulk extends \cmsgears\core\common\base\Action {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $modelService;

	public $user = false;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Model service provided by controller
		$this->modelService	= empty( $this->modelService ) ? $this->controller->modelService : $this->modelService;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Bulk ----------------------------------

	public function run() {

		$column	= Yii::$app->request->post( 'column' );
		$action	= Yii::$app->request->post( 'action' );
		$target	= Yii::$app->request->post( 'target' );

		$user = Yii::$app->core->getUser();

		if( isset( $action ) && isset( $column ) && isset( $target ) ) {

			$target	= preg_split( '/,/', $target );

			foreach( $target as $id ) {

				$model = $this->modelService->getById( $id );

				// Bulk Conditions
				if( isset( $model ) ) {

					// User Bulk
					if( $this->user && $model->modelId == $user->id ) {

						$this->modelService->applyBulk( $model, $column, $action, $target );
					}
				}
			}

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}

}
