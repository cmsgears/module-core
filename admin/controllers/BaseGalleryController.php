<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Gallery;

use cmsgears\core\admin\services\GalleryService;

class BaseGalleryController extends \cmsgears\core\admin\controllers\BaseController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

	// Default RBAC and Verbs
    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'index'  => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'all'   => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'create' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'update' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'items' => [ 'permission' => CoreGlobal::PERM_CORE ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index'  => [ 'get' ],
	                'all'   => [ 'get' ],
	                'create' => [ 'get', 'post' ],
	                'update' => [ 'get', 'post' ],
	                'delete' => [ 'get', 'post' ],
	                'items'  => [ 'get' ]
                ]
            ]
        ];
    }

	// RoleController --------------------

	public function actionIndex() {

		$this->redirect( [ 'all' ] );
	}

	public function actionAll( $type = null ) {

		$dataProvider = GalleryService::getPaginationByType( $type );

		Url::remember( [ 'gallery/all' ], 'galleries' );

	    return $this->render( 'all', [
	         'dataProvider' => $dataProvider
	    ]);
	}

	public function actionCreate( $type = null ) {

		$model				= new Gallery();
		$this->returnUrl	= Url::previous( 'galleries' );

		$model->setScenario( 'create' );

		if( isset( $type ) ) {

			$model->type = $type;
		}

		if( $model->load( Yii::$app->request->post(), 'Gallery' )  && $model->validate() ) {

			if( GalleryService::create( $model ) ) {

				$this->redirect( $this->returnUrl );
			}
		}

    	return $this->render( '@cmsgears/module-core/admin/views/gallery/create', [
    		'model' => $model
    	]);
	}

	public function actionUpdate( $id, $type = null ) {

		// Find Model
		$model				= GalleryService::findById( $id );
		$this->returnUrl	= Url::previous( 'galleries' );

		// Update/Render if exist
		if( isset( $model ) ) {

			$model->setScenario( 'update' );
	
			if( $model->load( Yii::$app->request->post(), 'Gallery' )  && $model->validate() ) {
	
				if( GalleryService::update( $model ) ) {

					$this->redirect( $this->returnUrl );
				}
			}

	    	return $this->render( '@cmsgears/module-core/admin/views/gallery/update', [
	    		'model' => $model
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id, $type = null ) {

		// Find Model
		$model				= GalleryService::findById( $id );
		$this->returnUrl	= Url::previous( 'galleries' );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), 'Gallery' ) ) {
	
				if( GalleryService::delete( $model ) ) {
		
					$this->redirect( $this->returnUrl );
				}
			}

	    	return $this->render( '@cmsgears/module-core/admin/views/gallery/delete', [
	    		'model' => $model
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionItems( $id, $type = null ) {

		// Find Model
		$gallery 			= GalleryService::findById( $id );
		$this->returnUrl	= Url::previous( 'galleries' );

		// Update/Render if exist
		if( isset( $gallery ) ) {

	    	return $this->render( '@cmsgears/module-core/admin/views/gallery/items', [
	    		'gallery' => $gallery,
	    		'items' => $gallery->files
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>