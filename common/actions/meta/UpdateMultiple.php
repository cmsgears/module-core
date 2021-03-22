<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\meta;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\Meta;
use cmsgears\core\common\models\resources\ModelMeta;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * UpdateMultiple add or update multiple meta of identified model.
 *
 * @since 1.0.0
 */
class UpdateMultiple extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $metaService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->metaService = $this->controller->metaService;

		$metaClass	= $this->metaService->getModelClass();

		$metaTest = new $metaClass;

		if( !$metaTest->hasAttribute( 'modelId' ) ) {

			$this->parentType = $this->modelService->getParentType();
		}
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// UpdateMultiple ------------------------

	public function run() {

		$model = $this->model;

		if( isset( $model ) ) {

			$metaClass	= $this->metaService->getModelClass();
			$metaTest	= new $metaClass;

			$data	= Yii::$app->request->post( 'Meta' );
			$count	= count( $data );
			$metas	= [];

			for( $i = 0; $i < $count; $i++ ) {

				$meta = $data[ $i ];

				if( empty( $meta[ 'label' ] ) ) {

					$meta[ 'label' ] = null;
				}

				if( $metaTest->hasAttribute( 'modelId' ) ) {

					$meta = $this->metaService->initByNameType( $model->id, $meta[ 'name' ], $meta[ 'type' ], $meta[ 'label' ] );
				}
				else {

					$meta = $this->metaService->initByNameType( $model->id, $this->parentType, $meta[ 'name' ], $meta[ 'type' ], $meta[ 'label' ] );
				}

				if( empty( $meta->type ) ) {

					$meta->type = CoreGlobal::TYPE_DEFAULT;
				}

				$metas[] = $meta;
			}

			// Meta using modelId
			if( $metaTest->hasAttribute( 'modelId' ) ) {

				if( Meta::loadMultiple( $metas, Yii::$app->request->post(), 'Meta' ) && Meta::validateMultiple( $metas ) ) {

					$this->modelService->updateMetas( $model, $metas, $this->metaService );

					$data = [];

					foreach( $metas as $meta ) {

						$data[]	= $meta->getFieldInfo();
					}

					// Trigger Ajax Success
					return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
				}

				// Generate Errors
				$errors = AjaxUtil::generateErrorMessage( $metas, [ 'multiple' => true ] );

				// Trigger Ajax Failure
				return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
			}
			// ModelMeta using parentId and parentType
			else {

				if( ModelMeta::loadMultiple( $metas, Yii::$app->request->post(), 'Meta' ) && ModelMeta::validateMultiple( $metas ) ) {

					$this->modelService->updateModelMetas( $model, $metas, $this->metaService );

					$data = [];

					foreach( $metas as $meta ) {

						$data[]	= $meta->getFieldInfo();
					}

					// Trigger Ajax Success
					return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
				}

				// Generate Errors
				$errors = AjaxUtil::generateErrorMessage( $metas, [ 'multiple' => true ] );

				// Trigger Ajax Failure
				return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}

}
