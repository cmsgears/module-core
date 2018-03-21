<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\mappers;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\services\interfaces\entities\IObjectService;
use cmsgears\core\common\services\interfaces\mappers\IModelObjectService;

use cmsgears\core\common\services\base\ModelMapperService;

/**
 * ModelObjectService provide service methods of object mapper.
 *
 * @since 1.0.0
 */
class ModelObjectService extends ModelMapperService implements IModelObjectService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\mappers\ModelObject';

	public static $modelTable	= CoreTables::TABLE_MODEL_OBJECT;

	public static $parentType	= null;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $objectService;

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function __construct( IObjectService $objectService, $config = [] ) {

		$this->objectService	= $objectService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelObjectService --------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ModelObjectService --------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
