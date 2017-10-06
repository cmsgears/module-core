<?php
namespace cmsgears\core\admin\controllers\apix;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class UserController extends \cmsgears\core\common\controllers\apix\UserController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->crudPermission	= CoreGlobal::PERM_USER;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		$behaviors	= parent::behaviors();

		$behaviors[ 'rbac' ][ 'actions' ][ 'auto-search' ] = [ 'permission' => CoreGlobal::PERM_ADMIN ]; // Available for all admin users
		$behaviors[ 'rbac' ][ 'actions' ][ 'bulk' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'delete' ] = [ 'permission' => $this->crudPermission ];

		$behaviors[ 'verbs' ][ 'actions' ][ 'auto-search' ] = [ 'post' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'bulk' ] = [ 'post' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'delete' ] = [ 'post' ];

		return $behaviors;
	}

	// yii\base\Controller ----

	public function actions() {

		$actions	= parent::actions();

		$actions[ 'auto-search' ] = [ 'class' => 'cmsgears\core\common\actions\content\AutoSearch' ];
		$actions[ 'bulk' ] = [ 'class' => 'cmsgears\core\common\actions\grid\Bulk' ];
		$actions[ 'delete' ] = [ 'class' => 'cmsgears\core\common\actions\grid\Delete' ];

		return $actions;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// UserController ------------------------

}
