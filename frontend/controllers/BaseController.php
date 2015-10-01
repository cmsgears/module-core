<?php
namespace cmsgears\core\frontend\controllers;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\frontend\config\WebGlobalCore;

use cmsgears\core\frontend\config\WebProperties;

class BaseController extends \cmsgears\core\common\controllers\BaseController {

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

?>