<?php
namespace cmsgears\core\admin\controllers\base\form;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\FormField;

use cmsgears\core\admin\services\resources\FormService;
use cmsgears\core\admin\services\resources\FormFieldService;

class FieldController extends \cmsgears\core\admin\controllers\base\Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->returnUrl	= Url::previous( 'fields' );
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
	                'delete'  => [ 'permission' => CoreGlobal::PERM_CORE ]
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

	// PageController --------------------

	public function actionAll( $formid ) {

		$dataProvider = FormFieldService::getPaginationByFormId( $formid );

	    return $this->render( '@cmsgears/module-core/admin/views/form/field/all', [
	         'dataProvider' => $dataProvider,
	         'formId' => $formid
	    ]);
	}

	public function actionCreate( $formid ) {

		$model			= new FormField();
		$model->formId	= $formid;

		$model->setScenario( 'create' );

		if( $model->load( Yii::$app->request->post(), 'FormField' ) && $model->validate() ) {

			if( FormFieldService::create( $model ) ) {

				return $this->redirect( [ "all?formid=$formid" ] );
			}
		}

		return $this->render( '@cmsgears/module-core/admin/views/form/field/create', [
			'model' => $model,
			'formId' => $formid,
			'typeMap' => FormField::$typeMap
		]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model		= FormFieldService::findById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$model->setScenario( 'update' );

			if( $model->load( Yii::$app->request->post(), 'FormField' ) && $model->validate() ) {

				if( FormFieldService::update( $model ) ) {

					return $this->redirect( [ "all?formid=$model->formId" ] );
				}
			}

	    	return $this->render( '@cmsgears/module-core/admin/views/form/field/update', [
				'model' => $model,
				'formId' => $model->formId,
				'typeMap' => FormField::$typeMap
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model	= FormFieldService::findById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), 'FormField' ) ) {

				if( FormFieldService::delete( $model ) ) {

					return $this->redirect( [ "all?formid=$model->formId" ] );
				}
			}

	    	return $this->render( '@cmsgears/module-core/admin/views/form/field/delete', [
				'model' => $model,
				'formId' => $model->formId,
				'typeMap' => FormField::$typeMap
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>