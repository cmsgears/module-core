<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\data\setting;

// Yii Imports
use Yii;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\base\Action;

/**
 * The Form action save model settings using Settings Data Form to the data column.
 */
class Form extends Action {

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

	// Form ----------------------------------

	public function run( $id ) {

		$modelService = $this->controller->modelService;

		// Find Model
		$model		= $modelService->getById( $id );
		$template	= isset( $model->template ) ? $model->template : Yii::$app->factory->get( 'templateService' )->getGlobalBySlugType( CoreGlobal::TEMPLATE_DEFAULT, $modelService->getParentType() );

		// Update/Render if exist
		if( isset( $model ) && isset( $template ) ) {

			$settingsClass	= $template->settingsPath;
			$settings		= new $settingsClass( $model->getDataMeta( 'settings' ) );

			$this->controller->setViewPath( $template->settingsForm );

			if( $settings->load( Yii::$app->request->post(), $settings->getClassName() ) && $settings->validate() ) {

				$modelService->updateDataMeta( $model, 'settings', $settings );

				return $this->controller->redirect( $this->controller->returnUrl );
			}

			return $this->controller->render( 'settings', [
				'model' => $model,
				'settings' => $settings
			]);
		}

		// Model not found
		//throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
		throw new NotFoundHttpException( 'Either model or template not found.' );
	}

}
