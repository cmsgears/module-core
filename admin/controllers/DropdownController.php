<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\db\IntegrityException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Category;

use cmsgears\core\admin\services\CategoryService; 

use cmsgears\core\admin\controllers\BaseController;  

class DropdownController extends BaseCategoryController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods ------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'all'  => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'create'  => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'update'  => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'delete'  => [ 'permission' => CoreGlobal::PERM_CORE ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'all'  => ['get'],
	                'create'  => ['get', 'post'],
	                'update'  => ['get', 'post'],
	                'delete'  => ['get', 'post']
                ]
            ]
        ];
    }

	// DropdownController --------------------

	public function actionAll( $type = null ) {
		
		Url::remember( [ 'dropdown/all' ], 'dropdowns' );

		return parent::actionAll( [ 'parent' => 'sidebar-dropdown', 'child' => 'post-category' ], CoreGlobal::TYPE_COMBO, true );
	}
	
	public function actionCreate() {

		return parent::actionCreate( Url::previous( 'dropdowns' ), [ 'parent' => 'sidebar-dropdown', 'child' => 'post-category' ], CoreGlobal::TYPE_COMBO, true );
	}
	 
	public function actionUpdate( $id ) {

		return parent::actionUpdate( $id, Url::previous( 'dropdowns' ), [ 'parent' => 'sidebar-dropdown', 'child' => 'post-category' ], CoreGlobal::TYPE_COMBO, true );
	}
	
	public function actionDelete( $id ) {

		return parent::actionDelete( $id, Url::previous( 'dropdowns' ), [ 'parent' => 'sidebar-dropdown', 'child' => 'post-category' ], CoreGlobal::TYPE_COMBO, true );
	} 
}

?>