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
 * The interface IAuthor provide methods to find the model author and the user who last modified it.
 *
 * @since 1.0.0
 */
interface IAuthor {

	/**
	 * Returns the user who created the model.
	 *
	 * @return \cmsgears\core\common\models\entities\User
	 */
	public function getCreator();

	/**
	 * Returns the user who recently updated the model.
	 *
	 * @return \cmsgears\core\common\models\entities\User
	 */
	public function getModifier();

	/**
	 * Check whether given user created the model. In non-strict mode, it will check
	 * for currently logged in user, in case no arguments are provided.
	 *
	 * @return boolean
	 */
	public function isCreator( $user = null, $strict = false );

}
