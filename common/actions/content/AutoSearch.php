<?php
namespace cmsgears\core\common\actions\content;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * AutoSearch uses searchByName and searchByNameType methods from NameTrait and NameTypeTrait service traits.
 *
 * The service model must have type column to perform type based search.
 *
 * By default it perform basic search and returns associative array having id and name attributes, but the
 * other configurations provided by these methods can be used to perform advanced search.
 */
class AutoSearch extends \cmsgears\core\common\base\Action {

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

	// AutoSearch ----------------------------

	public function run() {

		$name	= Yii::$app->request->post( 'name' );
		$type	= Yii::$app->request->post( 'type' );
		$data	= [];

		$modelService = $this->controller->modelService;

		// For models having type columns
		if( isset( $type ) ) {

			$data	= $modelService->searchByNameType( $name, $type );
		}
		else {

			$data	= $modelService->searchByName( $name );
		}

		// Trigger Ajax Success
		return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
	}
}
