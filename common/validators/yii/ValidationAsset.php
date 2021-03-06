<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\validators\yii;

// Yii Imports
use yii\web\AssetBundle;
use yii\web\View;

class ValidationAsset extends AssetBundle {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Path Configuration
    public $sourcePath = '@yii/assets';

	// Load Javascript
    public $js = [
        'yii.validation.js',
    ];

	// Position to load Javascript
	public $jsOptions = [
		'position' => View::POS_END
	];

	// Dependency
    public $depends = [
        'cmsgears\assets\yii\YiiAsset'
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

	// ValidationAsset -----------------------

}
