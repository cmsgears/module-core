<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\base;

// Yii Import
use Yii;

// CMG Imports
use cmsgears\core\common\utilities\DateUtil;

/**
 * The email verification flow of model.
 *
 */
trait VerifyEmailTrait {

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

	// VerifyEmailTrait ----------------------

	/**
	 * @inheritdoc
	 */
	public function isEmailVerified() {

		return $this->emailVerified;
	}

	/**
	 * @inheritdoc
	 */
	public function generateVerifyToken() {

		$this->verifyToken = Yii::$app->security->generateRandomString();

		$this->verifyTokenValidTill	= DateUtil::addMillis( DateUtil::getDateTime(), Yii::$app->core->tokenValidity );
	}

	/**
	 * @inheritdoc
	 */
	public function isVerifyTokenValid( $token ) {

		return $this->verifyToken === $token;
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// VerifyEmailTrait ----------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
