<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class AdminController extends BaseUserController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->sidebar 		= [ 'parent' => 'sidebar-identity', 'child' => 'admin' ];
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'index'  => [ 'permission' => CoreGlobal::PERM_IDENTITY ],
	                'all' => [ 'permission' => CoreGlobal::PERM_IDENTITY ],
	                'create' => [ 'permission' => CoreGlobal::PERM_IDENTITY ],
	                'update' => [ 'permission' => CoreGlobal::PERM_IDENTITY ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_IDENTITY ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index'  => ['get'],
	                'all' => ['get'],
	                'create' => ['get', 'post'],
	                'update' => ['get', 'post'],
	                'delete' => ['get', 'post']
                ]
            ]
        ];
    }

	// UserController --------------------

	public function actionIndex() {

		// TODO: Admins Dashboard
	}

	public function actionAll() {

		Url::remember( [ 'admin/all' ], 'users' );

		return parent::actionAll( null, CoreGlobal::PERM_ADMIN, false );
	}

	public function actionCreate() {

		return parent::actionCreate( Url::previous( 'users' ), CoreGlobal::TYPE_SYSTEM );
	}

	public function actionUpdate( $id ) {

		return parent::actionUpdate( Url::previous( 'users' ), $id, CoreGlobal::TYPE_SYSTEM );
	}

	public function actionDelete( $id ) {

		return parent::actionDelete( Url::previous( 'users' ), $id, CoreGlobal::TYPE_SYSTEM );
	}
}

?>