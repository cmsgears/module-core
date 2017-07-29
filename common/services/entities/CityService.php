<?php
namespace cmsgears\core\common\services\entities;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\services\interfaces\entities\ICityService;

use cmsgears\core\common\services\traits\NameTrait;

class CityService extends \cmsgears\core\common\services\base\EntityService implements ICityService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\entities\City';

	public static $modelTable	= CoreTables::TABLE_CITY;

	public static $parentType	= CoreGlobal::TYPE_CITY;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use NameTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CityService ---------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelClass		= static::$modelClass;
		$modelTable		= static::$modelTable;
		$countryTable	= CoreTables::TABLE_COUNTRY;
		$provinceTable	= CoreTables::TABLE_PROVINCE;

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
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
				'zone' => [
					'asc' => [ "$modelTable.zone" => SORT_ASC ],
					'desc' => [ "$modelTable.zone" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Zone'
				],
				'postal' => [
					'asc' => [ "$modelTable.postal" => SORT_ASC ],
					'desc' => [ "$modelTable.postal" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Postal Code'
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

		// Searching --------

		$searchCol	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [ 'name' => "$modelTable.name", 'zone' => "$modelTable.zone" ];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name", 'zone' => "$modelTable.zone", 'postal' => "$modelTable.postal"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	// Read - Lists ----

	public function getUnique( $name, $countryId, $provinceId, $zone = null ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findUnique( $name, $countryId, $provinceId, $zone );
	}

	public function isUniqueExist( $name, $countryId, $provinceId, $zone = null ) {

		$modelClass	= self::$modelClass;

		return $modelClass::isUniqueExist( $name, $countryId, $provinceId, $zone );
	}

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

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

	// Delete -------------

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
