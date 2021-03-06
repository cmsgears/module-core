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

/**
 * It's a special case and works only when array of values need to be submitted for a form. The method load must be called before validate.
 */
class EmptyArrayValidator extends \yii\validators\Validator {

	public function validateAttribute( $model, $attribute ) {

		$submittedData = $model->submittedData;

		if( !isset( $submittedData[ $attribute ] ) || !is_array( $submittedData[ $attribute ] ) ) {

			$model->$attribute	= [];
		}
	}

}
