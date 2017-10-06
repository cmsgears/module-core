<?php
namespace cmsgears\core\common\widgets;

// Yii Imports
use yii\helpers\Json;

// CMG Imports
use cmsgears\core\common\assets\CaptchaAsset;

class Captcha extends \yii\captcha\Captcha {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Widget --------

	// yii\captcha\Captcha ----

    public function registerClientScript() {

        $options = $this->getClientOptions();
        $options = empty($options) ? '' : Json::htmlEncode($options);
        $id = $this->imageOptions['id'];
        $view = $this->getView();
        CaptchaAsset::register($view);
        $view->registerJs("jQuery('#$id').yiiCaptcha($options);");
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Captcha -------------------------------

}
