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
 * The email verification flow of model.
 *
 * @since 1.0.0
 */
interface IVerifyEmail {

	/**
	 * Check whether email is verified.
	 *
	 * @return boolean
	 */
	public function isEmailVerified();

	/**
	 * Generate email verification token.
	 */
	public function generateVerifyToken();

	/**
	 * Check whether email verification token is valid.
	 *
	 * @return boolean
	 */
	public function isVerifyTokenValid( $token );

}
