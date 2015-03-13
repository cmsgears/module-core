<?php
namespace cmsgears\modules\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\web\Controller;

// CMG Imports
use cmsgears\modules\core\admin\config\AdminGlobalCore;

use cmsgears\modules\core\common\config\CoreProperties;
use cmsgears\modules\core\common\config\MailProperties;

use cmsgears\modules\core\admin\config\AdminProperties;

class BaseController extends Controller {

	private $coreProperties;
	private $mailProperties;

	private $adminProperties;

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
		
		if( !isset( $this->coreProperties ) ) {
			
			$this->coreProperties	= CoreProperties::getInstance();
		}

		return $this->coreProperties;
	}

	public function getMailProperties() {
		
		if( !isset( $this->mailProperties ) ) {
			
			$this->mailProperties	= MailProperties::getInstance();
		}

		return $this->mailProperties;
	}

	public function getAdminProperties() {

		if( !isset( $this->adminProperties ) ) {

			$this->adminProperties	= AdminProperties::getInstance();
		}

		return $this->adminProperties;
	}
}

?>