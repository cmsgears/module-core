<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\admin\controllers\apix;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * UserController provides actions specific to user model.
 *
 * @since 1.0.0
 */
class UserController extends \cmsgears\core\common\controllers\apix\UserController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->crudPermission = CoreGlobal::PERM_ADMIN;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		$behaviors	= parent::behaviors();

		$behaviors[ 'rbac' ][ 'actions' ][ 'auto-search' ] = [ 'permission' => $this->crudPermission ]; // Available for all admin users
		$behaviors[ 'rbac' ][ 'actions' ][ 'bulk' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'generic' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'delete' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'profile' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'account' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'address' ] = [ 'permission' => $this->crudPermission ];

		$behaviors[ 'verbs' ][ 'actions' ][ 'auto-search' ] = [ 'post' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'bulk' ] = [ 'post' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'generic' ] = [ 'post' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'delete' ] = [ 'post' ];

		return $behaviors;
	}

	// yii\base\Controller ----

	public function actions() {

		$actions = parent::actions();

		$actions[ 'auto-search' ] = [ 'class' => 'cmsgears\core\common\actions\content\AutoSearch' ];
		$actions[ 'bulk' ] = [ 'class' => 'cmsgears\core\common\actions\grid\Bulk' ];
		$actions[ 'generic' ] = [ 'class' => 'cmsgears\core\common\actions\grid\Generic' ];
		$actions[ 'delete' ] = [ 'class' => 'cmsgears\core\common\actions\grid\Delete' ];

		return $actions;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// UserController ------------------------

}
