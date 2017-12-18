<?php
namespace cmsgears\core\common\services\interfaces\entities;

// CMG Imports
use cmsgears\core\common\models\entities\Theme;

use cmsgears\core\common\services\interfaces\base\INameService;
use cmsgears\core\common\services\interfaces\base\ISlugService;

interface IThemeService extends INameService, ISlugService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Create -------------

	// Update -------------

	public function makeDefault( Theme $model, $config = [] );

	// Delete -------------

}
