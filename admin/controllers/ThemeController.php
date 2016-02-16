<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Theme;

use cmsgears\core\admin\services\ThemeService;

class ThemeController extends \cmsgears\core\admin\controllers\base\Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->sidebar 	= [ 'parent' => 'sidebar-core', 'child' => 'theme' ];
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'index' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'all' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'create' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'update' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_CORE ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index' => [ 'get' ],
	                'all' => [ 'get' ],
	                'create' => [ 'get', 'post' ],
	                'update' => [ 'get', 'post' ],
	                'delete' => [ 'get', 'post' ]
                ]
            ]
        ];
    }

	// RoleController --------------------

	public function actionIndex() {

		return $this->redirect( 'all' );
	}

	public function actionAll() {

		$dataProvider = ThemeService::getPagination();

	    return $this->render( 'all', [
			'dataProvider' => $dataProvider
	    ]);
	}

	public function actionCreate() {

		$model		= new Theme();

		$model->setScenario( 'create' );

		if( $model->load( Yii::$app->request->post(), 'Theme' )  && $model->validate() ) {

			if( ThemeService::create( $model ) ) {

				return $this->redirect( 'all' );
			}
		}
		
    	return $this->render( 'create', [
    		'model' => $model,
    		'renderers' => Yii::$app->templateSource->renderers
    	]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model		= ThemeService::findById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$model->setScenario( 'update' );

			if( $model->load( Yii::$app->request->post(), 'Theme' )  && $model->validate() ) {

				if( ThemeService::update( $model ) ) {

					return $this->redirect( 'all' );
				} 
			}

	    	return $this->render( 'update', [
	    		'model' => $model, 
	    		'renderers' => Yii::$app->templateSource->renderers
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model		= ThemeService::findById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), 'Theme' ) ) {

				try {

					ThemeService::delete( $model );

					return $this->redirect( 'all' );
				}
				catch( Exception $e ) {

					throw new HttpException( 409,  Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  ); 
				}
			}

	    	return $this->render( 'delete', [
	    		'model' => $model, 
	    		'renderers' => Yii::$app->templateSource->renderers
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>