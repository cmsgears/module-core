<?php
namespace cmsgears\core\admin\controllers\apix;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\Category;

use cmsgears\core\common\utilities\AjaxUtil;

class CategoryController extends \cmsgears\core\admin\controllers\base\Controller {

    // Variables ---------------------------------------------------

    // Globals ----------------

    // Public -----------------

    // Protected --------------

    // Private ----------------

    // Constructor and Initialisation ------------------------------

    public function init() {

        parent::init();

        $this->crudPermission 	= CoreGlobal::PERM_CORE;
        $this->modelService		= Yii::$app->factory->get( 'categoryService' );
    }

    // Instance methods --------------------------------------------

    // Yii interfaces ------------------------

    // Yii parent classes --------------------

    // yii\base\Component -----

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->core->getRbacFilterClass(),
                'actions' => [
                    'index'  => [ 'permission' => $this->crudPermission ],
                    'autoSearch' => [ 'permission' => CoreGlobal::PERM_ADMIN ], // Available for all admin users
                    'all'   => [ 'permission' => $this->crudPermission ],
                    'create' => [ 'permission' => $this->crudPermission ],
                    'update' => [ 'permission' => $this->crudPermission ],
                    'delete' => [ 'permission' => $this->crudPermission ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index'  => [ 'get' ],
                    'autoSearch' => [ 'post' ],
                    'all'   => [ 'get' ],
                    'create' => [ 'get', 'post' ],
                    'update' => [ 'get', 'post' ],
                    'delete' => [ 'get', 'post' ]
                ]
            ]
        ];
    }

    // yii\base\Controller ----

    public function actions() {

        return [
            'auto-search' => [ 'class' => 'cmsgears\core\common\actions\content\AutoSearch' ]
        ];
    }

    // CMG interfaces ------------------------

    // CMG parent classes --------------------

    // CategoryController --------------------

    public function actionCreate() {

        $model	= new Category();

        $model->setScenario( 'create' );

        if( $model->load( Yii::$app->request->post(), 'Category' )  && $model->validate() ) {

            $this->modelService->create( $model );

            // Trigger Ajax Success
            return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $model );
        }

        // Generate Errors
        $errors = AjaxUtil::generateErrorMessage( $model );

        // Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
    }

    public function actionUpdate( $id ) {

        // Find Model
        $model	= $this->modelService->getById( $id );

        $model->setScenario( 'update' );

        if( $model->load( Yii::$app->request->post(), 'Category' )  && $model->validate() ) {

            $this->modelService->update( $model );

            // Trigger Ajax Success
            return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $model );
        }

        // Generate Errors
        $errors = AjaxUtil::generateErrorMessage( $model );

        // Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
    }

    public function actionDelete( $id ) {

        // Find Model
        $model	= $this->modelService->getById( $id );

        if( isset( $_POST ) && count( $_POST ) > 0 ) {

            $this->modelService->delete( $model );

            // Trigger Ajax Success
            return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $model );
        }
        else {

            // Generate Errors
            $errors = AjaxUtil::generateErrorMessage( $model );

            // Trigger Ajax Failure
            return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
        }
    }
}
