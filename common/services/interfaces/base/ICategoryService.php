<?php
namespace cmsgears\core\common\services\interfaces\base;

interface ICategoryService extends IEntityService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByCategoryId( $categoryId, $config = [] );

	public function getByCategoryIds( $ids, $config = [] );

	public function getFeaturedByCategoryId( $categoryId, $config = [] );

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	// Update -------------

	// Delete -------------

}
