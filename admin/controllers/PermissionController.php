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

		$this->sidebar			= [ 'parent' => 'sidebar-identity', 'child' => 'perm' ];

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

	public function actionMatrix() {

		$this->sidebar	= [ 'parent' => 'sidebar-identity', 'child' => 'matrix' ];

		Url::remember( [ 'permission/matrix' ], 'roles' );

		return parent::actionMatrix();
	}

	public function actionAll() {

		Url::remember( [ 'permission/all' ], 'permissions' );

		return parent::actionAll();
	}

	public function actionGroups() {

		$this->sidebar	= [ 'parent' => 'sidebar-identity', 'child' => 'perm-group' ];

		Url::remember( [ 'permission/groups' ], 'permissions' );

		return parent::actionGroups();
	}

	public function actionUpdate( $id ) {

		$this->sidebar	= [ 'parent' => 'sidebar-identity', 'child' => 'perm-group' ];

		return parent::actionUpdate( $id );
	}

	public function actionDelete( $id ) {

		$this->sidebar	= [ 'parent' => 'sidebar-identity', 'child' => 'perm-group' ];

		return parent::actionDelete( $id );
	}
}
