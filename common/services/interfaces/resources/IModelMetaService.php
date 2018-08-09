<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\interfaces\resources;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\IModelResourceService;

use cmsgears\core\common\models\base\Meta;

/**
 * IModelMetaService provide service methods for model meta.
 *
 * @since 1.0.0
 */
interface IModelMetaService extends IModelResourceService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByType( $parentId, $parentType, $type );

	public function getByNameType( $parentId, $parentType, $name, $type );

	public function initByNameType( $parentId, $parentType, $name, $type, $valueType = Meta::VALUE_TYPE_TEXT, $label = null );

	// Read - Lists ----

	// Read - Maps -----

	public function getNameValueMapByType( $parentId, $parentType, $type );

	public function getIdMetaMapByType( $parentId, $parentType, $type );

	public function getNameMetaMapByType( $parentId, $parentType, $type );

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function toggle( $model );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
