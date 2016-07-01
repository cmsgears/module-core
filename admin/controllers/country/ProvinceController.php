<?php
namespace cmsgears\core\admin\controllers\country;

// Yii Imports
use \Yii;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\base\Exception;
use yii\web\HttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Province;

class ProvinceController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $countryService;

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		$this->crudPermission 	= CoreGlobal::PERM_CORE;
		$this->modelService		= Yii::$app->factory->get( 'provinceService' );
		$this->sidebar 			= [ 'parent' => 'sidebar-core', 'child' => 'country' ];

		$this->returnUrl		= Url::previous( 'provinces' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/country/province/all' ], true );

		$this->countryService	= Yii::$app->factory->get( 'countryService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->core->getRbacFilterClass(),
                'actions' => [
	                'all'  => [ 'permission' => $this->crudPermission ],
	                'create'  => [ 'permission' => $this->crudPermission ],
	                'update'  => [ 'permission' => $this->crudPermission ],
	                'delete'  => [ 'permission' => $this->crudPermission ]
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

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ProvinceController --------------------

	public function actionAll( $id ) {

		$dataProvider	= $this->modelService->getPage( [ 'conditions' => [ 'countryId' => $id ] ] );

		Url::remember( [ 'country/province/all?id=' . $id ], 'provinces' );

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'countryId' => $id
		]);
	}

	public function actionCreate( $id ) {

		$model				= new Province();
		$model->countryId	= $id;

		if( $model->load( Yii::$app->request->post(), 'Province' )  && $model->validate() ) {

			$this->modelService->create( $model );

			return $this->redirect( $this->returnUrl );
		}

    	return $this->render( 'create', [
    		'model' => $model
    	]);
	}

	public function actionUpdate( $id ) {

		$model		= $this->modelService->getById( $id );

		$model->setScenario( 'update' );

		if( $model->load( Yii::$app->request->post(), 'Province' )  && $model->validate() ) {

			$this->modelService->update( $model );

			return $this->redirect( $this->returnUrl );
		}

    	return $this->render( 'update', [
    		'model' => $model
    	]);
	}

	public function actionDelete( $id ) {

		// Find Model
		$model	= $this->modelService->getById( $id );

		// Delete if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), 'Province' )  && $model->validate() ) {

				try {

			    	$this->modelService->delete( $model );

					return $this->redirect( $this->returnUrl );
			    }
			    catch( Exception $e ) {

				    throw new HttpException(409,  Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  );
				}
			}

			// Render view
	    	return $this->render( 'delete', [
	    		'model' => $model
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>