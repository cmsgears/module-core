<?php
namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\CmgFile;

use cmsgears\core\common\models\entities\Category;

use cmsgears\core\admin\services\CategoryService;

abstract class CategoryController extends Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->returnUrl	= Url::previous( 'categories' );
	}

	// Instance Methods --------------------------------------------

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
	                'all'  => [ 'get' ],
	                'create'  => [ 'get', 'post' ],
	                'update'  => [ 'get', 'post' ],
	                'delete'  => [ 'get', 'post' ]
                ]
            ]
        ];
    }

	// CategoryController -----------------

	public function actionAll( $type ) {

		$dataProvider = CategoryService::getPaginationByType( $type );

	    return $this->render( '@cmsgears/module-core/admin/views/category/all', [
			'dataProvider' => $dataProvider
	    ]);
	}

	public function actionCreate( $type ) {

		$model			= new Category();
		$model->siteId	= Yii::$app->cmgCore->siteId;
		$model->type 	= $type;
		$banner 		= CmgFile::loadFile( null, 'Banner' );
		$video	 		= CmgFile::loadFile( null, 'Video' );

		$model->setScenario( 'create' );

		if( $model->load( Yii::$app->request->post(), 'Category' )  && $model->validate() ) {

			if( CategoryService::create( $model, $banner, $video ) ) { 

				return $this->redirect( $this->returnUrl );
			} 
		} 
		
		$categoryMap	= CategoryService::getIdNameMapByType( $type, [ 'prepend' => [ [ 'value' => 'Choose Category', 'name' => 0 ] ] ] );

    	return $this->render( '@cmsgears/module-core/admin/views/category/create', [
    		'model' => $model, 
    		'banner' => $banner,
			'video' => $video,
    		'categoryMap' => $categoryMap
    	]);
	}	
 	
	public function actionUpdate( $id, $type ) {
		
		// Find Model
		$model	= CategoryService::findById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$model->type 	= $type; 
			$banner 		= CmgFile::loadFile( $model->banner, 'Banner' );
			$video	 		= CmgFile::loadFile( $model->video, 'Video' );

			$model->setScenario( 'update' );

			if( $model->load( Yii::$app->request->post(), 'Category' )  && $model->validate() ) {

				if( CategoryService::update( $model, $banner, $video ) ) {

					return $this->redirect( $this->returnUrl );
				}
			}

			$categoryMap	= CategoryService::getIdNameMapByType( $type, [
									'prepend' => [ [ 'value' => 'Choose Category', 'name' => 0 ] ],
									'filters' => [ [ 'not in', 'id', [ $id ] ] ]
								]);

	    	return $this->render( '@cmsgears/module-core/admin/views/category/update', [
	    		'model' => $model, 
	    		'banner' => $banner,
				'video' => $video,
	    		'categoryMap' => $categoryMap
	    	]);
		}
		
		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id, $type ) {

		// Find Model
		$model	= CategoryService::findById( $id );

		// Delete/Render if exist		
		if( isset( $model ) ) {

			$banner = $model->banner;
			$video 	= $model->video;

			if( $model->load( Yii::$app->request->post(), 'Category' )  && $model->validate() ) {

				if( CategoryService::delete( $model, $banner, $video ) ) {

					return $this->redirect( $this->returnUrl );
				}
			}

			$categoryMap	= CategoryService::getIdNameMapByType( $type, [ 'prepend' => [ [ 'value' => 'Choose Category', 'name' => 0 ] ] ] );

	    	return $this->render( '@cmsgears/module-core/admin/views/category/delete', [
	    		'model' => $model, 
	    		'banner' => $banner,
				'video' => $video,
	    		'categoryMap' => $categoryMap
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>