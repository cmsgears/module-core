<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use Yii;
use yii\helpers\Url;

class PermissionController extends \cmsgears\core\admin\controllers\base\PermissionController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->sidebar		= [ 'parent' => 'sidebar-identity', 'child' => 'perm' ];

		$this->returnUrl	= Url::previous( 'permissions' );
		$this->returnUrl	= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/permission/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs	= [
			'all' => [ [ 'label' => 'Permissions' ] ],
			'groups' => [ [ 'label' => 'Permissions', 'url' => $this->returnUrl ], [ 'label' => 'Groups' ] ],
			'create' => [ [ 'label' => 'Permissions', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Permissions', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Permissions', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
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

		Url::remember( Yii::$app->request->getUrl(), 'permissions' );

		return parent::actionAll();
	}

	public function actionGroups() {

		$this->sidebar	= [ 'parent' => 'sidebar-identity', 'child' => 'perm-group' ];

		Url::remember( Yii::$app->request->getUrl(), 'permissions' );

		return parent::actionGroups();
	}

	public function actionUpdate( $id ) {

		$model	= $this->modelService->getById( $id );

		if( $model->group ) {

			$this->sidebar	= [ 'parent' => 'sidebar-identity', 'child' => 'perm-group' ];
		}

		return parent::actionUpdate( $id );
	}

	public function actionDelete( $id ) {

		$model	= $this->modelService->getById( $id );

		if( $model->group ) {

			$this->sidebar	= [ 'parent' => 'sidebar-identity', 'child' => 'perm-group' ];
		}

		return parent::actionDelete( $id );
	}
}
