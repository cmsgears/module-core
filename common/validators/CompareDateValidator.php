<?php
namespace cmsgears\core\common\validators;

class CompareDateValidator extends \yii\validators\CompareValidator {

	public $enableClientValidation = false;

	protected function compareValues( $operator, $type, $value, $compareValue ) {

		if( $type === 'datetime' ) {

			$value			= strtotime( $value );
			$compareValue	= strtotime( $compareValue );
		}

		return parent::compareValues( $operator, 'number', $value, $compareValue );
	}
}
