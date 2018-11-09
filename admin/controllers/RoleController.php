<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\admin\controllers;

// Yii Imports
use Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\admin\controllers\base\RoleController as BaseRoleController;

/**
 * RoleController provides actions specific to role model.
 *
 * @since 1.0.0
 */
class RoleController extends BaseRoleController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Config
		$this->apixBase	= 'core/role';

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-identity', 'child' => 'role' ];

		// Return Url
		$this->returnUrl = Url::previous( 'roles' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/role/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ]
			],
			'all' => [ [ 'label' => 'Roles' ] ],
			'create' => [ [ 'label' => 'Roles', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Roles', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Roles', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// RoleController ------------------------

	public function actionAll( $config = [] ) {

		Url::remember( Yii::$app->request->getUrl(), 'roles' );

		return parent::actionAll( $config );
	}

}
