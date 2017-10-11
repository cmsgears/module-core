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
 * MCustomScrollbar can be used to load custom scroll bar assets.
 *
 * @author Bhagwat Singh Chouhan <bhagwat.chouhan@gmail.com>
 * @since 1.0.0
 */
class MCustomScrollbar extends \yii\web\AssetBundle {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	/**
	 * @inheritdoc
	 */
	public $sourcePath = '@bower/malihu-custom-scrollbar-plugin';

	/**
	 * @inheritdoc
	 */
	public $js = [
		'jquery.mCustomScrollbar.concat.min.js'
	];

	/**
	 * @inheritdoc
	 */
	public $jsOptions = [
		'position' => View::POS_END
	];

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	/**
	 * @inheritdoc
	 */
    public function init() {

		if( YII_DEBUG ) {

			$this->js = [ 'jquery.mCustomScrollbar.js' ];
		}
    }

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// MCustomScrollbar ----------------------

}
