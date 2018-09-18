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
use cmsgears\core\common\services\interfaces\resources\IFileService;
use cmsgears\core\common\services\interfaces\mappers\IModelFileService;

use cmsgears\core\common\services\base\ModelMapperService;

/**
 * ModelFileService provide service methods of file mapper.
 *
 * @since 1.0.0
 */
class ModelFileService extends ModelMapperService implements IModelFileService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass = '\cmsgears\core\common\models\mappers\ModelFile';

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $fileService;

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function __construct( IFileService $fileService, $config = [] ) {

		$this->fileService = $fileService;

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelFileService ----------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	/**
	 * @inheritdoc
	 */
	public function getByFileTag( $parentId, $parentType, $fileTag, $type = null ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByFileTag( $parentId, $parentType, $fileTag, $type );
	}

	/**
	 * @inheritdoc
	 */
	public function searchByFileTitle( $parentId, $parentType, $fileTitle ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByFileTitle( $parentId, $parentType, $fileTitle );
	}

	/**
	 * @inheritdoc
	 */
	public function getByFileType( $parentId, $parentType, $fileType ) {

		$modelClass	= static::$modelClass;

		return $modelClass::findByFileType( $parentId, $parentType, $fileType );
	}

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

	// ModelFileService ----------------------

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
