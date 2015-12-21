<?php
namespace cmsgears\core\common\assets;

// Yii Imports
use \Yii;
use yii\web\AssetBundle;
use yii\web\View;

class HandlebarAssetBundle extends AssetBundle {

	// Variables ---------------------------------------------------

	// Public ----

	// Path Configuration
    public $sourcePath = '@bower/handlebars';

	// Load Javascript
    public $js = [
        'handlebars.js'
    ];

	// Position to load Javascript
    public $jsOptions = [
        'position' => View::POS_END
    ];
}