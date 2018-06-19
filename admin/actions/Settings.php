<?php
namespace cmsgears\core\admin\actions;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\base\Action;

/**
 * The Settings action save model settings using Settings Data Form to the data column.
 */
class Settings extends Action {

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

	// Settings ------------------------------

	public function run( $id ) {

		$modelService = $this->controller->modelService;

		// Find Model
		$model = $modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$settingsClass	= $this->controller->settingsClass;
			$settings		= new $settingsClass( $model->getDataMeta( 'settings' ) );

			if( $settings->load( Yii::$app->request->post(), $settings->getClassName() ) && $settings->validate() ) {

				$this->model = $modelService->updateDataMeta( $model, 'settings', $settings );

				return $this->redirect( $this->returnUrl );
			}

			return $this->controller->render( 'settings', [
				'model' => $model,
				'settings' => $settings
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
