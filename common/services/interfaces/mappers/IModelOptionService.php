<?php
namespace cmsgears\core\common\services\interfaces\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface IModelOptionService extends \cmsgears\core\common\services\interfaces\base\IMapperService {

	// Data Provider ------

	// Read ---------------

	public function getModelCounts( $parentType, $categorySlug );

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	// Update -------------

	public function bindOptions( $binder, $parentType );

	// Delete -------------

}
