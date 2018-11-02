<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\resources;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\interfaces\resources\ILocationService;

use cmsgears\core\common\services\base\ResourceService;

/**
 * LocationService provide service methods of location model.
 *
 * @since 1.0.0
 */
class LocationService extends ResourceService implements ILocationService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\resources\Location';

	public static $parentType	= CoreGlobal::TYPE_LOCATION;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// LocationService -----------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function create( $model, $config = [] ) {

		$model->countryName		= isset( $model->country ) ? $model->country->name : null;
		$model->provinceName	= isset( $model->province ) ? $model->province->name : null;
		$model->regionName		= isset( $model->region ) ? $model->region->name : null;

		return parent::create( $model, $config );
	}

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'countryId', 'provinceId', 'regionId', 'cityId',
			'title', 'cityName', 'regionName', 'provinceName', 'countryName', 'zip', 'subZip',
			'longitude', 'latitude', 'zoomLevel'
		];

		$model->countryName		= isset( $model->country ) ? $model->country->name : null;
		$model->provinceName	= isset( $model->province ) ? $model->province->name : null;
		$model->regionName		= isset( $model->region ) ? $model->region->name : null;

        $config[ 'attributes' ] = $attributes;

		return parent::update( $model, $config );
	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete mapping
		Yii::$app->factory->get( 'modelLocationService' )->deleteByModelId( $model->id );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'model': {

				switch( $action ) {

					case 'delete': {

						$this->delete( $model );

						break;
					}
				}

				break;
			}
		}
	}

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// LocationService -----------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
