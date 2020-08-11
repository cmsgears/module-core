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

use cmsgears\core\common\models\base\Meta;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Toggle action create/update boolean meta and toggle it's value for given parent model.
 *
 * @since 1.0.0
 */
class Toggle extends \cmsgears\core\common\actions\base\ModelAction {

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
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Toggle --------------------------------

	public function run( $ctype, $key ) {

		$parent	= $this->model;

		if( isset( $parent ) ) {

			$metaClass = $this->metaService->getModelClass();

			$meta	= new $metaClass;
			$label	= Yii::$app->request->post( 'label' );

			if( $meta->hasAttribute( 'modelId' ) ) {

				$meta = $this->metaService->getByNameType( $parent->id, $key, $ctype );

				if( empty( $meta ) ) {

					$this->metaService->initByNameType( $parent->id, $key, $ctype, Meta::VALUE_TYPE_FLAG, $label );
				}
				else {

					$this->metaService->toggleValue( $meta );
				}
			}
			else {

				$parentType	= $this->modelService->getParentType();

				$meta = $this->metaService->getByNameType( $parent->id, $parentType, $key, $ctype );

				if( empty( $meta ) ) {

					$this->metaService->initByNameType( $parent->id, $parentType, $key, $ctype, Meta::VALUE_TYPE_FLAG, $label );
				}
				else {

					$this->metaService->toggleValue( $meta );
				}
			}

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
