<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class AdminController extends \cmsgears\core\admin\controllers\base\UserController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->sidebar 		= [ 'parent' => 'sidebar-identity', 'child' => 'admin' ];
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {
		
		$behaviours	= parent::behaviors();

		$behaviours[ 'rbac' ][ 'actions' ][ 'index' ] 	= [ 'permission' => CoreGlobal::PERM_IDENTITY ];

		$behaviours[ 'verbs' ][ 'actions' ][ 'index' ] 	= [ 'get' ];

		return $behaviours;
    }

	// UserController --------------------

	public function actionIndex() {

		$this->redirect( 'all' );
	}

	public function actionAll() {

		Url::remember( [ 'admin/all' ], 'users' );

		return parent::actionAll( null, CoreGlobal::PERM_ADMIN, false );
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