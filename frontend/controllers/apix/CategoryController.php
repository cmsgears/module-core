<?php
namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\Category;

use cmsgears\core\common\services\resources\CategoryService;

use cmsgears\core\common\utilities\AjaxUtil;

class CategoryController extends \cmsgears\core\admin\controllers\base\Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'autoSearch' => [ 'permission' => CoreGlobal::PERM_ADMIN ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'autoSearch' => [ 'post']
                ]
            ]
        ];
    }

	// CategoryController ----------------

	public function actionAutoSearch() {

		$name	= Yii::$app->request->post( 'name' );
		$type	= Yii::$app->request->post( 'type' );

		$data	= CategoryService::searchByName( $name );

		// Trigger Ajax Success
		return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
	}

	public function actionCreate() {

		$model	= new Category();

		$model->setScenario( 'create' );

		if( $model->load( Yii::$app->request->post(), 'Category' )  && $model->validate() ) {

			if( CategoryService::create( $model ) ) {

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $model );
			}
		}

		// Generate Errors
		$errors = AjaxUtil::generateErrorMessage( $model );

		// Trigger Ajax Failure
    	return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model	= CategoryService::findById( $id );

		$model->setScenario( 'update' );

		if( $model->load( Yii::$app->request->post(), 'Category' )  && $model->validate() ) {

			if( CategoryService::update( $model ) ) {

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $model );
			}
		}

		// Generate Errors
		$errors = AjaxUtil::generateErrorMessage( $model );

		// Trigger Ajax Failure
    	return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model	= CategoryService::findById( $id );

		if( isset( $_POST ) && count( $_POST ) > 0 ) {

			if( CategoryService::delete( $model ) ) {

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $model );
			}
		}
		else {

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $model );

			// Trigger Ajax Failure
	    	return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}
	}
}
