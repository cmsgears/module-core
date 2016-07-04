<?php
namespace cmsgears\core\common\services\interfaces\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface IOptionService extends \cmsgears\core\common\services\interfaces\base\IEntityService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public function getByCategoryId( $categoryId );

	public function getByCategoryName( $categoryName, $categoryType = CoreGlobal::TYPE_OPTION_GROUP );

	public function getByNameCategoryId( $name, $categoryId );

	public function getByNameCategoryName( $name, $categoryName, $categoryType = CoreGlobal::TYPE_OPTION_GROUP );

	public function getByValueCategoryName( $value, $categoryName, $categoryType = CoreGlobal::TYPE_OPTION_GROUP );

    // Read - Lists ----

    // Read - Maps -----

	public function getIdNameMapByCategoryId( $categoryId, $config = [] );

	public function getIdNameMapByCategorySlug( $categoryName, $config = [], $type = CoreGlobal::TYPE_OPTION_GROUP );

	public function getValueNameMapByCategoryId( $categoryId );

	public function getValueNameMapByCategoryName( $categoryName, $type = CoreGlobal::TYPE_OPTION_GROUP );

	public function getValueNameMapByCategorySlug( $categorySlug, $type = CoreGlobal::TYPE_OPTION_GROUP );

	// Create -------------

	// Update -------------

	// Delete -------------

}

?>