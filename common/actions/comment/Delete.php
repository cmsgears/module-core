<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\comment;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Delete action deletes a model comment.
 *
 * Only model owner can delete comment in exceptional cases. It must be used with extra
 * care in scenarios where comment delete by model owners is not allowed.
 */
class Delete extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $parent = true;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Delete --------------------------------

	public function run( $cid ) {

		if( isset( $this->model ) ) {

			$modelCommentService = Yii::$app->factory->get( 'modelCommentService' );

			$modelComment = $modelCommentService->getById( $cid );

			if( isset( $modelComment ) && $modelComment->isParentValid( $this->model->id, $this->parentType ) ) {

				if( $modelCommentService->delete( $modelComment ) ) {

					// Trigger Ajax Success
					return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
				}
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
