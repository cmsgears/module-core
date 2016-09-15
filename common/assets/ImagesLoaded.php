<?php
namespace cmsgears\core\common\assets;

// Yii Imports
use \Yii;
use yii\web\View;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class ImagesLoaded extends \yii\web\AssetBundle {

    // Variables ---------------------------------------------------

    // Globals -------------------------------

    // Constants --------------

    // Public -----------------

    // Path Configuration
    public $sourcePath = '@bower/imagesloaded';

    // Load Javascript
    public $js = [
        'imagesloaded.pkgd.min.js'
    ];

    // Position to load Javascript
    public $jsOptions = [
        'position' => View::POS_END
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

    // ImagesLoaded --------------------------

    // Static Methods ----------------------------------------------

    // Yii parent classes --------------------

    // CMG parent classes --------------------

    // ImagesLoaded --------------------------

}
