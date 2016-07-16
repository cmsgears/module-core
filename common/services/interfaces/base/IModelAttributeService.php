<?php
namespace cmsgears\core\common\services\interfaces\base;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\Attribute;

interface IModelAttributeService extends IEntityService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

    public function getIdAttributeMapByType( $model, $type );

	public function getNameAttributeMapByType( $model, $type );

	// Create -------------

	// Update -------------

	public function updateModelAttributes( $model, $attributes );

	// Delete -------------
}
