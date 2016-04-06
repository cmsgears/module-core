<?php
namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\Form;

use cmsgears\core\admin\services\entities\TemplateService;
use cmsgears\core\admin\services\resources\FormService;

abstract class FormController extends Controller {

	protected $type;
	protected $submits;
	protected $templateType;

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->returnUrl	= Url::previous( 'forms' );

		$this->type			= CoreGlobal::TYPE_SYSTEM;
		$this->submits		= true;
		$this->templateType	= CoreGlobal::TYPE_FORM;
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

	// FormController --------------------

	public function actionIndex() {

		$this->redirect( 'all' );
	}

	public function actionAll() {

		$dataProvider = FormService::getPaginationByType( $this->type );

	    return $this->render( '@cmsgears/module-core/admin/views/form/all', [
	         'dataProvider' => $dataProvider,
	         'submits' => $this->submits
	    ]);
	}

	public function actionCreate() {

		$model			= new Form();
		$model->type 	= $this->type;
		$model->siteId	= Yii::$app->cmgCore->siteId;

		$model->setScenario( 'create' );

		if( $model->load( Yii::$app->request->post(), 'Form' ) && $model->validate() ) {

			if( FormService::create( $model ) ) {

				return $this->redirect( $this->returnUrl );
			}
		}

		$templatesMap	= TemplateService::getIdNameMapByType( $this->templateType, [ 'default' => true ] );

    	return $this->render( '@cmsgears/module-core/admin/views/form/create', [
    		'model' => $model,
    		'templatesMap' => $templatesMap,
    		'visibilityMap' => Form::$visibilityMap
    	]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model	= FormService::findById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$model->type = $this->type;

			$model->setScenario( 'update' );

			if( $model->load( Yii::$app->request->post(), 'Form' ) && $model->validate() ) {

				if( FormService::update( $model ) ) {

					return $this->redirect( $this->returnUrl );
				}
			}

			$templatesMap	= TemplateService::getIdNameMapByType( $this->templateType, [ 'default' => true ] );

	    	return $this->render( '@cmsgears/module-core/admin/views/form/update', [
	    		'model' => $model,
	    		'templatesMap' => $templatesMap,
	    		'visibilityMap' => Form::$visibilityMap
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model	= FormService::findById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), 'Form' ) ) {

				if( FormService::delete( $model ) ) {

					return $this->redirect( $this->returnUrl );
				}
			}

			$templatesMap	= TemplateService::getIdNameMapByType( $this->templateType, [ 'default' => true ] );

	    	return $this->render( '@cmsgears/module-core/admin/views/form/delete', [
	    		'model' => $model,
	    		'templatesMap' => $templatesMap,
	    		'visibilityMap' => Form::$visibilityMap
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>