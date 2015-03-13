<?php
namespace cmsgears\modules\core\frontend\controllers;

// Yii Imports
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\modules\core\common\config\CoreGlobal;
use cmsgears\modules\core\common\config\CoreProperties;
use cmsgears\modules\core\common\config\MailProperties;

use cmsgears\modules\core\frontend\config\WebGlobalCore;
use cmsgears\modules\core\frontend\config\WebProperties;

class BaseController extends Controller {

	private $coreProperties;
	private $mailProperties;

	private $webProperties;

	protected $page;

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->layout	= WebGlobalCore::LAYOUT_PRIVATE;
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

	public function getWebProperties() {

		if( !isset( $this->webProperties ) ) {
			
			$this->webProperties	= WebProperties::getInstance();
		}

		return $this->webProperties;
	}

	public function getPage() {

		return $this->page;
	}
}

?>