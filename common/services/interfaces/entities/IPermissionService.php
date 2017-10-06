<?php
namespace cmsgears\core\common\services\interfaces\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\interfaces\base\INameTypeService;
use cmsgears\core\common\services\interfaces\base\ISlugTypeService;

interface IPermissionService extends INameTypeService, ISlugTypeService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	public function getLeafIdNameListByType( $type, $config = [] );

	// Create -------------

	// Update -------------

	public function bindRoles( $binder );

	// Delete -------------

}
