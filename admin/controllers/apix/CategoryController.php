<?php
namespace cmsgears\modules\core\admin\controllers\apix;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\modules\core\common\config\CoreGlobal;
use cmsgears\modules\core\admin\config\AdminGlobalCore;

use cmsgears\modules\core\common\models\entities\Category;
use cmsgears\modules\core\common\models\entities\Permission;

use cmsgears\modules\core\admin\services\CategoryService;

use cmsgears\modules\core\admin\controllers\BaseController;

use cmsgears\modules\core\common\utilities\MessageUtil;
use cmsgears\modules\core\common\utilities\AjaxUtil;

class CategoryController extends BaseController {

	const URL_ALL	= 'category/all';

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'permissions' => [
	                'index'  => Permission::PERM_CATEGORY,
	                'all'   => Permission::PERM_CATEGORY,
	                'create' => Permission::PERM_CATEGORY,
	                'update' => Permission::PERM_CATEGORY,
	                'delete' => Permission::PERM_CATEGORY
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index'  => ['get'],
	                'all'   => ['get'],
	                'create' => ['get', 'post'],
	                'update' => ['get', 'post'],
	                'delete' => ['get', 'post']
                ]
            ]
        ];
    }

	// UserController

	public function actionCreate() {

		$model	= new Category();

		$model->setScenario( "create" );

		if( $model->load( Yii::$app->request->post( "Category" ), "" )  && $model->validate() ) {

			if( CategoryService::create( $model ) ) {

				AjaxUtil::generateSuccess( MessageUtil::getMessage( CoreGlobal::MESSAGE_REQUEST ), $model );
			}
		}
		
		// Generate Errors
		$errors = AjaxUtil::generateErrorMessage( $model );

		// Trigger Ajax Failure
    	AjaxUtil::generateFailure( MessageUtil::getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
	}
	
	public function actionUpdate( $id ) {
		
		// Find Model		
		$model	= CategoryService::findById( $id );
		
		$model->setScenario( "update" );
		
		if( $model->load( Yii::$app->request->post( "Category" ), "" )  && $model->validate() ) {

			if( CategoryService::update( $model ) ) {
				
				AjaxUtil::generateSuccess( MessageUtil::getMessage( CoreGlobal::MESSAGE_REQUEST ), $model );
			}
		}
		
		// Generate Errors
		$errors = AjaxUtil::generateErrorMessage( $model );

		// Trigger Ajax Failure
    	AjaxUtil::generateFailure( MessageUtil::getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
	}
	
	public function actionDelete( $id ) {
		
		// Find Model
		$model	= CategoryService::findById( $id );
		
		if( isset( $_POST ) && count( $_POST ) > 0 ) {
				
			if( CategoryService::delete( $model ) ) {
	
				AjaxUtil::generateSuccess( MessageUtil::getMessage( CoreGlobal::MESSAGE_REQUEST ), $model );
			}
		}
		else {
				
			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $model );

			// Trigger Ajax Failure
	    	AjaxUtil::generateFailure( MessageUtil::getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}
	}
}

?>