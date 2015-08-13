<?php
namespace cmsgears\core\frontend\controllers;

// Yii Imports
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\frontend\config\WebGlobalCore;

use cmsgears\core\common\config\CoreProperties;
use cmsgears\core\common\config\MailProperties;
use cmsgears\core\frontend\config\WebProperties;

class BaseController extends Controller {

	private $_coreProperties;
	private $_mailProperties;
	private $_webProperties;

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->layout	= WebGlobalCore::LAYOUT_PRIVATE;
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

	public function getWebProperties() {

		if( !isset( $this->_webProperties ) ) {
			
			$this->_webProperties	= WebProperties::getInstance();
		}

		return $this->_webProperties;
	}
}

?>