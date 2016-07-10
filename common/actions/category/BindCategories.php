<?php
namespace cmsgears\core\common\actions\file;

// Yii Imports
use \Yii;
use yii\base\InvalidConfigException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

class BindCategories extends \cmsgears\core\common\base\Action {

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

	// BindCategories ------------------------

	public function run() {

		$binder = new Binder();

		if( $binder->load( Yii::$app->request->post(), 'Binder' ) ) {

			Yii::$app->controller->modelService->bindCategories( $binder );

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
		}

		// Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}
}
