<?php
namespace cmsgears\core\frontend\actions\comment;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\mappers\ModelCommentService;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * DeleteRequest can be used to mark a comment for user deletion and trigger notification and mail to admin and model owner.
 *
 * The controller must provide modelService variable using approprite service class.
 */
class DeleteRequest extends Delete {

	// Variables ---------------------------------------------------

	// Constants/Statics --

	// Public -------------

	// Private/Protected --

	// Constructor and Initialisation ------------------------------

	// Instance Methods --------------------------------------------

	// DeleteRequest ---------------------

	public function run( $id ) {

		$model		= ModelCommentService::getById( $id );
		$user		= Yii::$app->user->getIdentity();
		$parent		= $this->parentService->findById( $model->parentId );

		if( isset( $model ) && $parent->isOwner( $user ) ) {

			ModelCommentService::updateDeleteRequest( $model );
            Yii::$app->cmgCoreMailer->sendCommentDeleteRequestMail( $model );

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $model );
		}

		// Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
