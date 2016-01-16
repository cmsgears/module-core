<?php
namespace cmsgears\core\common\assets;

// Yii Imports
use \Yii;
use yii\web\View;

class JqueryUi extends \yii\web\AssetBundle {

	// Variables ---------------------------------------------------

	// Public ----

	// Path Configuration
    public $sourcePath = '@bower/jquery-ui/ui/minified';

	// Load Javascript
    public $js = [
        'jquery-ui.min.js'
    ];

	// Position to load Javascript
    public $jsOptions = [
        'position' => View::POS_END
    ];
}