<?php
namespace cmsgears\core\frontend\controllers\base;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\frontend\config\WebGlobalCore;

use cmsgears\core\frontend\config\WebProperties;

class Controller extends \cmsgears\core\common\controllers\Controller {

	private $_webProperties;

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->layout	= WebGlobalCore::LAYOUT_PRIVATE;
	}

	// Instance Methods --------------------------------------------

	public function getWebProperties() {

		if( !isset( $this->_webProperties ) ) {

			$this->_webProperties	= WebProperties::getInstance();
		}

		return $this->_webProperties;
	}
}
