<?php
namespace cmsgears\core\common\services\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Address;
use cmsgears\core\common\models\mappers\ModelAddress;

use cmsgears\core\common\services\interfaces\resources\IAddressService;

/**
 * The class AddressService is base class to perform database activities for Address Entity.
 */
class AddressService extends \cmsgears\core\common\services\base\EntityService implements IAddressService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\resources\Address';

	public static $modelTable	= CoreTables::TABLE_ADDRESS;

	public static $parentType	= CoreGlobal::TYPE_ADDRESS;

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

	// AddressService ------------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		return parent::update( $model, [
			'attributes' => [ 'countryId', 'provinceId', 'title', 'line1', 'line2', 'line3', 'city', 'zip', 'subZip', 'firstName', 'lastName', 'phone', 'email', 'fax', 'website', 'longitude', 'latitude', 'zoomLevel' ]
		]);
 	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete dependencies
		ModelAddress::deleteByModelId( $model->id );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// AddressService ------------------------

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

?>