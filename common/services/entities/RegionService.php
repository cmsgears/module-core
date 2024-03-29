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

use cmsgears\core\common\services\interfaces\entities\IRegionService;

use cmsgears\core\common\services\traits\base\NameTrait;

/**
 * RegionService provide service methods of region model.
 *
 * @since 1.0.0
 */
class RegionService extends \cmsgears\core\common\services\base\EntityService implements IRegionService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\entities\Region';

	public static $parentType = CoreGlobal::TYPE_REGION;

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

	// RegionService -------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

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
			],
			'defaultOrder' => [
				'id' => SORT_DESC
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

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'name' => "$modelTable.name",
			'code' => "$modelTable.code",
			'iso' => "$modelTable.iso"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= $config[ 'report-col' ] ?? [
			'name' => "$modelTable.name",
			'code' => "$modelTable.code",
			'iso' => "$modelTable.iso"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	public function getByCountryId( $countryId ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findByCountryId( $countryId );
	}

	public function getByCountryIdCode( $countryId, $code ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findByCountryIdCode( $countryId, $code );
	}

	public function getByCountryIdIso( $countryId, $iso ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findByCountryIdIso( $countryId, $iso );
	}

	public function getByCountryIdProvinceIdName( $countryId, $provinceId, $name ) {

		$modelClass	= self::$modelClass;

		return $modelClass::findByCountryIdProvinceIdName( $countryId, $provinceId, $name );
	}

	// Read - Lists ----

	public function getIdNameListByProvinceId( $provinceId, $config = [] ) {

		$config[ 'conditions' ][] = [ 'provinceId' => $provinceId ];

		$config[ 'order' ] = 'name ASC';

		return self::findIdNameList( $config );
	}

	// Read - Maps -----

	public function getIdNameMapByProvinceId( $provinceId, $config = [] ) {

		$config[ 'conditions' ][] = [ 'provinceId' => $provinceId ];

		$config[ 'order' ] = 'name ASC';

		return self::findIdNameMap( $config );
	}

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [
			'code', 'iso', 'name', 'title'
		];

		return parent::update( $model, [
			'attributes' => $attributes
		]);
	}

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

	// RegionService -------------------------

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
