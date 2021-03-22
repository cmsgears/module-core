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
use yii\validators\Validator;

class SanitizeFilter extends Validator {

	public function validateAttribute($model, $attribute) {

		$model->$attribute = filter_var( $model->$attribute, FILTER_SANITIZE_STRING );
	}

}
