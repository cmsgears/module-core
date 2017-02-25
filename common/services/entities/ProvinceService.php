<?php
namespace cmsgears\core\common\services\entities;

use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\Province;

use cmsgears\core\common\services\interfaces\entities\IProvinceService;

class ProvinceService extends \cmsgears\core\common\services\base\EntityService implements IProvinceService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\entities\Province';

	public static $modelTable	= CoreTables::TABLE_PROVINCE;

	public static $parentType	= CoreGlobal::TYPE_PROVINCE;

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

	// ProvinceService -----------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$sort = new Sort([
			'attributes' => [
				'name' => [
					'asc' => [ 'name' => SORT_ASC ],
					'desc' => ['name' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Name'
				],
				'slug' => [
					'asc' => [ 'slug' => SORT_ASC ],
					'desc' => ['slug' => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Slug'
				]
			]
		]);

		$config[ 'sort' ] = $sort;

		return parent::findPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	public function getByCode( $code ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findByCode( $code );
	}

	public function getByCountryIdCode( $countryId, $code ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findByCountryIdCode( $countryId, $code );
	}

	// Read - Lists ----

	public function getListByCountryId( $countryId ) {

		return self::findIdNameList( [ 'conditions' => [ 'countryId' => $countryId ] ] );
	}

	// Read - Maps -----

	public function getMapByCountryId( $countryId ) {

		return self::findIdNameMap( [ 'conditions' => [ 'countryId' => $countryId ] ] );
	}

	public function getIsoNameMapByCountryId( $countryId ) {

		return self::findNameValueMap( [ 'nameColumn' => 'iso', 'valueColumn' => 'name', 'conditions' => [ 'countryId' => $countryId ] ] );
	}

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'code', 'iso', 'name' ];

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ProvinceService -----------------------

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
