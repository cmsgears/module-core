<?php
namespace cmsgears\core\admin\controllers\province;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class CityController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $provinceService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->crudPermission	= CoreGlobal::PERM_CORE;
		$this->modelService		= Yii::$app->factory->get( 'cityService' );

		$this->provinceService	= Yii::$app->factory->get( 'provinceService' );
		$this->sidebar			= [ 'parent' => 'sidebar-core', 'child' => 'country' ];

		$this->returnUrl		= Url::previous( 'cities' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/province/city/all' ], true );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ProvinceController --------------------

	public function actionAll( $pid ) {

		$dataProvider	= $this->modelService->getPage( [ 'conditions' => [ 'provinceId' => $pid ] ] );

		Url::remember( [ "province/city/all?pid=$pid" ], 'cities' );

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'provinceId' => $pid
		]);
	}

	public function actionCreate( $pid ) {

		$province = $this->provinceService->getById( $pid );

		if( isset( $province ) ) {

			$modelClass			= $this->modelService->getModelClass();
			$model				= new $modelClass;
			$model->countryId	= $province->countryId;
			$model->provinceId	= $pid;

			if( $model->load( Yii::$app->request->post(), $model->getClassName() )	&& $model->validate() ) {

				$this->modelService->create( $model );

				return $this->redirect( $this->returnUrl );
			}

			return $this->render( 'create', [
				'model' => $model
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model	= $this->modelService->getById( $id );

		// Update if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->modelService->update( $model );

				return $this->redirect( $this->returnUrl );
			}

			// Render view
			return $this->render( 'update', [
				'model' => $model
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model	= $this->modelService->getById( $id );

		// Delete if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				try {

					$this->modelService->delete( $model );

					return $this->redirect( $this->returnUrl );
				}
				catch( Exception $e ) {

					throw new HttpException( 409,  Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  );
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