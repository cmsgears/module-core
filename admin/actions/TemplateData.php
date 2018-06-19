<?php
namespace cmsgears\core\admin\actions;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\base\Action;

/**
 * The TemplateData action save model settings using Template Data Form to the data column.
 */
class TemplateData extends Action {

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

	// TemplateData --------------------------

	public function run( $id ) {

		$modelService = $this->controller->modelService;

		// Find Model
		$model = $modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$template		= $model->template;
			$settingsClass	= $template->dataForm;
			$settings		= new $settingsClass( $settingsClass::$dataKey );

			if( $settings->load( Yii::$app->request->post(), $settings->getClassName() ) && $settings->validate() ) {

				$this->controller->model = $modelService->updateDataMeta( $model, $settingsClass::$dataKey, $settings->getDataObject() );

				return $this->controller->redirect( $this->returnUrl );
			}

			return $this->controller->render( $settingsClass::$dataForm, [
				'model' => $model,
				'settings' => $settings
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
