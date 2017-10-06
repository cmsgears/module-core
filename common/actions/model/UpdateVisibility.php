<?php
namespace cmsgears\core\common\actions\model;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\IVisibility;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * UpdateVisibility can be used to update visivility of models using visivility trait.
 *
 * The controller must provide modelService variable using approprite service class.
 */
class UpdateVisibility extends \cmsgears\core\common\actions\base\ModelAction {

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

	// UpdateAvatar --------------------------

	public function run() {

		$model	= $this->model;

		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) ) {

				switch( $model->visibility ) {

					case IVisibility::VISIBILITY_PUBLIC: {

						$this->modelService->makePublic( $model );

						break;
					}
					case IVisibility::VISIBILITY_PROTECTED: {

						$this->modelService->makeProtected( $model );

						break;
					}
					case IVisibility::VISIBILITY_PRIVATE: {

						$this->modelService->makePrivate( $model );

						break;
					}
				}

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
			}

			// Trigger Ajax Failure
			return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
