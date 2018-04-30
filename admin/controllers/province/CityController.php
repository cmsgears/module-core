<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\admin\controllers\province;

// Yii Imports
use Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\admin\controllers\base\Controller;

/**
 * ProvinceController provides actions specific to province model.
 *
 * @since 1.0.0
 */
class CityController extends Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $provinceService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = CoreGlobal::PERM_CORE;

		// Config
		$this->apixBase = 'core/province/city';

		// Services
		$this->modelService		= Yii::$app->factory->get( 'cityService' );

		$this->provinceService	= Yii::$app->factory->get( 'provinceService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-core', 'child' => 'country' ];

		// Return Url
		$this->returnUrl = Url::previous( 'cities' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/province/city/all' ], true );

		// Country Url
		$countryUrl = Url::previous( 'countries' );
		$countryUrl = isset( $countryUrl ) ? $countryUrl : Url::toRoute( [ '/core/country/all' ], true );

		// Province Url
		$provinceUrl = Url::previous( 'provinces' );
		$provinceUrl = isset( $provinceUrl ) ? $provinceUrl : Url::toRoute( [ '/core/country/province/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [
				[ 'label' => 'Countries', 'url' =>  $countryUrl ],
				[ 'label' => 'Provinces', 'url' =>  $provinceUrl ],
			],
			'all' => [ [ 'label' => 'Cities' ] ],
			'create' => [ [ 'label' => 'Cities', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Cities', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Cities', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
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

		Url::remember( [ "province/city/all?pid=$pid" ], 'cities' );

		$dataProvider = $this->modelService->getPage( [ 'conditions' => [ 'provinceId' => $pid ] ] );

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'provinceId' => $pid
		]);
	}

	public function actionCreate( $pid ) {

		$province = $this->provinceService->getById( $pid );

		if( isset( $province ) ) {

			$model = $this->modelService->getModelObject();

			$model->countryId	= $province->countryId;
			$model->provinceId	= $pid;

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->create( $model );

				return $this->redirect( "all?pid=$pid" );
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
		$model = $this->modelService->getById( $id );

		// Update if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->update( $model );

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
		$model = $this->modelService->getById( $id );

		// Delete if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				try {

					$this->model = $model;

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
