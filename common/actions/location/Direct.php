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

/**
 * The Direct action creates or update the location of models having locationId column.
 *
 * @since 1.0.0
 */
class Direct extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $viewPath = '@cmsgears/module-core/admin/views/location';

	public $scenario = 'location';

	public $returnUrl = 'all';

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

	// Direct --------------------------------

	public function run() {

		// Views
        $this->controller->setViewPath( $this->viewPath );

		$model = $this->model;

		if( isset( $model ) ) {

			$location = $this->locationService->getModelObject();

			// Need the overriden model location, hence discarded $model->location to get the location model.
			if( isset( $model->locationId ) ) {

				$location = $this->locationService->getById( $model->locationId );
			}

			if( isset( $this->scenario ) ) {

				$location->setScenario( $this->scenario );

				if( $this->scenario == 'location' && $location->hasAttribute( 'geoPoint' ) && empty( $location->geoPoint ) ) {

					$location->geoPoint = new Expression( "ST_GeometryFromText( CONCAT( 'POINT(', 0, ' ', 0, ')' ) )" );
				}
			}

			if( $location->load( Yii::$app->request->post(), $location->getClassName() ) && $location->validate() ) {

				if( empty( $model->location ) ) {

					$location = $this->locationService->create( $location );

					$this->controller->modelService->linkModel( $model, 'locationId', $location );
				}
				else {

					$this->locationService->update( $location );
				}

				return $this->controller->redirect( $this->returnUrl );
			}

			$countriesMap	= $this->countryService->getIdNameMap( [ 'default' => true ] );
			$countryId		= !empty( $location->countryId ) ? $location->countryId : key( $countriesMap );
			$provincesMap	= $this->provinceService->getMapByCountryId( $countryId, [ 'default' => true, 'defaultValue' => Yii::$app->core->provinceLabel ] );
			$provinceId		= !empty( $location->provinceId ) ? $location->provinceId : key( $provincesMap );
			$regionsMap		= $this->regionService->getMapByProvinceId( $provinceId, [ 'default' => true, 'defaultValue' => Yii::$app->core->regionLabel ] );

			return $this->controller->render( 'location', [
				'model' => $model,
				'location' => $location,
				'countriesMap' => $countriesMap,
				'provincesMap' => $provincesMap,
				'regionsMap' => $regionsMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
