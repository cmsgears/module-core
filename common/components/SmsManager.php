<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\components;

/**
 * The SMS Manager component provides methods to trigger message and OTP.
 *
 * @since 1.0.0
 */
class SmsManager extends \cmsgears\core\common\base\Component {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// SmsManager ----------------------------

	public function isActive() {

		// Adapter method

		return false;
	}

	// OTP --------------

	public function isOTP() {

		return $this->isActive() && $this->getOtpBalance() > 10;
	}

	public function getOtpBalance() {

		// Adapter method
	}

	public function sendOtp( $number, $otp, $templateSlug, $expiry = 10 ) {

		// Adapter method
	}

	public function reSendOtp( $number, $message, $otp ) {

		// Adapter method
	}

	public function generateOtp( $digits = null ) {

		$otp = 0;

		// Generate 6 digits OTP by default
		if( empty( $digits ) ) {

			$otp = random_int( 100000, 999999 );
		}

		return $otp;
	}

	// SMS --------------

	public function isSms() {

		return $this->isActive() && $this->getSmsBalance() > 10;
	}

	public function getSmsBalance() {

		// Adapter method
	}

	public function sendSms( $number, $message ) {

		// Adapter method
	}

}
