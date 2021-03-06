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

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\admin\config\AdminGlobalCore;

use cmsgears\core\admin\config\AdminProperties;

class SiteController extends \cmsgears\core\common\controllers\SiteController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $siteProperties;

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Config
		$this->admin = true;

		// Permissions
		$this->crudPermission = CoreGlobal::PERM_ADMIN;

		// Check Layout for Public and Private pages
		if( Yii::$app->user->isGuest ) {

			$this->layout = AdminGlobalCore::LAYOUT_PUBLIC;
		}

		// Breadcrumbs
		$this->breadcrumbs	= [
			'index' => [ [ 'label' => 'Dashboard' ] ],
			'login' => [ [ 'label' => 'Login' ] ],
			'dashboard' => [ [ 'label' => 'Dashboard' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		$behaviours	= parent::behaviors();

		$behaviours[ 'rbac' ][ 'actions' ][ 'index' ] = [ 'permission' => $this->crudPermission ];
		$behaviours[ 'rbac' ][ 'actions' ][ 'dashboard' ] = [ 'permission' => $this->crudPermission ];

		$behaviours[ 'verbs' ][ 'actions' ][ 'index' ] = [ 'get' ];
		$behaviours[ 'verbs' ][ 'actions' ][ 'dashboard' ] = [ 'get' ];

		return $behaviours;
	}

	// yii\base\Controller ----

	public function actions() {

		if ( !Yii::$app->user->isGuest ) {

			$this->layout = AdminGlobalCore::LAYOUT_PRIVATE;
		}

		return [
			'error' => [
				'class' => 'yii\web\ErrorAction'
			]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// SiteController ------------------------

	public function getSiteProperties() {

		if( !isset( $this->siteProperties ) ) {

			$this->siteProperties = AdminProperties::getInstance();
		}

		return $this->siteProperties;
	}

	/**
	 * The method redirect user to dashboard page.
	 */
	public function actionIndex() {

		return $this->redirect( [ '/dashboard' ] );
	}

	/**
	 * The method shows the dashboard page based on user role.
	 */
	public function actionDashboard() {

		$this->layout	= AdminGlobalCore::LAYOUT_DASHBOARD;
		$this->sidebar	= [ 'parent' => 'sidebar-dashboard', 'child' => 'dasboard' ];

		return $this->render( 'index' );
	}

}
