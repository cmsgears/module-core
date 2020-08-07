<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\validators\yii;

class RangeValidator extends \yii\validators\RangeValidator {

	public function clientValidateAttribute( $model, $attribute, $view ) {

		if( $this->range instanceof \Closure ) {

			$this->range = call_user_func( $this->range, $model, $attribute );
		}

		ValidationAsset::register( $view );

		$options = $this->getClientOptions( $model, $attribute );

		return 'yii.validation.range(value, messages, ' . json_encode($options, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ');';
    }

}
