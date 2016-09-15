<?php
namespace cmsgears\core\common\assets;

// Yii Imports
use \Yii;
use yii\web\View;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class JqueryMouseWheel extends \yii\web\AssetBundle {

    // Variables ---------------------------------------------------

    // Globals -------------------------------

    // Constants --------------

    // Public -----------------

    // Path Configuration
    public $sourcePath = '@bower/jquery-mousewheel';

    // Load Javascript
    public $js = [
        'jquery.mousewheel.min.js'
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

    // JqueryMouseWheel ----------------------

    // Static Methods ----------------------------------------------

    // Yii parent classes --------------------

    // CMG parent classes --------------------

    // JqueryMouseWheel ----------------------

}
