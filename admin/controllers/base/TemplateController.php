<?php
namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Template;

use cmsgears\core\admin\services\entities\TemplateService;

abstract class TemplateController extends Controller {

	protected $type;

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->returnUrl	= Url::previous( 'templates' );

		$this->setViewPath( '@cmsgears/module-core/admin/views/template' );
	}

	// Instance Methods ---------------------------------------------

	// yii\base\Component -----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'all'  => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'create' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'update' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_CORE ]
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

	// BaseRoleController -----------------

	public function actionAll() {

		$dataProvider = TemplateService::getPaginationByType( $this->type );

	    return $this->render( 'all', [

			'dataProvider' => $dataProvider
	    ]);
	}

	public function actionCreate() {

		$model			= new Template();
		$model->type 	= $this->type;

		$model->setScenario( 'create' );

		if( $model->load( Yii::$app->request->post(), 'Template' )  && $model->validate() ) {

			if( TemplateService::create( $model ) ) {

				return $this->redirect( $this->returnUrl );
			}
		}

    	return $this->render( 'create', [
    		'model' => $model,
    		'renderers' => Yii::$app->templateSource->renderers
    	]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model		= TemplateService::findById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$model->setScenario( 'update' );

			if( $model->load( Yii::$app->request->post(), 'Template' )  && $model->validate() ) {

				if( TemplateService::update( $model ) ) {

					return $this->redirect( $this->returnUrl );
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
		$model	= TemplateService::findById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), 'Template' )  && $model->validate() ) {

				if( TemplateService::delete( $model ) ) {

					return $this->redirect( $this->returnUrl );
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