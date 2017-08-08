<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use Yii;
use yii\helpers\Url;

class RoleController extends \cmsgears\core\admin\controllers\base\RoleController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Sidebar
		$this->sidebar		= [ 'parent' => 'sidebar-identity', 'child' => 'role' ];

		// Return Url
		$this->returnUrl	= Url::previous( 'roles' );
		$this->returnUrl	= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/role/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
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

	public function actionAll() {

		Url::remember( Yii::$app->request->getUrl(), 'roles' );

		return parent::actionAll();
	}
}
