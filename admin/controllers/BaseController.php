<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\web\Controller;

// CMG Imports
use cmsgears\core\admin\config\AdminGlobalCore;

use cmsgears\core\common\config\CoreProperties;
use cmsgears\core\common\config\MailProperties;
use cmsgears\core\admin\config\AdminProperties;

class BaseController extends Controller {

	private $_coreProperties;
	private $_mailProperties;
	private $_adminProperties;

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->layout	= AdminGlobalCore::LAYOUT_PRIVATE;
	}

	public function beforeAction( $action ) {

	    if( defined('YII_DEBUG') && YII_DEBUG ) {

	        Yii::$app->assetManager->forceCopy = true;
	    }

	    return parent::beforeAction( $action );
	}

	// Instance Methods --------------------------------------------

	public function getCoreProperties() {

		if( !isset( $this->_coreProperties ) ) {

			$this->_coreProperties	= CoreProperties::getInstance();
		}

		return $this->_coreProperties;
	}

	public function getMailProperties() {

		if( !isset( $this->_mailProperties ) ) {

			$this->_mailProperties	= MailProperties::getInstance();
		}

		return $this->_mailProperties;
	}

	public function getAdminProperties() {

		if( !isset( $this->_adminProperties ) ) {

			$this->_adminProperties	= AdminProperties::getInstance();
		}

		return $this->_adminProperties;
	}
}

?>