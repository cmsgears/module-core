<?php
namespace cmsgears\core\common\services\interfaces\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\interfaces\base\INameTypeService;
use cmsgears\core\common\services\interfaces\base\ISlugTypeService;

interface IRoleService extends INameTypeService, ISlugTypeService {

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
