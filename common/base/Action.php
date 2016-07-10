<?php
namespace cmsgears\core\common\base;

// Yii Imports
use \Yii;
use yii\base\InvalidConfigException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * The parent Action class to handle action requests for both regular and ajax requests.
 *
 * We can selectively process regular and ajax requests as mentioned below:
 *
 * public function run( $param1, $param2 ... ) {
 *
 * 		// Do common processing
 *
 * 		// Do request specific processing
 * 		if( Yii::$app->request->isAjax ) {
 *
 * 			return $this->runAjax( $param1, $param2 ... );
 * 		}
 * 		else {
 *
 * 			return $this->runRegular( $param1, $param2 ... );
 * 		}
 * }
 */
class Action extends \yii\base\Action {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->modelService	= Yii::$app->controller->modelService;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Action

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Action --------------------------------
}
