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

		$sort = new Sort([
			'attributes' => [
				'name' => [
					'asc' => [ 'name' => SORT_ASC ],
					'desc' => ['name' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				]
			]
		]);

		$config[ 'sort' ] = $sort;

		return parent::findPage( $config );
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
