<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\interfaces\base;

// CMG Imports
use cmsgears\core\common\models\interfaces\base\IFollower;

/**
 * The base follower service interface declares the methods available with all the follower services.
 *
 * @since 1.0.0
 */
interface IFollowerService extends IMapperService {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public static function getByFollower( $modelId, $type = IFollower::TYPE_FOLLOW );

	// Read - Lists ----

    public function getFollowersIdList( $modelId );

    public function getFollowingIdList();

	// Read - Maps -----

	// Read - Others ---

	public function getFollowCount( $type = IFollower::TYPE_FOLLOW );

	public function getFollowersCount( $modelId, $type = IFollower::TYPE_FOLLOW );

	public function getStatusCounts( $modelId, $type = IFollower::TYPE_FOLLOW );

	// Create -------------

	// Update -------------

	public function toggleStatus( $model );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}