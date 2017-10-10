<?php
namespace cmsgears\core\common\validators;

// Yii Imports
use yii\validators\Validator;

class SanitizeFilter extends Validator {

	public function validateAttribute($model, $attribute) {

		$model->$attribute = filter_var( $model->$attribute, FILTER_SANITIZE_STRING );
	}
}
