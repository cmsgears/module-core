<?php
namespace cmsgears\core\common\services\interfaces\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface ICategoryService extends \cmsgears\core\common\services\interfaces\base\IHierarchyService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public function getByParentId( $id );

	public function getFeaturedByType( $type );

	public function getByName( $name, $first = false );

	public function getByType( $type );

	public function getBySlugType( $slug, $type );

	public function searchByName( $name, $config = [] );

    // Read - Lists ----

	public function getIdNameListByType( $type, $config = [] );

	public function getTopLevelIdNameListByType( $type, $config = [] );

	public function getTopLevelIdNameListById( $id, $config = [] );

	public function getLevelListByType( $type );

    // Read - Maps -----

	public function getIdNameMapByType( $type, $config = [] );

	// Create -------------

	// Update -------------

	// Delete -------------

}

?>