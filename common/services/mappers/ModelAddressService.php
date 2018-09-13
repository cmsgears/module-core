<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\mappers;

//Yii Imports
use Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\resources\Address;

use cmsgears\core\common\services\interfaces\mappers\IModelAddressService;

use cmsgears\core\common\services\base\ModelMapperService;

/**
 * ModelAddressService provide service methods of address mapper.
 *
 * @since 1.0.0
 */
class ModelAddressService extends ModelMapperService implements IModelAddressService {

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

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelFormService ----------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$addressTable = Yii::$app->factory->get( 'addressService' )->getModelTable();

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
			}
		}

		// Searching --------

		$searchCol = Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchCol ) ) {

			$search = [
				'title' => "address.title",
				'line1' => "address.line1"
			];

			$config[ 'search-col' ] = $search[ $searchCol ];
		}

		// Reporting --------

		$config[ 'report-col' ]	= [
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

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ModelFormService ----------------------

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
