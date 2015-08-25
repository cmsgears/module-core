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

// BM Imports
use billmaid\core\common\config\BmCoreGlobal;  

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
	                'index'  => [ 'permission' => BmCoreGlobal::PERM_ACCOUNT ]
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
		
		Url::remember( [ "dropdown/all" ], 'dropdowns' );

		$dataProvider = null;

		if( isset( $type ) ) {

			$dataProvider = CategoryService::getPaginationByType( $type );
		}
		else {

			$dataProvider = CategoryService::getPagination();
		}

	    return $this->render( 'all', [
	         'dataProvider' => $dataProvider
	    ]);
	}
	
	public function actionCreate() {

		return parent::actionCreate( Url::previous( "dropdowns" ), CoreGlobal::TYPE_COMBO );
	}
	 
	public function actionUpdate( $id ) {

		return parent::actionUpdate( $id, Url::previous( "dropdowns" ), CoreGlobal::TYPE_COMBO );
	}
	
	public function actionDelete( $id ) {

		return parent::actionDelete( $id, Url::previous( "dropdowns" ), CoreGlobal::TYPE_COMBO );
	}  
}
?>