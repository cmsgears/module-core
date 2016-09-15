<?php
namespace cmsgears\core\common\services\interfaces\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

interface IFormFieldService extends \cmsgears\core\common\services\interfaces\base\IEntityService {

    // Data Provider ------

    public function getPageByFormId( $formId );

    // Read ---------------

    // Read - Models ---

    public function getByFormId( $formId );

    // Read - Lists ----

    // Read - Maps -----

    // Create -------------

    // Update -------------

    // Delete -------------

}
