<?php
namespace cmsgears\core\common\actions\meta;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * UpdateMeta update existing meta for given parent model.
 *
 * The controller must provide appropriate model service and meta service.
 */
class UpdateMeta extends \cmsgears\core\common\base\Action {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelService;

	protected $metaService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->modelService		= $this->controller->modelService;

		$this->metaService		= $this->controller->metaService;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// UpdateMeta -----------------------

	/**
	 * Update Meta for given Meta id, parent slug and parent type.
	 */
	public function run( $id, $slug, $type = null ) {

		// Find Meta parent
		$parent	= null;

		if( isset( $type ) ) {

			$parent	= $this->modelService->getBySlugType( $slug, $type );
		}
		else {

			$parent	= $this->modelService->getBySlug( $slug );
		}

		// Delete Meta
		if( isset( $parent ) ) {

			$meta	= $this->metaService->getById( $id );

			if( isset( $meta ) && $meta->isBelongsTo( $parent ) ) {

				if( $meta->load( Yii::$app->request->post(), $meta->getClassName() ) && $meta->validate() ) {

					$this->metaService->update( $meta );

					// Trigger Ajax Success
					return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $meta );
				}

				// Generate Errors
				$errors = AjaxUtil::generateErrorMessage( $model );

				// Trigger Ajax Failure
				return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
