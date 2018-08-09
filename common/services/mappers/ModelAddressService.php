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

	private $addressService;

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->addressService = Yii::$app->factory->get( 'addressService' );
	}

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

	public function create( $address, $config = [] ) {

		$parentId	= $config[ 'parentId' ];
		$parentType = $config[ 'parentType' ];
		$type		= isset( $config[ 'type' ] ) ? $config[ 'type' ] : null;
		$order		= isset( $config[ 'order' ] ) ? $config[ 'order' ] : 0;

		// Create Address
		$address = $this->addressService->create( $address );

		// Create Model Address
		$modelAddress = $this->getModelObject();

		$modelAddress->modelId		= $address->id;
		$modelAddress->parentId		= $parentId;
		$modelAddress->parentType	= $parentType;
		$modelAddress->type			= $type;
		$modelAddress->order		= $order;

		$modelAddress->save();

		// Return Address
		return $modelAddress;
	}

	public function createOrUpdate( $address, $config = [] ) {

		$parentId	= $config[ 'parentId' ];
		$parentType = $config[ 'parentType' ];

		$type	= isset( $config[ 'type' ] ) ? $config[ 'type' ] : null;
		$order	= isset( $config[ 'order' ] ) ? $config[ 'order' ] : 0;

		if( isset( $address->id ) && !empty( $address->id ) ) {

			$existingAddress = $this->getFirstByParentModelId( $parentId, $parentType, $address->id );

			if( isset( $existingAddress ) ) {

				return $this->update( $existingAddress, [ 'address' => $address ] );
			}
		}
		else {

			return $this->create( $address, $config );
		}
	}

	public function createOrUpdateByType( $address, $config = [] ) {

		$parentId	= $config[ 'parentId' ];
		$parentType = $config[ 'parentType' ];

		$type	= isset( $config[ 'type' ] ) ? $config[ 'type' ] : null;
		$order	= isset( $config[ 'order' ] ) ? $config[ 'order' ] : 0;

		$existingAddress = $this->getByType( $parentId, $parentType, $type, true );

		if( isset( $existingAddress ) ) {

			$addressToUpdate = $existingAddress->model;

			$addressToUpdate->copyForUpdateFrom( $address, [
				'countryId', 'provinceId', 'regionId', 'cityId', 'line1', 'line2', 'line3',
				'countryName', 'provinceName', 'regionName', 'cityName', 'zip',
				'firstName', 'lastName', 'phone', 'email', 'fax', 'longitude', 'latitude', 'zoomLevel'
			]);

			$this->addressService->update( $addressToUpdate, $config );

			return $existingAddress;
		}
		else {

			return $this->create( $address, $config );
		}
	}

	public function createShipping( $address, $config = [] ) {

		$config[ 'type' ] = Address::TYPE_SHIPPING;

		return $this->create( $address, $config );
	}

	public function copyToShipping( $address, $config = [] ) {

		$config[ 'type' ]	= Address::TYPE_SHIPPING;

		$shippingAddress	= Yii::$app->get( 'addressService' )->getModelObject();

		$shippingAddress->copyForUpdateFrom( $address, [ 'countryId', 'provinceId', 'line1', 'line2', 'line3', 'cityName', 'zip', 'firstName', 'lastName', 'phone', 'email', 'fax' ] );

		return $this->create( $address, $config );
	}

	// Update -------------

	public function update( $modelAddress, $config = [] ) {

		// Update Address
		$this->addressService->update( $config[ 'address' ] );

		// Find existing Model Address
		$addressToUpdate = $this->getById( $modelAddress->id );

		// Copy Attributes
		$addressToUpdate->copyForUpdateFrom( $modelAddress, [ 'type', 'order', 'active' ] );

		// Update Model Address
		$addressToUpdate->update();

		// Return updated Model Address
		return $addressToUpdate;
	}

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
