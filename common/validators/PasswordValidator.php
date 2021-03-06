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

class PasswordValidator extends Validator {

	/* '$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$'
		$ = beginning of string
		\S* = any set of characters
		(?=\S{8,}) = of at least length 8
		(?=\S*[a-z]) = containing at least one lowercase letter
		(?=\S*[A-Z]) = and at least one uppercase letter
		(?=\S*[\d]) = and at least one number
		(?=\S*[\W]) = and at least a special character (non-word characters)
		$ = end of the string

	const REGEX_PASSWORD_STRENGTH		= "$\S*(?=\S{5,})(?=\S*[a-z])(?=[A-Z]*)(?=\S*[\d])(?=\S*[\W])\S*$";
	 */

	private static $regex	= "$\S*(?=\S{5,})(?=[a-z]*)(?=[A-Z]*)\S*$";

	public function validateAttribute($model, $attribute) {

		if ( !preg_match_all( self::$regex, $model->$attribute ) ) {

			$this->addError( $model, $attribute, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_PASSWORD ) );
		}
	}

}
