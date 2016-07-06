<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class PermissionController extends \cmsgears\core\admin\controllers\base\PermissionController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		$this->sidebar 			= [ 'parent' => 'sidebar-identity', 'child' => 'permission' ];

		$this->returnUrl		= Url::previous( 'permissions' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/permission/all' ], true );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// PermissionController ------------------

	public function actionAll() {

		// Remember return url for crud
		Url::remember( [ 'permission/all' ], 'permissions' );

		return parent::actionAll();
	}

	public function actionMatrix() {

		$this->sidebar 	= [ 'parent' => 'sidebar-identity', 'child' => 'matrix' ];

		// Remember return url for crud
		Url::remember( [ 'permission/matrix' ], 'roles' );

		return parent::actionMatrix();
	}
}
