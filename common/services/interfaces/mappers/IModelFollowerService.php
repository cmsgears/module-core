<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\interfaces\mappers;

// CMG Imports
use cmsgears\core\common\services\interfaces\base\IModelMapperService;

/**
 * IModelFollowerService provide service methods for follower mapper.
 *
 * @since 1.0.0
 */
interface IModelFollowerService extends IModelMapperService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getByFollower( $parentId, $parentType, $config = [] );

	public function getFollowing( $parentType, $config = [] );

	// Read - Lists ----

    public function getFollowersIdList( $parentId, $parentType, $config = [] );

    public function getFollowingIdList( $parentType, $config = [] );

    public function getLikeIdList( $parentType, $config = [] );

    public function getDisikeIdList( $parentType, $config = [] );

    public function getFollowIdList( $parentType, $config = [] );

    public function getWishlistIdList( $parentType, $config = [] );

	// Read - Maps -----

	// Read - Others ---

	public function getFollowCount( $parentType, $config = [] );

	public function getFollowersCount( $parentId, $parentType, $config = [] );

	public function getStatusCounts( $parentId, $parentType, $config = [] );

	// Create -------------

	// Update -------------

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
