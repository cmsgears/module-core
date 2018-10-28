<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\base;

/**
 * The mobile verification flow of model.
 *
 */
trait VerifyMobileTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// VerifyMobileTrait ---------------------

	/**
	 * @inheritdoc
	 */
	public function isMobileVerified() {

		return $this->mobileVerified;
	}

	/**
	 * @inheritdoc
	 */
	public function isOtpExpired() {

		if( empty( $this->otp ) ) {

			return true;
		}

		$now = DateUtil::getDateTime();

		// OTP Expired
		return DateUtil::greaterThan( $this->otpValidTill, $now );
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// VerifyMobileTrait ---------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
