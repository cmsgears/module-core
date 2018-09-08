<?php
/**
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 * @license https://www.cmsgears.org/license/
 * @package module
 * @subpackage core
 */
namespace cmsgears\core\common\base;

// CMG Imports
use cmsgears\core\frontend\config\CoreGlobalWeb;

/**
 * The configuration component to configure the module.
 *
 * @since 1.0.0
 */
class Config extends Component {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Default layout used for all the module controllers
	public $defaultLayout;

	// A controller can be assigned custom layout if required
	public $customLayout = [];

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->defaultLayout = $this->defaultLayout ?? CoreGlobalWeb::LAYOUT_PRIVATE;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Config --------------------------------

	public function getDefaultLayout() {

		return $this->defaultLayout;
	}

	public function getCustomLayout() {

		return $this->customLayout;
	}

}
