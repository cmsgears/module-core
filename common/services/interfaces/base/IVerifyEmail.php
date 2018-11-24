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
 * IVerifyEmail declare the methods provided by service trait - [[\cmsgears\core\common\services\traits\base\VerifyEmailTrait]].
 *
 * @since 1.0.0
 */
interface IVerifyEmail {

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	/**
	 * Generate the verification token.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @return \cmsgears\org\common\models\base\ActiveRecord
	 */
	public function generateVerifyToken( $model );

	/**
	 * Validate the verification token and update model accordingly.
	 *
	 * @param \cmsgears\core\common\models\base\ActiveRecord $model
	 * @param string $token
	 * @return \cmsgears\org\common\models\base\ActiveRecord
	 */
	public function validateVerifyToken( $model, $token );

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

}
