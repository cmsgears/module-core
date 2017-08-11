<?php
namespace cmsgears\core\common\services\interfaces\mappers;

interface IModelOptionService extends \cmsgears\core\common\services\interfaces\base\IMapperService {

	// Data Provider ------

	// Read ---------------

	public function getModelCounts( $parentType, $categorySlug );

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	// Update -------------

	public function toggle( $parentId, $parentType, $modelId );

	public function bindOptions( $binder, $parentType );

	// Delete -------------

}
