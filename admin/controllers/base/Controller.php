<?php
namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\admin\config\AdminGlobalCore;

use cmsgears\core\admin\config\AdminProperties;

abstract class Controller extends \cmsgears\core\common\controllers\Controller {

	private $_adminProperties;

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->layout	= AdminGlobalCore::LAYOUT_PRIVATE;
	}

	// Instance Methods --------------------------------------------

	public function getAdminProperties() {

		if( !isset( $this->_adminProperties ) ) {

			$this->_adminProperties	= AdminProperties::getInstance();
		}

		return $this->_adminProperties;
	}
}

?>