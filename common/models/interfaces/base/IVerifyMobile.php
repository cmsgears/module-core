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
 * The mobile verification flow of model.
 *
 * @since 1.0.0
 */
interface IVerifyMobile {

	/**
	 * Check whether mobile is verified.
	 *
	 * @return boolean
	 */
	public function isMobileVerified();

	/**
	 * Check whether otp is expired.
	 *
	 * @return boolean
	 */
	public function isOtpExpired();

}
