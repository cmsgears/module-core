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
 * The base model mapper service interface declares the methods available with all the model mapper services.
 *
 * @since 1.0.0
 */
interface IModelMapperService extends IMapperService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getAllByModelId( $modelId );

	public function getByModelId( $parentId, $parentType, $modelId );

	public function getByParent( $parentId, $parentType );

	public function getByParentId( $parentId );

	public function getByParentType( $parentType );

	// Models having active column

	public function getActiveByParent( $parentId, $parentType );

	public function getActiveByParentId( $parentId );

	public function getActiveByParentType( $parentType );

	public function getActiveByModelIdParentType( $modelId, $parentType );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Models having active column

	public function activate( $model );

	public function activateByModelId( $parentId, $parentType, $modelId );

	public function disable( $model );

	public function disableByModelId( $parentId, $parentType, $modelId, $delete = false );

	// Delete -------------

	public function deleteByParent( $parentId, $parentType );

	public function deleteByModelId( $modelId );

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}