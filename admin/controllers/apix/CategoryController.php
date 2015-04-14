<?php
namespace cmsgears\core\admin\controllers\apix;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Category;

use cmsgears\core\admin\services\CategoryService;

use cmsgears\core\admin\controllers\BaseController;

use cmsgears\core\common\utilities\AjaxUtil;

class CategoryController extends BaseController {

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
                'actions' => [
	                'index'  => [ 'permission' => CoreGlobal::PERM_CATEGORY ],
	                'all'   => [ 'permission' => CoreGlobal::PERM_CATEGORY ],
	                'create' => [ 'permission' => CoreGlobal::PERM_CATEGORY ],
	                'update' => [ 'permission' => CoreGlobal::PERM_CATEGORY ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_CATEGORY ]
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

				AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::MESSAGE_REQUEST ), $model );
			}
		}
		
		// Generate Errors
		$errors = AjaxUtil::generateErrorMessage( $model );

		// Trigger Ajax Failure
    	AjaxUtil::generateFailure( Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
	}
	
	public function actionUpdate( $id ) {
		
		// Find Model		
		$model	= CategoryService::findById( $id );
		
		$model->setScenario( "update" );
		
		if( $model->load( Yii::$app->request->post( "Category" ), "" )  && $model->validate() ) {

			if( CategoryService::update( $model ) ) {
				
				AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::MESSAGE_REQUEST ), $model );
			}
		}
		
		// Generate Errors
		$errors = AjaxUtil::generateErrorMessage( $model );

		// Trigger Ajax Failure
    	AjaxUtil::generateFailure( Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
	}
	
	public function actionDelete( $id ) {
		
		// Find Model
		$model	= CategoryService::findById( $id );
		
		if( isset( $_POST ) && count( $_POST ) > 0 ) {
				
			if( CategoryService::delete( $model ) ) {
	
				AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::MESSAGE_REQUEST ), $model );
			}
		}
		else {

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $model );

			// Trigger Ajax Failure
	    	AjaxUtil::generateFailure( Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}
	}
}

?>