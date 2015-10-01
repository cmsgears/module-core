<?php
namespace cmsgears\core\common\controllers;

// Yii Imports
use Yii;
use yii\web\Controller;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\config\CoreProperties;
use cmsgears\core\common\config\MailProperties;

class BaseController extends Controller {

	private $_coreProperties;
	private $_mailProperties;

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// For development purpose only - Publish assets for each request
	public function beforeAction( $action ) {

	    if( defined( 'YII_DEBUG' ) && YII_DEBUG ) {

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
}

?>