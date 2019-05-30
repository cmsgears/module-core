<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\entities;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\interfaces\entities\ICityService;

use cmsgears\core\common\services\base\EntityService;

use cmsgears\core\common\services\traits\base\NameTrait;

/**
 * CityService provide service methods of city model.
 *
 * @since 1.0.0
 */
class CityService extends EntityService implements ICityService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\entities\City';

	public static $parentType = CoreGlobal::TYPE_CITY;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use NameTrait {

		searchByName as baseSearchByName;
	}

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CityService ---------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$countryTable	= Yii::$app->factory->get( 'countryService' )->getModelTable();
		$provinceTable	= Yii::$app->factory->get( 'provinceService' )->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'country' => [
					'asc' => [ "$countryTable.name" => SORT_ASC ],
					'desc' => [ "$countryTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Country'
				],
				'province' => [
					'asc' => [ "$provinceTable.name" => SORT_ASC ],
					'desc' => [ "$provinceTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Province'
				],
				'name' => [
					'asc' => [ "$modelTable.name" => SORT_ASC ],
					'desc' => [ "$modelTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'name'
				],
				'iso' => [
					'asc' => [ "$modelTable.iso" => SORT_ASC ],
					'desc' => [ "$modelTable.iso" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'ISO'
				],
				'type' => [
					'asc' => [ "$modelTable.type" => SORT_ASC ],
					'desc' => [ "$modelTable.type" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Type'
				],
				'postal' => [
					'asc' => [ "$modelTable.postal" => SORT_ASC ],
					'desc' => [ "$modelTable.postal" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Postal Code'
				],
				'zone' => [
					'asc' => [ "$modelTable.zone" => SORT_ASC ],
					'desc' => [ "$modelTable.zone" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Zone'
				],
				'tzone' => [
					'asc' => [ "$modelTable.timeZone" => SORT_ASC ],
					'desc' => [ "$modelTable.timeZone" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Time Zone'
				],
				'latitude' => [
					'asc' => [ "$modelTable.latitude" => SORT_ASC ],
					'desc' => [ "$modelTable.latitude" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Latitude'
				],
				'longitude' => [
					'asc' => [ "$modelTable.longitude" => SORT_ASC ],
					'desc' => [ "$modelTable.longitude" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Longitude'
				]
			]
		]);

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		// Query ------------

		if( !isset( $config[ 'query' ] ) ) {

			$config[ 'hasOne' ] = true;
		}

		// Filters ----------

		// Params
		$type = Yii::$app->request->getQueryParam( 'type' );

		// Filter - Type
		if( isset( $type ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = $type;
		}

		// Searching --------

		$searchCol = Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [
				'name' => "$modelTable.name",
				'code' => "$modelTable.code",
				'iso' => "$modelTable.iso",
				'postal' => "$modelTable.postal",
				'zone' => "$modelTable.zone"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name",
			'code' => "$modelTable.code",
			'iso' => "$modelTable.iso",
			'postal' => "$modelTable.postal",
			'zone' => "$modelTable.zone",
			'regions' => "$modelTable.regions",
			'zcodes' => "$modelTable.zipCodes"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	// Read - Models ---

	public function getUnique( $name, $countryId, $provinceId ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findUnique( $name, $countryId, $provinceId );
	}

	public function isUniqueExist( $name, $countryId, $provinceId ) {

		$modelClass	= self::$modelClass;

		return $modelClass::isUniqueExist( $name, $countryId, $provinceId );
	}

	public function getUniqueByZone( $name, $countryId, $provinceId, $zone ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findUniqueByZone( $name, $countryId, $provinceId, $zone );
	}

	public function isUniqueExistByZone( $name, $countryId, $provinceId, $zone ) {

		$modelClass	= self::$modelClass;

		return $modelClass::isUniqueExistByZone( $name, $countryId, $provinceId, $zone );
	}

	// Read - Lists ----

	public function searchByName( $name, $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$autoCache = isset( $config[ 'autoCache' ] ) ? $config[ 'autoCache' ] : false;

		$config[ 'columns' ] = isset( $config[ 'columns' ] ) ? $config[ 'columns' ] : [ "$modelTable.id", "$modelTable.name", "$modelTable.latitude", "$modelTable.longitude", "$modelTable.postal" ];

		if( $autoCache ) {

			$config[ 'query' ]	= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
			$config[ 'array' ]	= isset( $config[ 'array' ] ) ? $config[ 'array' ] : true;
			$config[ 'sort' ]	= isset( $config[ 'sort' ] ) ? $config[ 'sort' ] : [ 'name' => SORT_ASC ];

			//$config[ 'query' ]->andWhere( "MATCH(autoCache) AGAINST(:auto IN NATURAL LANGUAGE MODE)", [ ':auto' => $name ] );
          	$config[ 'query' ]->andWhere( "$modelTable.name like :name", [ ':name' => "$name%" ] );

			return static::searchModels( $config );
		}
		else {

			return $this->baseSearchByName( $name, $config );
		}
	}

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

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

	// CityService ---------------------------

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
