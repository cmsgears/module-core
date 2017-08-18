<?php
namespace cmsgears\core\common\actions\meta;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Delete delete existing meta for given parent model.
 */
class Delete extends \cmsgears\core\common\base\Action {

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

	// Delete --------------------------------

	/**
	 * Delete meta for given meta id, parent slug and parent type.
	 */
	public function run( $id, $slug, $type = null ) {

		// Find meta parent
		$parent	= null;

		if( isset( $type ) ) {

			$parent	= $this->modelService->getBySlugType( $slug, $type );
		}
		else {

			$parent	= $this->modelService->getBySlug( $slug );
		}

		// Delete meta
		if( isset( $parent ) ) {

			$meta		= $this->metaService->getById( $id );
			$belongsTo	= $meta->hasAttribute( 'modelId' ) ? $meta->belongsTo( $parent ) : $meta->belongsTo( $parent, $this->modelService->getParentType() );

			if( isset( $meta ) && $belongsTo ) {

				$this->metaService->delete( $meta );

				$data	= [ 'id' => $meta->id, 'name' => $meta->name, 'value' => $meta->value ];

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}