<?php
namespace cmsgears\core\common\actions\comment;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * DeleteRequest can be used to mark a comment for user deletion and trigger notification and mail to admin and model owner.
 *
 * The controller must provide modelService variable using approprite service class.
 */
class DeleteRequest extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $typed = true;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// RequestDelete -------------------------

	public function run( $id ) {

		$modelCommentService	= Yii::$app->factory->get( 'modelCommentService' );

		$model		= $modelCommentService->getById( $id );
		$user		= Yii::$app->user->getIdentity();
		$parent		= $this->modelService->getById( $model->parentId );

		if( isset( $model ) && $parent->isOwner( $user ) ) {

			if( $modelCommentService->updateDeleteRequest( $model ) ) {

				 Yii::$app->coreMailer->sendCommentDeleteRequestMail( $model );

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $model );
			}
		}

        // Generate Validation Errors
        $errors = AjaxUtil::generateErrorMessage( $model );

		// Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
