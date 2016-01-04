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

use cmsgears\core\common\models\entities\Form;

use cmsgears\core\admin\services\FormService;
use cmsgears\core\admin\services\TemplateService;

abstract class FormController extends Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
		
		$this->returnUrl	= Url::previous( 'forms' );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'all'    => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'create' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'update' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_CORE ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'all'   => [ 'get' ],
	                'create' => [ 'get', 'post' ],
	                'update' => [ 'get', 'post' ],
	                'delete' => [ 'get', 'post' ]
                ]
            ]
        ];
    }

	// FormController --------------------

	public function actionAll( $type = null, $submits = true ) {

		$dataProvider = FormService::getPaginationByType( $type );

	    return $this->render( '@cmsgears/module-core/admin/views/form/all', [
	         'dataProvider' => $dataProvider,
	         'submits' => $submits
	    ]);
	}

	public function actionCreate( $type = null ) {

		$model			= new Form();
		$model->type 	= $type;
		$model->siteId	= Yii::$app->cmgCore->siteId;

		$model->setScenario( 'create' );

		if( $model->load( Yii::$app->request->post(), 'Form' ) && $model->validate() ) {

			if( FormService::create( $model ) ) {

				$this->redirect( $this->returnUrl );
			}
		}

		$templatesMap	= TemplateService::getIdNameMapByType( CoreGlobal::TYPE_FORM );
		$templatesMap	= ArrayHelper::merge( [ '0' => 'Choose Template' ], $templatesMap );

    	return $this->render( '@cmsgears/module-core/admin/views/form/create', [
    		'model' => $model,
    		'templatesMap' => $templatesMap,
    		'visibilityMap' => Form::$visibilityMap
    	]);
	}

	public function actionUpdate( $id, $type = null ) {

		// Find Model
		$model	= FormService::findById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {
			
			$model->type = $type;

			$model->setScenario( 'update' );

			if( $model->load( Yii::$app->request->post(), 'Form' ) && $model->validate() ) {

				if( FormService::update( $model ) ) {

					$this->redirect( $this->returnUrl );
				}
			}

			$templatesMap	= TemplateService::getIdNameMapByType( CoreGlobal::TYPE_FORM );
			$templatesMap	= ArrayHelper::merge( [ '0' => 'Choose Template' ], $templatesMap );

	    	return $this->render( '@cmsgears/module-core/admin/views/form/update', [
	    		'model' => $model,
	    		'templatesMap' => $templatesMap,
	    		'visibilityMap' => Form::$visibilityMap
	    	]);
		}
		
		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id, $type = null ) {

		// Find Model
		$model	= FormService::findById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {
			
			$model->type = $type;

			if( $model->load( Yii::$app->request->post(), 'Form' ) ) {

				if( FormService::delete( $model ) ) {

					$this->redirect( $this->returnUrl );
				}
			}

			$templatesMap	= TemplateService::getIdNameMapByType( CoreGlobal::TYPE_FORM );
			$templatesMap	= ArrayHelper::merge( [ '0' => 'Choose Template' ], $templatesMap );

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