<?php
namespace cmsgears\core\common\assets;

// Yii Imports
use yii\web\View;

class YiiAsset extends \yii\web\AssetBundle {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Path Configuration
    public $sourcePath = '@yii/assets';


	// Load Javascript
    public $js = [
        'yii.js'
    ];

	// Position to load Javascript
	public $jsOptions = [
		'position' => View::POS_END
	];

	// Dependency
    public $depends = [
        'cmsgears\core\common\assets\Jquery'
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

	// YiiAsset ------------------------------

}
