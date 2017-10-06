<?php
namespace cmsgears\core\common\assets;

// Yii Imports
use yii\web\View;

class CaptchaAsset extends \yii\web\AssetBundle {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Path Configuration
    public $sourcePath = '@yii/assets';

	// Load Javascript
    public $js = [
        'yii.captcha.js',
    ];

	// Position to load Javascript
	public $jsOptions = [
		'position' => View::POS_END
	];

	// Dependency
    public $depends = [
        'cmsgears\core\common\assets\YiiAsset'
    ];

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

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CaptchaAsset --------------------------

}
