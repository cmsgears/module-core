<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class RoleController extends \cmsgears\core\admin\controllers\base\RoleController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->sidebar 	= [ 'parent' => 'sidebar-identity', 'child' => 'role' ];
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
		Url::remember( [ 'role/all' ], 'roles' );

		return parent::actionAll( CoreGlobal::TYPE_SYSTEM );
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