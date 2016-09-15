<?php
namespace cmsgears\core\common\actions\category;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * RemoveCategory disable a category for model by de-activating it.
 *
 * The controller must provide appropriate model service having model class, model table and parent type defined for the base model.
 */
class RemoveCategory extends \cmsgears\core\common\actions\base\ModelAction {

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

    // RemoveCategory ------------------------

    public function run() {

        $post	= yii::$app->request->post();

        if( isset( $this->model ) && isset( $post[ 'categoryId' ] ) ) {

            $modelCategoryService	= Yii::$app->factory->get( 'modelCategoryService' );

            $mapping	= $modelCategoryService->getByModelId( $this->model->id, $this->modelService->getParentType(), $post[ 'categoryId' ] );

            if( isset( $mapping ) ) {

                $modelCategoryService->disable( $mapping );

                // Trigger Ajax Success
                return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
            }
        }

        // Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
    }
}
