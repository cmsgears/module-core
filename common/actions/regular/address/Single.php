<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\regular\address;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\base\Action;

/**
 * The Single action creates or update the address of models having addressId column.
 *
 * @since 1.0.0
 */
class Single extends Action {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// public $scenario = 'location';

	// Protected --------------

	protected $countryService;
	protected $provinceService;
	protected $regionService;
	protected $addressService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->countryService	= Yii::$app->factory->get( 'countryService' );
		$this->provinceService 	= Yii::$app->factory->get( 'provinceService' );
		$this->regionService 	= Yii::$app->factory->get( 'regionService' );
		$this->addressService	= Yii::$app->factory->get( 'addressService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Create --------------------------------

	public function run( $pid ) {

		$parentService = $this->controller->modelService;

		$parent = $this->controller->modelService->getById( $pid );

		if( isset( $parent ) ) {

			$model = $this->addressService->getModelObject();

			// Need the overriden model address, hence discarded $parent->address to get the address model.
			if( isset( $parent->addressId ) ) {

				$model = $this->addressService->getById( $parent->addressId );
			}

			if( isset( $this->scenario ) ) {

				$model->setScenario( $this->scenario );

				if( $this->scenario == 'location' && $model->hasAttribute( 'geoPoint' ) && empty( $model->geoPoint ) ) {

					$model->geoPoint = new Expression( "ST_GeometryFromText( CONCAT( 'POINT(', 0, ' ', 0, ')' ) )" );
				}
			}

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				if( empty( $parent->address ) ) {

					$address = $this->addressService->create( $model );

					$parent->addressId = $address->id;

					$parentService->update( $parent );
				}
				else {

					$this->addressService->update( $model );
				}

				return $this->controller->redirect( "all?pid=$parent->id" );
			}

			$countriesMap	= $this->countryService->getIdNameMap( [ 'default' => true ] );
			$countryId		= !empty( $model->countryId ) ? $model->countryId : key( $countriesMap );
			$provincesMap	= $this->provinceService->getMapByCountryId( $countryId, [ 'default' => true, 'defaultValue' => Yii::$app->core->provinceLabel ] );
			$provinceId		= !empty( $model->provinceId ) ? $model->provinceId : key( $provincesMap );
			$regionsMap		= $this->regionService->getMapByProvinceId( $provinceId, [ 'default' => true, 'defaultValue' => Yii::$app->core->regionLabel ] );

			return $this->controller->render( 'single', [
				'model' => $model,
				'parent' => $parent,
				'countriesMap' => $countriesMap,
				'provincesMap' => $provincesMap,
				'regionsMap' => $regionsMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}