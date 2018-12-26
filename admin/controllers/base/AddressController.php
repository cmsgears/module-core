<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\admin\controllers\base\Controller;

/**
 * AddressController provides actions specific to model attributes having own meta table.
 *
 * @since 1.0.0
 */
abstract class AddressController extends Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $title;

	// Protected --------------

	protected $parentService;

	protected $addressService;

	protected $countryService;
	protected $provinceService;
	protected $regionService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Views
		$this->setViewPath( '@cmsgears/module-core/admin/views/maddress' );

		// Permission
		$this->crudPermission = CoreGlobal::PERM_CORE;

		$this->modelService = Yii::$app->factory->get( 'modelAddressService' );

		$this->addressService = Yii::$app->factory->get( 'addressService' );

		$this->countryService	= Yii::$app->factory->get( 'countryService' );
		$this->provinceService 	= Yii::$app->factory->get( 'provinceService' );
		$this->regionService 	= Yii::$app->factory->get( 'regionService' );
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
					'index'	 => [ 'permission' => $this->crudPermission ],
					'all'  => [ 'permission' => $this->crudPermission ],
					'create'  => [ 'permission' => $this->crudPermission ],
					'update'  => [ 'permission' => $this->crudPermission ],
					'delete'  => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'index' => [ 'get', 'post' ],
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

	// AddressController ---------------------

	public function actionAll( $pid ) {

		$modelClass = $this->modelService->getModelClass();

		$parent		= $this->parentService->getById( $pid );
		$parentType	= $this->parentService->getParentType();

		if( isset( $parent ) ) {

			$dataProvider = $this->modelAddressService->getPageByParent( $parent->id, $parentType );

			return $this->render( 'all', [
				'dataProvider' => $dataProvider,
				'parent' => $parent,
				'typeMap' => $modelClass::$typeMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionCreate( $pid ) {

		$parent = $this->parentService->getById( $pid );

		if( isset( $parent ) ) {

			$addressClass	= $this->addressService->getModelClass();
			$parentType		= $this->parentService->getParentType();

			$address	= new $addressClass;
			$model		= $this->modelService->getModelObject();

			if( $address->load( Yii::$app->request->post(), $address->getClassName() ) && $model->load( Yii::$app->request->post(), $model->getClassName() ) &&
				$address->validate() ) {

				$this->model = $this->modelService->createByParent( $address, [
					'parentId' => $parent->id, 'parentType' => $parentType, 'type' => $model->type ] );

				return $this->redirect( $this->returnUrl );
			}

			$countriesMap	= $this->countryService->getIdNameMap( [ 'default' => true ] );
			$countryId		= !empty( $address->countryId ) ? $address->countryId : key( $countriesMap );
			$provincesMap	= $this->provinceService->getMapByCountryId( $countryId, [ 'default' => true, 'defaultValue' => Yii::$app->core->provinceLabel ] );
			$provinceId		= !empty( $address->provinceId ) ? $address->provinceId : key( $provincesMap );
			$regionsMap		= $this->regionService->getMapByProvinceId( $provinceId, [ 'default' => true, 'defaultValue' => Yii::$app->core->regionLabel ] );

			return $this->render( 'create', [
				'address' => $address,
				'model' => $model,
				'parent' => $parent,
				'typeMap' => $modelClass::$typeMap,
				'countriesMap' => $countriesMap,
				'provincesMap' => $provincesMap,
				'regionsMap' => $regionsMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionUpdate( $id, $pid ) {

		$model	= $this->modelService->getById( $id );
		$parent = $this->parentService->getById( $pid );

		if( isset( $model ) && isset( $parent ) ) {

			$modelClass = $this->modelService->getModelClass();

			$address = $this->addressService->getById( $model->modelId );

			if( $address->load( Yii::$app->request->post(), $address->getClassName() ) && $model->load( Yii::$app->request->post(), $model->getClassName() ) &&
				$address->validate() && $model->validate() ) {

				$this->addressService->update( $address, [ 'admin' => true ] );

				$this->model = $this->modelService->update( $model, [ 'admin' => true ] );

				return $this->redirect( $this->returnUrl );
			}

			$countriesMap	= $this->countryService->getIdNameMap( [ 'default' => true ] );
			$countryId		= !empty( $address->countryId ) ? $address->countryId : key( $countriesMap );
			$provincesMap	= $this->provinceService->getMapByCountryId( $countryId, [ 'default' => true, 'defaultValue' => Yii::$app->core->provinceLabel ] );
			$provinceId		= !empty( $address->provinceId ) ? $address->provinceId : key( $provincesMap );
			$regionsMap		= $this->regionService->getMapByProvinceId( $provinceId, [ 'default' => true, 'defaultValue' => Yii::$app->core->regionLabel ] );

			return $this->render( 'update', [
				'address' => $address,
				'model' => $model,
				'parent' => $parent,
				'typeMap' => $modelClass::$typeMap,
				'countriesMap' => $countriesMap,
				'provincesMap' => $provincesMap,
				'regionsMap' => $regionsMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id, $pid ) {

		$model	= $this->modelService->getById( $id );
		$parent = $this->parentService->getById( $pid );

		if( isset( $model ) && isset( $parent ) ) {

			$modelClass = $this->modelService->getModelClass();

			$address = $this->addressService->getById( $model->modelId );

			if( $address->load( Yii::$app->request->post(), $address->getClassName() ) && $address->validate() ) {

				$this->model = $model;

				// Delete Address and Mappings
				$this->addressService->delete( $address, [ 'admin' => true ] );

				return $this->redirect( $this->returnUrl );
			}

			$countriesMap	= $this->countryService->getIdNameMap( [ 'default' => true ] );
			$countryId		= !empty( $address->countryId ) ? $address->countryId : key( $countriesMap );
			$provincesMap	= $this->provinceService->getMapByCountryId( $countryId, [ 'default' => true, 'defaultValue' => Yii::$app->core->provinceLabel ] );
			$provinceId		= !empty( $address->provinceId ) ? $address->provinceId : key( $provincesMap );
			$regionsMap		= $this->regionService->getMapByProvinceId( $provinceId, [ 'default' => true, 'defaultValue' => Yii::$app->core->regionLabel ] );

			return $this->render( 'delete', [
				'address' => $address,
				'model' => $model,
				'parent' => $parent,
				'typeMap' => $modelClass::$typeMap,
				'countriesMap' => $countriesMap,
				'provincesMap' => $provincesMap,
				'regionsMap' => $regionsMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
