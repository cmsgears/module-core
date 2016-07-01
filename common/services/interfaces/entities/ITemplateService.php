<?php
namespace cmsgears\core\common\services\interfaces\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface ITemplateService extends \cmsgears\core\common\services\interfaces\base\INameSlugTypeService {

	// Data Provider ------

	public function getPageByType( $type, $config = [] );

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Create -------------

	// Update -------------

	// Delete -------------

}

?>