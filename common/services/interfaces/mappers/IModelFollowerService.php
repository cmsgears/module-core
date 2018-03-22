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
use cmsgears\core\common\models\interfaces\base\IFollower;

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

	public static function getByFollower( $parentId, $parentType, $type = IFollower::TYPE_FOLLOW );

	// Read - Lists ----

    public function getFollowersIdList( $parentId, $parentType );

    public function getFollowingIdList( $parentType );

	// Read - Maps -----

	// Read - Others ---

	public function getFollowCount( $parentType, $type = IFollower::TYPE_FOLLOW );

	public function getFollowersCount( $parentId, $parentType, $type = IFollower::TYPE_FOLLOW );

	public function getStatusCounts( $parentId, $parentType, $type = IFollower::TYPE_FOLLOW );

	// Create -------------

	// Update -------------

	public function toggleStatus( $model );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
