<?php
namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\CmgFile;
use cmsgears\core\common\models\resources\Category;

use cmsgears\core\admin\services\resources\CategoryService;
use cmsgears\core\admin\services\resources\OptionService;

abstract class DropdownController extends Controller {

	protected $type;
	protected $title;

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->returnUrl	= Url::previous( 'categories' );

		$this->type 		= CoreGlobal::TYPE_COMBO;
		$this->title 		= 'Dropdown';
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
	                'create'  => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'update'  => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'delete'  => [ 'permission' => CoreGlobal::PERM_CORE ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index' => [ 'get' ],
	                'all' => [ 'get' ],
	                'create'  => [ 'get', 'post' ],
	                'update'  => [ 'get', 'post' ],
	                'delete'  => [ 'get', 'post' ]
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

	    return $this->render( '@cmsgears/module-core/admin/views/dropdown/all', [

			'dataProvider' => $dataProvider,
    		'title' => $this->title
	    ]);
	}

	public function actionCreate() {

		$model			= new Category();
		$model->siteId	= Yii::$app->cmgCore->siteId;
		$model->type 	= $this->type;

		$model->setScenario( 'create' );

		if( $model->load( Yii::$app->request->post(), 'Category' )  && $model->validate() ) {

			if( CategoryService::create( $model ) ) {

				return $this->redirect( $this->returnUrl );
			}
		}

    	return $this->render( '@cmsgears/module-core/admin/views/dropdown/create', [
    		'model' => $model,
    		'title' => $this->title
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

				if( CategoryService::update( $model ) ) {


					return $this->redirect( $this->returnUrl );
				}
			}

	    	return $this->render( '@cmsgears/module-core/admin/views/dropdown/update', [
	    		'model' => $model,
    			'title' => $this->title
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

				$categoryOptions	= OptionService::findByCategoryId( $id );

				if( isset( $categoryOptions ) ) {

					foreach( $categoryOptions as $option ) {

						try {

					    	OptionService::delete( $option );
					    }
					    catch( Exception $e) {

						    throw new HttpException( 409,  Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  );
						}
					}
				}

				if( CategoryService::delete( $model ) ) {

					return $this->redirect( $this->returnUrl );
				}
			}

	    	return $this->render( '@cmsgears/module-core/admin/views/dropdown/delete', [
	    		'model' => $model,
    			'title' => $this->title
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>