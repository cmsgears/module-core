<?php
namespace cmsgears\core\common\actions\grid;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

class Generic extends \cmsgears\core\common\base\Action {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $modelService;

	public $config = [];

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->modelService = isset( $this->modelService ) ? $this->modelService : $this->controller->modelService;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Delete --------------------------------

	public function run( $id ) {

		$action	= Yii::$app->request->post( 'action' );
		$model	= $this->modelService->getById( $id );

		if( isset( $action ) && isset( $model ) ) {

			$action		= str_replace( '-', '', ucwords( $action, '-' ) );
			$action		= lcfirst( $action );
			$service	= $this->modelService;
			$result		= $service->$action( $model, $this->config );

			// Trigger Ajax Success
			if( $result ) {

				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}
}
