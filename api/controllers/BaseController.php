<?php
namespace cmsgears\core\api\controllers\api;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\frontend\config\SiteProperties;
use cmsgears\core\api\controllers\api\RestTrait;

class BaseController extends \yii\rest\Controller {
	
	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $userService;
	
	// Private ----------------
	
	public $siteProperties;
	
	// Constructor and Initialisation ------------------------------

	public function init() {
		
		$this->userService = Yii::$app->factory->get( 'userService' );
	}
	
	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	use RestTrait;
	
	public static function allowedDomains() {
		return [
			 '*'         // star allows all domains
		];
	}
	
	public function actionTest() {

		return json_encode( getallheaders() );
	}

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// BaseController ------------------------
	
	public function getSiteProperties() {

		if( !isset( $this->siteProperties ) ) {

			$this->siteProperties = SiteProperties::getInstance();
		}

		return $this->siteProperties;
	}
	
	protected function getUser() {

		$token		= json_encode( getallheaders()[ 'accessToken' ] );
		$token		= trim( $token, "'" ); // Remove single quotes if any
		$token		= trim( $token, '"' ); // Remove double quotes if any		
		$model		= $this->userService->getByAccessToken( $token );

		if( isset( $model ) ) {
			
			Yii::$app->user->setIdentity( $model );
		}
		return $model;
	}
}