<?php
namespace cmsgears\core\common\actions\mapper;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Assign action maps existing category to model in action using mapper.
 */
class Assign extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $parent 	= true;

	// Protected --------------

	protected $modelMapperService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Assign --------------------------------

	public function run() {

		$post = yii::$app->request->post();

		if( isset( $this->model ) && isset( $post[ 'itemId' ] ) ) {

			$mappingType = isset( $post[ 'ctype' ] ) ? $post[ 'ctype' ] : null;

			$modelMapper = $this->modelMapperService->activateByModelId( $this->model->id, $this->parentType, $post[ 'itemId' ], $mappingType );

			$data = [ 'cid' => $modelMapper->id, 'name' => $modelMapper->model->name ];

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
