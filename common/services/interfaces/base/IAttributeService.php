<?php
namespace cmsgears\core\common\services\interfaces\base;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\Attribute;

interface IAttributeService extends IEntityService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public function getByType( $modelId, $type );

	public function getByName( $modelId, $name );

	public function getByNameType( $modelId, $type, $name );

	public function getOrInitByNameType( $modelId, $name, $type, $valueType = Attribute::VALUE_TYPE_TEXT );

    // Read - Lists ----

    // Read - Maps -----

	public function getNameValueMapByType( $modelId, $type );

	public function getObjectMapByType( $modelId, $type );

	// Create -------------

	// Update -------------

	// Delete -------------

}

?>