<?php
namespace cmsgears\core\admin\controllers\optiongroup;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class OptionController extends \cmsgears\core\admin\controllers\base\category\OptionController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->sidebar			= [ 'parent' => 'sidebar-core', 'child' => 'option-group' ];

		$this->returnUrl		= Url::previous( 'options' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/optiongroup/option/all' ], true );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// OptionController ----------------------

	public function actionAll( $cid ) {

		Url::remember( [ "optiongroup/option/all?cid=$cid" ], 'options' );

		return parent::actionAll( $cid );
	}
}
