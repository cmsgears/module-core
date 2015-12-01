<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class RoleController extends BaseRoleController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->sidebar 	= [ 'parent' => 'sidebar-identity', 'child' => 'role' ];
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'index'  => [ 'permission' => CoreGlobal::PERM_RBAC ],
	                'all'   => [ 'permission' => CoreGlobal::PERM_RBAC ],
	                'create' => [ 'permission' => CoreGlobal::PERM_RBAC ],
	                'update' => [ 'permission' => CoreGlobal::PERM_RBAC ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_RBAC ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index'  => [ 'get' ],
	                'all'   => [ 'get' ],
	                'create' => [ 'get', 'post' ],
	                'update' => [ 'get', 'post' ],
	                'delete' => [ 'get', 'post' ]
                ]
            ]
        ];
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