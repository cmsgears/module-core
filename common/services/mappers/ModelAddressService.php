<?php
namespace cmsgears\core\common\services\mappers;

//Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Address;
use cmsgears\core\common\models\mappers\ModelAddress;

use cmsgears\core\common\services\interfaces\mappers\IModelAddressService;

use cmsgears\core\common\services\traits\MapperTrait;

/**
 * The class ModelAddressService is base class to perform database activities for ModelAddress Entity.
 */
class ModelAddressService extends \cmsgears\core\common\services\base\EntityService implements IModelAddressService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\mappers\ModelAddress';

	public static $modelTable	= CoreTables::TABLE_MODEL_ADDRESS;

	public static $parentType	= null;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $addressService;

	// Traits ------------------------------------------------------

	use MapperTrait;

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->addressService	= Yii::$app->factory->get( 'addressService' );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelFormService ----------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByType( $parentId, $parentType, $type, $first = false ) {

		return ModelAddress::findByType( $parentId, $parentType, $type, $first );
	}

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
		$address	= $this->addressService->create( $address );

		// Create Model Address
		$modelAddress				= new ModelAddress();

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
		$type		= isset( $config[ 'type' ] ) ? $config[ 'type' ] : null;
		$order		= isset( $config[ 'order' ] ) ? $config[ 'order' ] : 0;

		if( isset( $address->id ) && !empty( $address->id ) ) {

			$existingAddress	= $this->getByModelId( $parentId, $parentType, $address->id );

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
		$type		= isset( $config[ 'type' ] ) ? $config[ 'type' ] : null;
		$order		= isset( $config[ 'order' ] ) ? $config[ 'order' ] : 0;

		$existingAddress	= $this->getByType( $parentId, $parentType, $type, true );

		if( isset( $existingAddress ) ) {

			$addressToUpdate	= $existingAddress->model;

			$addressToUpdate->copyForUpdateFrom( $address, [ 'countryId', 'provinceId', 'line1', 'line2', 'line3', 'cityName', 'zip',
											'firstName', 'lastName', 'phone', 'email', 'fax', 'longitude', 'latitude', 'zoomLevel' ] );

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

		$shippingAddress	= new Address();

		$shippingAddress->copyForUpdateFrom( $address, [ 'countryId', 'provinceId', 'line1', 'line2', 'line3', 'cityName', 'zip', 'firstName', 'lastName', 'phone', 'email', 'fax' ] );

		return $this->create( $address, $config );
	}

	// Update -------------

	public function update( $modelAddress, $config = [] ) {

		// Update Address
		$this->addressService->update( $config[ 'address' ] );

		// Find existing Model Address
		$addressToUpdate	= $this->getById( $modelAddress->id );

		// Copy Attributes
		$addressToUpdate->copyForUpdateFrom( $modelAddress, [ 'type', 'order' ] );

		// Update Model Address
		$addressToUpdate->update();

		// Return updated Model Address
		return $addressToUpdate;
	}

	// Delete -------------

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
