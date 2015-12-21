<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class PermissionController extends \cmsgears\core\admin\controllers\base\PermissionController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->sidebar 	= [ 'parent' => 'sidebar-identity', 'child' => 'permission' ];
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

		$behaviours	= parent::behaviors();

		$behaviours[ 'rbac' ][ 'actions' ][ 'index' ] 	= [ 'permission' => CoreGlobal::PERM_RBAC ];

		$behaviours[ 'verbs' ][ 'actions' ][ 'index' ] 	= [ 'get' ];

		return $behaviours;
    }

	// RoleController --------------------

	public function actionIndex() {

		$this->redirect( 'all' );
	}

	public function actionAll() {

		// Remember return url for crud
		Url::remember( [ 'permission/all' ], 'permissions' );

		return parent::actionAll( CoreGlobal::TYPE_SYSTEM );
	}

	public function actionMatrix() {

		$this->sidebar 	= [ 'parent' => 'sidebar-identity', 'child' => 'matrix' ];

		// Remember return url for crud
		Url::remember( [ 'permission/matrix' ], 'roles' );

		return parent::actionMatrix( CoreGlobal::TYPE_SYSTEM );
	}

	public function actionCreate() {

		return parent::actionCreate( CoreGlobal::TYPE_SYSTEM );
	}

	public function actionUpdate( $id ) {

		return parent::actionUpdate( $id, CoreGlobal::TYPE_SYSTEM );
	}

	public function actionDelete( $id ) {

		return parent::actionDelete( $id, CoreGlobal::TYPE_SYSTEM );
	}
}

?>