<?php
namespace cmsgears\core\common\services\interfaces\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\Attribute;

interface IModelAttributeService extends \cmsgears\core\common\services\interfaces\base\IEntityService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public function getByType( $parentId, $parentType, $type );

	public function getByTypeName( $parentId, $parentType, $type, $name );

	public function getOrInitByTypeName( $parentId, $parentType, $type, $name, $valueType = Attribute::VALUE_TYPE_TEXT );

    // Read - Lists ----

    // Read - Maps -----

	public function getNameValueMapByType( $parentId, $parentType, $type );

	public function getObjectMapByType( $parentId, $parentType, $type );

	// Create -------------

	// Update -------------

	// Delete -------------

}
