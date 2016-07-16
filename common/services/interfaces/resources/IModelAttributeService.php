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

	public function getByNameType( $parentId, $parentType, $name, $type );

	public function initByNameType( $parentId, $parentType, $name, $type, $valueType = Attribute::VALUE_TYPE_TEXT );

    // Read - Lists ----

    // Read - Maps -----

	public function getNameValueMapByType( $parentId, $parentType, $type );

	public function getIdObjectMapByType( $parentId, $parentType, $type );

	public function getNameObjectMapByType( $parentId, $parentType, $type );

	// Create -------------

	// Update -------------

	// Delete -------------

}
