<?php
namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Category;

use cmsgears\core\admin\services\CategoryService;

abstract class CategoryController extends Controller {

	protected $type;
	
	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->returnUrl	= Url::previous( 'categories' );

		$this->type			= CoreGlobal::TYPE_SITE;
        
        $this->setViewPath( "@cmsgears/module-core/admin/views/category" );
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
	                'delete' => [ 'permission' => CoreGlobal::PERM_CORE ],
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

	// CategoryController -----------------

	public function actionIndex() {

		return $this->redirect( 'all' );
	}
	
	public function actionAll() {

		$dataProvider = CategoryService::getPaginationByType( $this->type );

	    return $this->render( 'all', [
			'dataProvider' => $dataProvider
	    ]);
	}

	public function actionCreate() {

		$model			= new Category();
		$model->siteId	= Yii::$app->cmgCore->siteId;
		$model->type 	= $this->type;

		$model->setScenario( 'create' );

		if( $model->load( Yii::$app->request->post(), 'Category' )  && $model->validate() ) {

			CategoryService::create( $model );

			return $this->redirect( $this->returnUrl ); 
		}

		$categoryMap	= CategoryService::getIdNameMapByType( $this->type, [ 'prepend' => [ [ 'value' => 'Choose Category', 'name' => 0 ] ] ] );

    	return $this->render( 'create', [
    		'model' => $model,
    		'categoryMap' => $categoryMap
    	]);
	}	
 	
	public function actionUpdate( $id ) {
		
		// Find Model
		$model	= CategoryService::findById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$model->type 	= $this->type; 

			$model->setScenario( 'update' );

			if( $model->load( Yii::$app->request->post(), 'Category' )  && $model->validate() ) {

				CategoryService::update( $model );

				return $this->redirect( $this->returnUrl );
			}

			$categoryMap	= CategoryService::getIdNameMapByType( $this->type, [
									'prepend' => [ [ 'value' => 'Choose Category', 'name' => 0 ] ],
									'filters' => [ [ 'not in', 'id', [ $id ] ] ]
								]);

	    	return $this->render( 'update', [
	    		'model' => $model,
	    		'categoryMap' => $categoryMap
	    	]);
		}
		
		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model	= CategoryService::findById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), 'Category' )  && $model->validate() ) {

				CategoryService::delete( $model );

				return $this->redirect( $this->returnUrl );
			}

			$categoryMap	= CategoryService::getIdNameMapByType( $this->type, [ 'prepend' => [ [ 'value' => 'Choose Category', 'name' => 0 ] ] ] );

	    	return $this->render( 'delete', [
	    		'model' => $model,
	    		'categoryMap' => $categoryMap
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>