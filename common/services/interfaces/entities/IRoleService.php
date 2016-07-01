<?php
namespace cmsgears\core\common\services\interfaces\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface IRoleService extends \cmsgears\core\common\services\interfaces\base\INameSlugTypeService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	public function getIdNameMapByRoles( $roles );

	// Create -------------

	// Update -------------

	public function bindPermissions( $binder );

	// Delete -------------

}

?>