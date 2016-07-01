<?php
namespace cmsgears\core\common\services\interfaces\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface ISiteService extends \cmsgears\core\common\services\interfaces\base\ISluggableService {

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public function getAttributeMapBySlugType( $slug, $type );

	public function getAttributeNameValueMapBySlugType( $slug, $type );

    // Read - Lists ----

    // Read - Maps -----

	// Create -------------

	// Update -------------

	// Delete -------------

}

?>