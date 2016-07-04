<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class AdminController extends \cmsgears\core\admin\controllers\base\UserController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		$this->roleType			= CoreGlobal::TYPE_SYSTEM;
		$this->permissionSlug	= CoreGlobal::PERM_ADMIN;
		$this->showCreate 		= false;

		$this->sidebar			= [ 'parent' => 'sidebar-identity', 'child' => 'admin' ];

		$this->returnUrl		= Url::previous( 'users' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/admin/all' ], true );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// UserController ------------------------

	public function actionAll() {

		Url::remember( [ 'admin/all' ], 'users' );

		return parent::actionAll();
	}
}

?>