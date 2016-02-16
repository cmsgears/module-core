<?php
namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Gallery;

use cmsgears\core\admin\services\GalleryService;
use cmsgears\core\admin\services\TemplateService;

abstract class GalleryController extends Controller {

	protected $type;
	protected $templateType;

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->returnUrl	= Url::previous( 'galleries' );

		$this->type			= CoreGlobal::TYPE_SITE;
		$this->templateType	= CoreGlobal::TYPE_GALLERY;
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

	// Default RBAC and Verbs
    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'index' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'all' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'create' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'update' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'items' => [ 'permission' => CoreGlobal::PERM_CORE ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index' => [ 'get' ],
	                'all' => [ 'get' ],
	                'create' => [ 'get', 'post' ],
	                'update' => [ 'get', 'post' ],
	                'delete' => [ 'get', 'post' ],
	                'items'  => [ 'get' ]
                ]
            ]
        ];
    }

	// GalleryController -----------------

	public function actionIndex() {

		return $this->redirect( 'all' );
	}
	
	public function actionAll() {

		$dataProvider = GalleryService::getPaginationByType( $this->type );

		Url::remember( [ 'gallery/all' ], 'galleries' );

	    return $this->render( '@cmsgears/module-core/admin/views/gallery/all', [
	         'dataProvider' => $dataProvider
	    ]);
	}

	public function actionCreate() {

		$model			= new Gallery();
		$model->type 	= $this->type;
		$model->siteId	= Yii::$app->cmgCore->siteId;
		
		$model->setScenario( 'create' );

		if( $model->load( Yii::$app->request->post(), 'Gallery' )  && $model->validate() ) {

			if( GalleryService::create( $model ) ) {

				return $this->redirect( $this->returnUrl );
			}
		}

		$templatesMap	= TemplateService::getIdNameMapByType( $this->templateType, [ 'default' => true ] );

    	return $this->render( '@cmsgears/module-core/admin/views/gallery/create', [
    		'model' => $model,
    		'templatesMap' => $templatesMap
    	]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model	= GalleryService::findById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$model->type 	= $this->type;

			$model->setScenario( 'update' );
	
			if( $model->load( Yii::$app->request->post(), 'Gallery' )  && $model->validate() ) {
	
				if( GalleryService::update( $model ) ) {

					return $this->redirect( $this->returnUrl );
				}
			}

			$templatesMap	= TemplateService::getIdNameMapByType( $this->templateType, [ 'default' => true ] );

	    	return $this->render( '@cmsgears/module-core/admin/views/gallery/update', [
	    		'model' => $model,
	    		'templatesMap' => $templatesMap
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model	= GalleryService::findById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			$model->type 	= $this->type;

			if( $model->load( Yii::$app->request->post(), 'Gallery' ) ) {

				if( GalleryService::delete( $model ) ) {

					return $this->redirect( $this->returnUrl );
				}
			}

			$templatesMap	= TemplateService::getIdNameMapByType( $this->templateType, [ 'default' => true ] );

	    	return $this->render( '@cmsgears/module-core/admin/views/gallery/delete', [
	    		'model' => $model,
	    		'templatesMap' => $templatesMap
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionItems( $id ) {

		// Find Model
		$gallery 		= GalleryService::findById( $id );

		// Update/Render if exist
		if( isset( $gallery ) ) {

			$gallery->type 	= $this->type;

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