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

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\services\interfaces\entities\IProvinceService;

use cmsgears\core\common\services\base\EntityService;

use cmsgears\core\common\services\traits\base\NameTrait;

/**
 * ProvinceService provide service methods of province model.
 *
 * @since 1.0.0
 */
class ProvinceService extends EntityService implements IProvinceService {

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

	use NameTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ProvinceService -----------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelClass		= static::$modelClass;
		$modelTable		= static::$modelTable;
		$countryTable	= CoreTables::TABLE_COUNTRY;

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'country' => [
					'asc' => [ "$countryTable.name" => SORT_ASC ],
					'desc' => [ "$countryTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Country'
				],
				'name' => [
					'asc' => [ "$modelTable.name" => SORT_ASC ],
					'desc' => [ "$modelTable.name" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'name'
				],
				'code' => [
					'asc' => [ "$modelTable.code" => SORT_ASC ],
					'desc' => [ "$modelTable.code" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Code'
				],
				'iso' => [
					'asc' => [ "$modelTable.iso" => SORT_ASC ],
					'desc' => [ "$modelTable.iso" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'ISO'
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

			$search = [ 'name' => "$modelTable.name", 'code' => "$modelTable.code" ];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
			'name' => "$modelTable.name", 'code' => "$modelTable.code", 'iso' => "$modelTable.iso"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	public function getByCode( $code ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findByCode( $code );
	}

	public function getAllByCode( $code ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findAllByCode( $code );
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

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

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
