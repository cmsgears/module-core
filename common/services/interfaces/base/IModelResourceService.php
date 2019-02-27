<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\interfaces\base;

/**
 * The base model resource service interface declares the methods available with all the model resource services.
 *
 * @since 1.0.0
 */
interface IModelResourceService extends IResourceService {

	// Data Provider ------

	public function getPageByParent( $parentId, $parentType, $config = [] );

	public function getPageByParentType( $parentType, $config = [] );

	// Read ---------------

	// Read - Models ---

	public function getByParent( $parentId, $parentType, $config = [] );

	public function getFirstByParent( $parentId, $parentType, $config = [] );

	public function getByParentId( $parentId, $config = [] );

	public function getByParentType( $parentType, $config = [] );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	public function deleteByParent( $parentId, $parentType );

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
