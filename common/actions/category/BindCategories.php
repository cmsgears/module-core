<?php
namespace cmsgears\core\common\actions\category;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\Binder;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * BindCategories binds multiple categories to a model using Binder form.
 *
 * The controller must provide appropriate model service having model class, table and type defined for the base model.
 */
class BindCategories extends \cmsgears\core\common\actions\base\ModelAction {

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

    // BindCategories ------------------------

    public function run() {

        $binder = new Binder();

        if( $binder->load( Yii::$app->request->post(), 'Binder' ) ) {

            $this->modelService->bindCategories( $binder );

            // Trigger Ajax Success
            return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
        }

        // Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
    }
}
