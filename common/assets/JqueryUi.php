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
 * JqueryUi can be used to load jQuery UI JS.
 *
 * @author Bhagwat Singh Chouhan <bhagwat.chouhan@gmail.com>
 * @since 1.0.0
 */
class JqueryUi extends \yii\web\AssetBundle {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	/**
	 * @inheritdoc
	 */
	public $sourcePath = '@bower/jquery-ui';

	/**
	 * @inheritdoc
	 */
	public $js = [
		'jquery-ui.min.js'
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

	/**
	 * @inheritdoc
	 */
    public function init() {

		if( YII_DEBUG ) {

			$this->js = [ 'jquery-ui.js' ];
		}
    }

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// JqueryUi ------------------------------

}
