<?php
namespace cmsgears\forms\admin\controllers\form;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\forms\common\config\FormsGlobal;

use cmsgears\forms\common\models\entities\FormField;

use cmsgears\forms\admin\services\FormService;
use cmsgears\forms\admin\services\FormFieldService;

class FieldController extends \cmsgears\core\admin\controllers\BaseController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'index'  => [ 'permission' => FormsGlobal::PERM_FORM ],
	                'all'    => [ 'permission' => FormsGlobal::PERM_FORM ],
	                'create' => [ 'permission' => FormsGlobal::PERM_FORM ],
	                'update' => [ 'permission' => FormsGlobal::PERM_FORM ],
	                'delete' => [ 'permission' => FormsGlobal::PERM_FORM ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index'  => [ 'get' ],
	                'all'   => [ 'get' ],
	                'create' => [ 'get', 'post' ],
	                'update' => [ 'get', 'post' ],
	                'delete' => [ 'get', 'post' ]
                ]
            ]
        ];
    }

	// PageController --------------------

	public function actionIndex() {

		$this->redirect( [ 'all' ] );
	}

	public function actionAll( $formid ) {

		$dataProvider = FormFieldService::getPaginationByFormId( $formid );

	    return $this->render( 'all', [
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

				$this->redirect( [ "all?formid=$formid" ] );
			}
		}

		return $this->render( 'create', [
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

					$this->redirect( [ "all?formid=$model->formId" ] );
				}
			}

	    	return $this->render( 'update', [
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

					$this->redirect( [ "all?formid=$model->formId" ] );
				}
			}

	    	return $this->render( 'delete', [
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