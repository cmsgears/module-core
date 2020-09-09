<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\mappers;

// Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\resources\Address;

use cmsgears\core\common\services\interfaces\resources\IAddressService;
use cmsgears\core\common\services\interfaces\mappers\IModelAddressService;

/**
 * ModelAddressService provide service methods of address mapper.
 *
 * @since 1.0.0
 */
class ModelAddressService extends \cmsgears\core\common\services\base\ModelMapperService implements IModelAddressService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\mappers\ModelAddress';

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function __construct( IAddressService $addressService, $config = [] ) {

		$this->parentService = $addressService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelAddressService -------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$searchParam	= $config[ 'search-param' ] ?? 'keywords';
		$searchColParam	= $config[ 'search-col-param' ] ?? 'search';

		$defaultSort = isset( $config[ 'defaultSort' ] ) ? $config[ 'defaultSort' ] : [ 'id' => SORT_DESC ];

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$addressTable = $this->parentService->getModelTable();

		// Sorting ----------

		$sort = new Sort([
			'attributes' => [
				'id' => [
					'asc' => [ "$modelTable.id" => SORT_ASC ],
					'desc' => [ "$modelTable.id" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Id'
				],
				'title' => [
					'asc' => [ "$addressTable.title" => SORT_ASC ],
					'desc' => [ "$addressTable.title" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Title',
				],
				'line1' => [
					'asc' => [ "$addressTable.line1" => SORT_ASC ],
					'desc' => [ "$addressTable.line1" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Address 1',
				],
				'country' => [
					'asc' => [ "$addressTable.countryName" => SORT_ASC ],
					'desc' => [ "$addressTable.countryName" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Country',
				],
				'province' => [
					'asc' => [ "$addressTable.provinceName" => SORT_ASC ],
					'desc' => [ "$addressTable.provinceName" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => Yii::$app->core->provinceLabel,
				],
				'region' => [
					'asc' => [ "$addressTable.regionName" => SORT_ASC ],
					'desc' => [ "$addressTable.regionName" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => Yii::$app->core->regionLabel,
				],
				'city' => [
					'asc' => [ "$addressTable.cityName" => SORT_ASC ],
					'desc' => [ "$addressTable.cityName" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'City',
				],
	            'type' => [
	                'asc' => [ "$modelTable.type" => SORT_ASC ],
	                'desc' => [ "$modelTable.type" => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'Type'
	            ],
				'order' => [
					'asc' => [ "$modelTable.order" => SORT_ASC ],
					'desc' => [ "$modelTable.order" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Order'
				],
				'active' => [
					'asc' => [ "$modelTable.active" => SORT_ASC ],
					'desc' => [ "$modelTable.active" => SORT_DESC ],
					'default' => SORT_DESC,
					'label' => 'Active'
				]
			],
			'defaultOrder' => $defaultSort
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
		$type	= Yii::$app->request->getQueryParam( 'type' );
		$filter	= Yii::$app->request->getQueryParam( 'model' );

		// Filter - Type
		if( isset( $type ) ) {

			$config[ 'conditions' ][ "$modelTable.type" ] = $type;
		}

		// Filter - Model
		if( isset( $filter ) ) {

			switch( $filter ) {

				case 'active': {

					$config[ 'conditions' ][ "$modelTable.active" ] = true;

					break;
				}
				case 'disabled': {

					$config[ 'conditions' ][ "$modelTable.active" ] = false;

					break;
				}
			}
		}

		// Searching --------

		$searchCol		= Yii::$app->request->getQueryParam( $searchColParam );
		$keywordsCol	= Yii::$app->request->getQueryParam( $searchParam );

		$search = [
			'title' => "$addressTable.title",
			'line1' => "$addressTable.line1"
		];

		if( isset( $searchCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search[ $searchCol ];
		}
		else if( isset( $keywordsCol ) ) {

			$config[ 'search-col' ] = $config[ 'search-col' ] ?? $search;
		}

		// Reporting --------

		$config[ 'report-col' ]	= $config[ 'report-col' ] ?? [
			'title' => "$addressTable.title",
			'line1' => "$addressTable.line1",
			'order' => "$modelTable.order",
			'active' => "$modelTable.active"
		];

		// Result -----------

		return parent::getPage( $config );
	}

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createPrimary( $model, $config = [] ) {

		$model->type = Address::TYPE_PRIMARY;

		return $this->create( $model, $config );
	}

	public function createShipping( $model, $config = [] ) {

		$model->type = Address::TYPE_SHIPPING;

		return $this->create( $model, $config );
	}

	public function createBilling( $model, $config = [] ) {

		$model->type = Address::TYPE_BILLING;

		return $this->create( $model, $config );
	}

	// Update -------------

	// Delete -------------

	public function deleteWithParent( $model, $config = [] ) {

		$parent = $this->parentService->getById( $model->modelId );

		$this->parentService->delete( $parent, $config );
	}

	// Bulk ---------------

	protected function applyBulk( $model, $column, $action, $target, $config = [] ) {

		switch( $column ) {

			case 'model': {

				switch( $action ) {

					case 'activate': {

						$model->active = true;

						$model->update();

						break;
					}
					case 'disable': {

						$model->active = false;

						$model->update();

						break;
					}
					case 'delete': {

						$this->deleteWithParent( $model, $config );

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

	// ModelAddressService -------------------

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
