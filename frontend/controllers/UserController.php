<?php
namespace cmsgears\core\frontend\controllers;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\frontend\config\WebGlobalCore;

use cmsgears\core\common\services\resources\OptionService;
use cmsgears\core\frontend\services\entities\UserService;

class UserController extends base\Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'index' => [ 'permission' => CoreGlobal::PERM_USER ],
	                'home' => [ 'permission' => CoreGlobal::PERM_USER ],
	                'profile' => [ 'permission' => CoreGlobal::PERM_USER ],
	                'settings' => [ 'permission' => CoreGlobal::PERM_USER ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => [ 'get' ],
                    'home' => [ 'get' ],
                    'profile' => [ 'get' ],
                    'settings' => [ 'get' ]
                ]
            ]
        ];
    }

	// UserController

	// Redirect user to appropriate home page
    public function actionIndex() {

        $this->checkHome();
    }

	// Default home page for user
    public function actionHome() {

        return $this->render( WebGlobalCore::PAGE_INDEX );
    }

    public function actionProfile() {

		// Find Model
		$user	= Yii::$app->user->getIdentity();

		// Update/Render if exist
		if( isset( $user ) ) {

			$genderMap 	= OptionService::getIdNameMapByCategoryName( CoreGlobal::CATEGORY_GENDER, [ [ 'value' => 'Choose Gender', 'name' => '0' ] ] );

	    	return $this->render( WebGlobalCore::PAGE_PROFILE, [
	    		'user' => $user,
	    		'genderMap' => $genderMap
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
    }

	public function actionSettings() {

		// Find Model
		$user		= Yii::$app->user->getIdentity();

		// Update/Render if exist
		if( isset( $user ) ) {

			$privacy		= UserService::findAttributeMapByType( $user, WebGlobalCore::SETTINGS_PRIVACY );
			$notification	= UserService::findAttributeMapByType( $user, WebGlobalCore::SETTINGS_NOTIFICATION );
			$reminder		= UserService::findAttributeMapByType( $user, WebGlobalCore::SETTINGS_REMINDER );

	    	return $this->render( WebGlobalCore::PAGE_SETTINGS, [
	    		'user' => $user,
	    		'privacy' => $privacy,
	    		'notification' => $notification,
	    		'reminder' => $reminder
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>