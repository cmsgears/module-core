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
 * Useful for models which required high level of security while editing or updating
 * by site users.
 *
 * @since 1.0.0
 */
interface IOwner {

	/**
	 * Checks ownership for given user. In case user is not provided, current logged in user
	 * can be considered in case of non strict mode depending on model implementation.
	 *
	 * @param \cmsgears\core\common\models\entities\User $user
	 * @param boolean $strict
	 */
	public function isOwner( $user = null, $strict = false );

	/**
	 * Returns the corresponding user.
	 *
	 * @return \cmsgears\core\common\models\entities\User
	 */
	public function getUser();

	/**
	 * Returns the corresponding owner user.
	 *
	 * @return \cmsgears\core\common\models\entities\User
	 */
	public function getOwner();

	/**
	 * Check whether the model belongs to the given user.
	 *
	 * @param \cmsgears\core\common\models\entities\User $user
	 * @return boolean
	 */
	public function belongsToUser( $user );

}
