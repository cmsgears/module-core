<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\validators;

class CompareDateValidator extends \yii\validators\CompareValidator {

	public $enableClientValidation = false;

	protected function compareValues( $operator, $type, $value, $compareValue ) {

		if( $type === 'datetime' ) {

			$value = strtotime( $value );

			$compareValue = strtotime( $compareValue );
		}

		return parent::compareValues( $operator, 'number', $value, $compareValue );
	}

}
