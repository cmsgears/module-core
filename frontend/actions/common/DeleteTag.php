<?php
namespace cmsgears\core\frontend\actions\common;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\mappers\ModelTagService;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * DeleteTag can be used to delete a tag for model.
 *
 * The controller must provide modelService variable using approprite service class.
 */
class DeleteTag extends ModelAction {

	// Variables ---------------------------------------------------

	// Constants/Statics --

	// Public -------------

	// Private/Protected --

	// Constructor and Initialisation ------------------------------

	// Instance Methods --------------------------------------------

	// DeleteTag -------------------------

	public function run( $tslug ) {

		if( isset( $this->model ) ) {

			ModelTagService::deleteByTagSlug( $this->model->id, $this->modelType, $tslug );

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $tslug );
		}

		// Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>
