<?php
namespace cmsgears\core\common\actions\meta;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\ModelMeta;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * UpdateMultiple add/update multiple data meta for given model supporting data trait.
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

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// MultiUpdate ---------------------------

	public function run( $id ) {

		$model	= $this->model;

		if( isset( $model ) ) {

			$modelMetaService	= Yii::$app->factory->get( 'modelMetaService' );

			$modelMetas			= Yii::$app->request->post( 'ModelMeta' );
			$count				= count( $modelMetas );
			$metas				= [];

			for ( $i = 0; $i < $count; $i++ ) {

				$meta		= $modelMetas[ $i ];
				$meta		= $modelMetaService->initByNameType( $model->id, $this->parentType, $meta[ 'name' ], $meta[ 'type' ] );

				$metas[]	= $meta;
			}

			// Load SchoolItem models
			if( ModelMeta::loadMultiple( $metas, Yii::$app->request->post(), 'ModelMeta' ) && ModelMeta::validateMultiple( $metas ) ) {

				$this->modelService->updateModelMetas( $model, $metas );

				$data	= [];

				foreach ( $metas as $meta ) {

					$data[]	= $meta->getFieldInfo();
				}

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}
}
