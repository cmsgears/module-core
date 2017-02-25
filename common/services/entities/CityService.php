<?php
namespace cmsgears\core\common\services\entities;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\City;

use cmsgears\core\common\services\interfaces\entities\ICityService;

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

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CountryService ------------------------

	// Data Provider ------

	// Read - Lists ----

	public function searchLike( $query, $province ) {

		$modelClass	= self::$modelClass;

		return $modelClass::find()->where( [ 'like', 'name', $query ] )
					->andWhere( 'provinceId=:provinceId' ,[ ":provinceId" =>  $province ] )
					->limit( 5 )
					->all();
	}

	public function getByNameCountryIdProvinceId( $name, $countryId, $provinceId ) {

		$modelClass	= self::$modelClass;

		return $modelClass::isExistByNameCountryIdProvinceId( $name, $countryId, $provinceId );
	}

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// CountryService ------------------------

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
