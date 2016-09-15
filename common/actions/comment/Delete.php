<?php
namespace cmsgears\core\common\actions\comment;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Delete can be used to delete a comment and trigger notification and mail to admin and model owner.
 *
 * Only model owner can delete model in exceptional cases. It must be used with extra care in scenarios where comment dlete is not allowed to model owners.
 *
 * The controller must provide modelService variable using approprite service class.
 */
class Delete extends \cmsgears\core\common\actions\base\ModelAction {

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

    // Delete --------------------------------

    public function run( $id ) {

        $modelCommentService	= Yii::$app->factory->get( 'modelCommentService' );

        $model		= $modelCommentService->getById( $id );
        $user		= Yii::$app->user->getIdentity();
        $parent		= $this->modelService->getById( $model->parentId );

        if( isset( $model ) && $parent->isOwner( $user ) ) {

            if( $modelCommentService->delete( $model ) ) {

                // TODO: Trigger notification and mail

                // Trigger Ajax Success
                return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $model );
            }
        }

        // Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
    }
}
