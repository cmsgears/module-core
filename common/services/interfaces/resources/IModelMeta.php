<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\interfaces\resources;

/**
 * IModelMeta declare the methods provided by service trait - [[\cmsgears\core\common\services\traits\resources\ModelMetaTrait]].
 *
 * @since 1.0.0
 */
interface IModelMeta {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	public function getIdMetaMapByType( $model, $type );

	public function getNameMetaMapByType( $model, $type );

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateModelMetas( $model, $metas );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
