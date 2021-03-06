<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\interfaces\base;

/**
 * IVerifyMobile declare the methods provided by service trait - [[\cmsgears\core\common\services\traits\base\VerifyMobileTrait]].
 *
 * @since 1.0.0
 */
interface IVerifyMobile {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	/**
	 * Set the OTP generated by SMS service.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param int $otp
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public function setOtp( $model, $otp );

	/**
	 * Validate the OTP generated by SMS service and the one sent by user to verify mobile number.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param int $otp
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public function verifyMobile( $model, $otp );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
