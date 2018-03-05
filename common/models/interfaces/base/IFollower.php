<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\interfaces\base;

/**
 * The interface IFollower provide globals and methods to link a model to another model based on interest.
 *
 * @since 1.0.0
 */
interface IFollower {

	// Pre-Defined Type
	const TYPE_LIKE		= 'like'; // User Likes
	const TYPE_DISLIKE	= 'dislike'; // User or Model Dislikes
	const TYPE_FOLLOW	= 'follow'; // User Followers
	const TYPE_WISHLIST	= 'wish'; // User who wish to have this model - specially if model is doing sales
}
