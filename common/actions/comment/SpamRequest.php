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
 * SpamRequest can be used to mark a comment for user deletion and trigger notification and mail to admin and model owner.
 *
 * The controller must provide modelService variable using appropriate service class.
 */
class SpamRequest extends \cmsgears\core\common\actions\base\ModelAction {

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

	// RequestDelete -------------------------

	public function run( $cid ) {

		$modelCommentService = Yii::$app->factory->get( 'modelCommentService' );

		$modelComment = $modelCommentService->getById( $cid );

		if( isset( $modelComment ) && $modelComment->isParentValid( $this->model->id, $this->parentType ) ) {

			if( $modelCommentService->updateSpamRequest( $modelComment ) ) {

				Yii::$app->coreMailer->sendCommentSpamRequestMail( $modelComment );

				$data = [ 'cid' => $modelComment->id, 'status' => $modelComment->getStatusStr() ];

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
			}

			// Trigger Ajax Failure
			return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
