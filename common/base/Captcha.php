<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\base;

// Yii Imports
use yii\helpers\Json;

// CMG Imports
use cmsgears\assets\yii\CaptchaAsset;

class Captcha extends \yii\captcha\Captcha {

    public function registerClientScript() {

        $options = $this->getClientOptions();
        $options = empty( $options ) ? '' : Json::htmlEncode( $options );

        $id		= $this->imageOptions[ 'id' ];
        $view	= $this->getView();

        CaptchaAsset::register( $view );

        $view->registerJs( "jQuery('#$id').yiiCaptcha($options);" );
    }

}
