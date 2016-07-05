<?php
namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\Tag;

use cmsgears\core\common\services\resources\TagService;

abstract class TagController extends Controller {

	protected $type;

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->returnUrl	= Url::previous( 'tags' );

		$this->type			= CoreGlobal::TYPE_SITE;

        $this->setViewPath( '@cmsgears/module-core/admin/views/tag' );
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

	// TagController ----------------------

	public function actionIndex() {

		return $this->redirect( 'all' );
	}

	public function actionAll() {

		$dataProvider = TagService::getPaginationByType( $this->type );

	    return $this->render( 'all', [
			'dataProvider' => $dataProvider
	    ]);
	}

	public function actionCreate() {

		$model			= new Tag();
		$model->siteId	= Yii::$app->cmgCore->siteId;
		$model->type 	= $this->type;

		$model->setScenario( 'create' );

		if( $model->load( Yii::$app->request->post(), 'Tag' )  && $model->validate() ) {

			TagService::create( $model );

			return $this->redirect( $this->returnUrl );
		}

    	return $this->render( 'create', [
    		'model' => $model
    	]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model	= TagService::findById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$model->setScenario( 'update' );

			if( $model->load( Yii::$app->request->post(), 'Tag' )  && $model->validate() ) {

				TagService::update( $model );

				return $this->redirect( $this->returnUrl );
			}

	    	return $this->render( 'update', [
	    		'model' => $model
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model	= TagService::findById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), 'Tag' )  && $model->validate() ) {

				TagService::delete( $model );

				return $this->redirect( $this->returnUrl );
			}

	    	return $this->render( 'delete', [
	    		'model' => $model
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>