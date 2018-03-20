<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\interfaces\mappers;

/**
 * The IFollower declare the methods implemented by FollowerTrait. It can be implemented
 * by entities, resources and models which need followers.
 *
 * @since 1.0.0
 */
interface IFollower {

	/**
	 * Return all the files associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\resources\User[]
	 */
	public function getFollowers();

	/**
	 * Return all the active files associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\resources\User[]
	 */
	public function getActiveFollowers();

	/**
	 * Return files associated with the parent for given mapping type.
	 *
	 * @param string $type
	 * @param boolean $active
	 * @return \cmsgears\core\common\models\resources\User[]
	 */
	public function getFollowersByType( $type, $active = true );

	/**
	 * Return the likes count.
	 *
	 * @return array
	 */
	public function getLikesCount( $active = true );

	/**
	 * Return the dislikes count.
	 *
	 * @return array
	 */
	public function getDislikesCount( $active = true );

	/**
	 * Return the followers count.
	 *
	 * @return array
	 */
	public function getFollowersCount( $active = true );

	/**
	 * Return the wishers count.
	 *
	 * @return array
	 */
	public function getWishersCount( $active = true );

	/**
	 * Check whether user liked the model.
	 *
	 * @return boolean
	 */
	public function isLiked();

	/**
	 * Check whether user disliked the model.
	 *
	 * @return boolean
	 */
	public function isDisliked();

	/**
	 * Check whether user follows the model.
	 *
	 * @return boolean
	 */
	public function isFollowing();

	/**
	 * Check whether user added the model on wishlist.
	 *
	 * @return boolean
	 */
	public function isWished();

}
