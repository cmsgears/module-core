<?php
namespace cmsgears\core\common\assets;

// Yii Imports
use \Yii;
use yii\web\AssetBundle;
use yii\web\View;

class CMTAssetBundle extends AssetBundle {

	// Variables ---------------------------------------------------

	// Public ----

	// Path Configuration
    public $sourcePath = '@bower/cmt-js/dist';

	// Load Javascript
    public $js = [
        'cmgtools.js'
    ];

	// Position to load Javascript
    public $jsOptions = [
        'position' => View::POS_END
    ];
}