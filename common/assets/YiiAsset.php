<?php
/**
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 * @license https://www.cmsgears.org/license/
 * @package module
 * @subpackage core
 */
namespace cmsgears\core\common\assets;

// Yii Imports
use yii\web\View;

/**
 * YiiAsset can be used to load yii.js from yii assets directory.
 *
 * @author Bhagwat Singh Chouhan <bhagwat.chouhan@gmail.com>
 * @since 1.0.0
 */
class YiiAsset extends \yii\web\AssetBundle {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	/**
	 * @inheritdoc
	 */
	public $sourcePath = '@yii/assets';

	/**
	 * @inheritdoc
	 */
	public $js = [
		'yii.js'
	];

	/**
	 * @inheritdoc
	 */
	public $jsOptions = [
		'position' => View::POS_END
	];

	/**
	 * @inheritdoc
	 */
	public $depends = [
		'cmsgears\core\common\assets\Jquery'
	];

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
