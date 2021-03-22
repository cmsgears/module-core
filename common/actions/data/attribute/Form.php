<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\data\attribute;

// Yii Imports
use Yii;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * The Form action save model attributes using Attributes Data Form to the data column.
 */
class Form extends \cmsgears\core\common\base\Action {

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

		$modelService		= $this->controller->modelService;
		$templateService	= Yii::$app->factory->get( 'templateService' );

		// Find Model
		$model		= $modelService->getById( $id );
		$template	= isset( $model->template ) ? $model->template : $templateService->getGlobalBySlugType( CoreGlobal::TEMPLATE_DEFAULT, $modelService->getParentType() );

		// Update/Render if exist
		if( isset( $model ) && isset( $template ) ) {

			$attributesClass = $template->attributesPath;

			$attributes = new $attributesClass( $model->getDataMeta( 'attributes' ) );

			$this->controller->setViewPath( $template->attributesForm );

			if( $attributes->load( Yii::$app->request->post(), $attributes->getClassName() ) && $attributes->validate() ) {

				$modelService->updateDataMeta( $model, 'attributes', $attributes );

				return $this->controller->redirect( $this->controller->returnUrl );
			}

			return $this->controller->render( 'attributes', [
				'model' => $model,
				'attributes' => $attributes
			]);
		}

		// Model not found
		//throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
		throw new NotFoundHttpException( 'Either model or template not found.' );
	}

}
