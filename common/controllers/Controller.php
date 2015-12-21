<?php
namespace cmsgears\core\common\controllers;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\config\CoreProperties;
use cmsgears\core\common\config\MailProperties;

abstract class Controller extends \yii\web\Controller {

	private $_coreProperties;
	private $_mailProperties;
	
	// It provide information to display active tab on sidebar.
	public $sidebar;

	// We need return url in cases where view need to provide links to move back to previous page.
	public $returnUrl;

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

	/**
	 * The method check whether user is logged in and send to respective home page.
	 */
	protected function checkHome() {

		// Send user to home if already logged in
	    if ( !Yii::$app->user->isGuest ) {

			$user	= Yii::$app->user->getIdentity();
			$role	= $user->role;

			// Redirect user to home
			if( isset( $role ) && isset( $role->homeUrl ) ) {

				$this->redirect( [ "/$role->homeUrl" ] );
			}
			// Redirect user to home set by app config
			else {

				$this->redirect( [ Yii::$app->cmgCore->getLoginRedirectPage() ] );
			}
	    }
	}
}

?>