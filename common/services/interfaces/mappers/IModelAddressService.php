<?php
namespace cmsgears\core\common\services\interfaces\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface IModelAddressService extends \cmsgears\core\common\services\interfaces\base\IMapperService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByType( $parentId, $parentType, $type, $first = false );

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	public function createOrUpdate( $address, $config = [] ) ;

	public function createOrUpdateByType( $address, $config = [] );

	public function createShipping( $address, $config = [] );

	public function copyToShipping( $address, $config = [] );

	// Update -------------

	// Delete -------------

}
