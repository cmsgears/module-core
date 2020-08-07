<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\validators\yii;

class FilterValidator extends \yii\validators\FilterValidator {

	public function clientValidateAttribute( $model, $attribute, $view ) {

		if( $this->filter !== 'trim' ) {

			return null;
        }

        ValidationAsset::register( $view );

        $options = $this->getClientOptions($model, $attribute);

        return 'value = yii.validation.trim($form, attribute, ' . json_encode($options, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ');';
    }

}
