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

use cmsgears\core\common\actions\base\ModelAction;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Create Action add the meta by identifying the appropriate parent.
 *
 * @since 1.0.0
 */
class Create extends ModelAction {

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

		$this->metaService	= $this->controller->metaService;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Create --------------------------------

	/**
	 * Create Meta for given parent slug and parent type.
	 */
	public function run() {

		$parent	= $this->model;

		if( isset( $parent ) ) {

			$metaClass	= $this->metaService->getModelClass();
			$meta		= new $metaClass;

			if( $meta->hasAttribute( 'modelId' ) ) {

				$meta->modelId = $parent->id;
			}
			else {

				$meta->parentId		= $parent->id;
				$meta->parentType	= $this->modelService->getParentType();
			}

			if( empty( $meta->type ) ) {

				$meta->type = CoreGlobal::TYPE_DEFAULT;
			}

			if( $meta->load( Yii::$app->request->post(), $meta->getClassName() ) && $meta->validate() ) {

				$this->metaService->create( $meta );

				$data = [ 'id' => $meta->id, 'name' => $meta->name, 'value' => $meta->value ];

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
			}

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $meta );

			// Trigger Ajax Failure
			return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
