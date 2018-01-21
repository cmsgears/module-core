<?php
namespace cmsgears\core\common\base;

// Yii Imports
use yii\helpers\Json;

// CMG Imports
use cmsgears\core\common\assets\CaptchaAsset;

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
