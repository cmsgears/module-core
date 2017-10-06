<?php
namespace cmsgears\core\common\actions\data;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\Meta;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * RemoveMeta remove data meta for given model supporting data trait.
 *
 * The controller must provide appropriate model service.
 */
class RemoveMeta extends \cmsgears\core\common\actions\base\ModelAction {

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

	// RemoveMeta ----------------------------

	public function run() {

		$meta	= new Meta();

		if( $meta->load( Yii::$app->request->post(), 'Meta' ) && $meta->validate() ) {

			// Save meta
			$this->modelService->removeDataMetaObj( $this->model, $meta );

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $meta );
		}

		// Generate Errors
		$errors = AjaxUtil::generateErrorMessage( $meta );

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
	}
}
