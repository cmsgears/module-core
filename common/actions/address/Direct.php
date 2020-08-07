<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\address;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * The Direct action creates or update the address of models having addressId column.
 *
 * @since 1.0.0
 */
class Direct extends \cmsgears\core\common\base\Action {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $viewPath = '@cmsgears/module-core/admin/views/address';

	public $scenario = 'location';

	public $returnUrl = 'all';

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

	// Direct --------------------------------

	public function run() {

		// Views
        $this->controller->setViewPath( $this->viewPath );

		$model = $this->model;

		if( isset( $model ) ) {

			$address = $this->addressService->getModelObject();

			// Need the overriden model address, hence discarded $model->address to get the address model.
			if( isset( $model->addressId ) ) {

				$address = $this->addressService->getById( $model->addressId );
			}

			if( isset( $this->scenario ) ) {

				$address->setScenario( $this->scenario );

				if( $this->scenario == 'location' && $address->hasAttribute( 'geoPoint' ) && empty( $address->geoPoint ) ) {

					$address->geoPoint = new Expression( "ST_GeometryFromText( CONCAT( 'POINT(', 0, ' ', 0, ')' ) )" );
				}
			}

			if( $address->load( Yii::$app->request->post(), $address->getClassName() ) && $address->validate() ) {

				if( empty( $model->address ) ) {

					$address = $this->addressService->create( $address );

					$this->controller->modelService->linkModel( $model, 'addressId', $address );
				}
				else {

					$this->addressService->update( $address );
				}

				return $this->controller->redirect( $this->returnUrl );
			}

			$countriesMap	= $this->countryService->getIdNameMap( [ 'default' => true ] );
			$countryId		= !empty( $address->countryId ) ? $address->countryId : key( $countriesMap );
			$provincesMap	= $this->provinceService->getMapByCountryId( $countryId, [ 'default' => true, 'defaultValue' => Yii::$app->core->provinceLabel ] );
			$provinceId		= !empty( $address->provinceId ) ? $address->provinceId : key( $provincesMap );
			$regionsMap		= $this->regionService->getMapByProvinceId( $provinceId, [ 'default' => true, 'defaultValue' => Yii::$app->core->regionLabel ] );

			return $this->controller->render( 'address', [
				'model' => $model,
				'address' => $address,
				'countriesMap' => $countriesMap,
				'provincesMap' => $provincesMap,
				'regionsMap' => $regionsMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
