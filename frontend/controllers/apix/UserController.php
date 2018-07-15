<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\frontend\controllers\apix;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\controllers\apix\UserController as BaseUserController;

/**
 * UserController handles the ajax requests specific to User model.
 *
 * @since 1.0.0
 */
class UserController extends BaseUserController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->crudPermission = CoreGlobal::PERM_USER;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

/*
	public function behaviors() {

		$behaviors = parent::behaviors();

		$behaviors[ 'rbac' ][ 'actions' ][ 'auto-search' ] = [ 'permission' => $this->crudPermission ];

		$behaviors[ 'rbac' ][ 'verbs' ][ 'auto-search' ] = [ 'post' ];

		return $behaviors;
	}
*/

	// yii\base\Controller ----

/*
	public function actions() {

		$actions = parent::actions();

		$actions[ 'auto-search' ][] = [ 'class' => 'cmsgears\core\common\actions\content\AutoSearch' ];

		return $actions;
	}
*/

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// UserController ------------------------

}
