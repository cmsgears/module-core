<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\plugin;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\Meta;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * AdminForms Action add the meta by identifying the appropriate parent.
 *
 * @since 1.0.0
 */
class AdminForms extends \cmsgears\core\common\actions\base\ModelAction {

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

	// Create --------------------------------

	/**
	 * Create Meta for given parent slug and parent type.
	 */
	public function run( $id ) {

		$pluginId	= Yii::$app->request->post( 'pluginId' );
		$formType	= Yii::$app->request->post( 'formType' );

		$model = $this->model;

		if( isset( $model ) && isset( $pluginId ) && isset( $formType ) ) {

			$modelService = $this->modelService;

			$plugin = Yii::$app->pluginManager->plugins[ $pluginId ];

			$pluginClass = $plugin[ 'class' ];

			$pluginObj = new $pluginClass( [ 'id' => $pluginId, 'model' => $model, 'admin' => true ] );

			$pluginKey	= $pluginObj->key;
			$formClass	= null;

			switch( $formType ) {

				case 'settings': {

					if( isset( $pluginObj->settingsModelClass ) ) {

						$formClass = $pluginObj->settingsModelClass;
					}

					break;
				}
				case 'config': {

					if( isset( $pluginObj->configModelClass ) ) {

						$formClass = $pluginObj->configModelClass;
					}

					break;
				}
				case 'meta': {

					if( isset( $pluginObj->metaModelClass ) ) {

						$formClass = $pluginObj->metaModelClass;
					}

					break;
				}
				case 'data': {

					if( isset( $pluginObj->dataModelClass ) ) {

						$formClass = $pluginObj->dataModelClass;
					}

					break;
				}
				case 'plugins': {

					if( isset( $pluginObj->dataModelClass ) ) {

						$formClass = $pluginObj->pluginModelClass;
					}

					break;
				}
			}

			if( isset( $formClass ) ) {

				$form = new $formClass( [ 'model' => $model ] );

				if( $form->load( Yii::$app->request->post(), $form->getClassName() ) && $form->validate() ) {

					$meta = new Meta();

					$meta->key		= $pluginKey;
					$meta->value	= $form->getData();

					switch( $formType ) {

						case 'settings': {

							$modelService->updateDataSettingObj( $model, $meta );

							break;
						}
						case 'config': {

							$modelService->updateDataConfigObj( $model, $meta );

							break;
						}
						case 'meta': {

							$modelService->updateDataAttributeObj( $model, $meta );

							break;
						}
						case 'data': {

							$modelService->updateDataKeyObj( $model, $meta );

							break;
						}
						case 'plugins': {

							$modelService->updateDataPluginObj( $model, $meta );

							break;
						}
					}

					// Trigger Ajax Success
					return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
				}

				// Generate Errors
				$errors = AjaxUtil::generateErrorMessage( $form );

				// Trigger Ajax Failure
				return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
