<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\location;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\base\Action;

/**
 * The Single action creates or update the location.
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
	protected $locationService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->countryService	= Yii::$app->factory->get( 'countryService' );
		$this->provinceService 	= Yii::$app->factory->get( 'provinceService' );
		$this->regionService 	= Yii::$app->factory->get( 'regionService' );
		$this->locationService	= Yii::$app->factory->get( 'locationService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Create --------------------------------

	public function run( $pid ) {

		$parentService	= $this->controller->modelService;
		$parent			= $this->controller->modelService->getById( $pid );

		if( isset( $parent ) ) {

			$model = $this->locationService->getModelObject();

			// Need the overriden model location, hence discarded $parent->location to get the location model.
			if( isset( $parent->locationId ) ) {

				$model = $this->locationService->getById( $parent->locationId );
			}

			if( isset( $this->scenario ) ) {

				$model->setScenario( $this->scenario );

				if( $this->scenario == 'location' && $model->hasAttribute( 'geoPoint' ) && empty( $model->geoPoint ) ) {

					$model->geoPoint = new Expression( "ST_GeometryFromText( CONCAT( 'POINT(', 0, ' ', 0, ')' ) )" );
				}
			}

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				if( empty( $parent->location ) ) {

					$location = $this->locationService->create( $model );

					$parent->locationId = $location->id;

					$parentService->update( $parent );
				}
				else {

					$this->locationService->update( $model );
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
