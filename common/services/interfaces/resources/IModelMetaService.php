<?php
namespace cmsgears\core\common\services\interfaces\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\Meta;

interface IModelMetaService extends \cmsgears\core\common\services\interfaces\base\IEntityService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public function getByType( $parentId, $parentType, $type );

	public function getByNameType( $parentId, $parentType, $name, $type );

	public function initByNameType( $parentId, $parentType, $name, $type, $valueType = Meta::VALUE_TYPE_TEXT );

    // Read - Lists ----

    // Read - Maps -----

	public function getNameValueMapByType( $parentId, $parentType, $type );

	public function getIdMetaMapByType( $parentId, $parentType, $type );

	public function getNameMetaMapByType( $parentId, $parentType, $type );

	// Create -------------

	// Update -------------

	// Delete -------------

}
