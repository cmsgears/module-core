<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\validators;

// Yii Imports
use Yii;
use yii\validators\Validator;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class AlphaNumWithUnderscoreValidator extends Validator {

	private static $regex	= "/^[a-zA-Z_0-9]+$/";

	public function validateAttribute($model, $attribute) {

		if ( !preg_match( self::$regex, $model->$attribute ) ) {

			$this->addError( $model, $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_AN_U ) );
		}
	}

}
