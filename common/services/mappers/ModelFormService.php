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

use cmsgears\core\common\services\interfaces\resources\IFormService;
use cmsgears\core\common\services\interfaces\mappers\IModelFormService;

use cmsgears\core\common\services\base\ModelMapperService;

/**
 * ModelFormService provide service methods of form mapper.
 *
 * @since 1.0.0
 */
class ModelFormService extends ModelMapperService implements IModelFormService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\mappers\ModelForm';

	public static $modelTable	= CoreTables::TABLE_MODEL_FORM;

	public static $parentType	= null;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $formService;

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function __construct( IFormService $formService, $config = [] ) {

		$this->formService	= $formService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelFormService ----------------------

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

	// ModelFormService ----------------------

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
