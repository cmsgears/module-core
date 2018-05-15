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
use cmsgears\core\common\services\interfaces\resources\IData;

/**
 * IModelCommentService provide service methods for model comment.
 *
 * @since 1.0.0
 */
interface IModelCommentService extends IModelResourceService, IData {

	// Data Provider ------

	public function getPageByParent( $parentId, $parentType, $config = [] );

	public function getCommentPageByParent( $parentId, $parentType, $config = [] );

	public function getReviewPageByParent( $parentId, $parentType, $config = [] );

	public function getPageByParentType( $parentType, $config = [] );

	public function getPageByBaseId( $baseId, $config = [] );

	public function getPageForApproved( $config = [] );

	// Read ---------------

	// Read - Models ---

	public function getByUser( $parentId, $parentType );

	public function isExistByUser( $parentId, $parentType );

	public function getByBaseId( $baseId, $config = [] );

	public function isExistByEmail( $email );

	public function getByEmail( $email );

	public function getFeaturedByType( $parentId, $parentType, $type, $config = [] );

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function attachMedia( $model, $file, $mediaType, $parentType );

	// Update -------------

	public function updateStatus( $model, $status );

	public function approve( $model );

	public function block( $model );

	public function markSpam( $model );

	public function markTrash( $model );

	public function updateSpamRequest( $model, $value = true );

	public function updateDeleteRequest( $model, $value = true );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
