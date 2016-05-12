<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\admin\config\AdminGlobalCore;

use cmsgears\core\admin\services\entities\UserService;
use cmsgears\core\admin\services\resources\OptionService;

class UserController extends \cmsgears\core\admin\controllers\base\UserController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->layout		= AdminGlobalCore::LAYOUT_PRIVATE;

		$this->sidebar 		= [ 'parent' => 'sidebar-identity', 'child' => 'user' ];

		$this->roleType			= CoreGlobal::TYPE_SYSTEM;
		$this->permissionSlug	= CoreGlobal::PERM_USER;
		$this->showCreate 		= true;
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

		$behaviours	= parent::behaviors();

		$behaviours[ 'rbac' ][ 'actions' ][ 'profile'] 		= [ 'permission' => CoreGlobal::PERM_USER ];
		$behaviours[ 'rbac' ][ 'actions' ][ 'settings'] 	= [ 'permission' => CoreGlobal::PERM_USER ];

		$behaviours[ 'verbs' ][ 'actions' ][ 'profile' ] 	= [ 'get', 'post' ];
		$behaviours[ 'verbs' ][ 'actions' ][ 'settings' ] 	= [ 'get', 'post' ];

		return $behaviours;
    }

	// UserController --------------------

	public function actionAll() {

		Url::remember( [ 'user/all' ], 'users' );

		return parent::actionAll();
	}

    public function actionProfile() {

		// Find Model
		$user				= Yii::$app->user->getIdentity();
		$this->sidebar 		= [];

		// Update/Render if exist
		if( isset( $user ) ) {

			$genderMap 	= OptionService::getIdNameMapByCategoryName( CoreGlobal::CATEGORY_GENDER, [ [ 'value' => 'Choose Gender', 'name' => '0' ] ] );

	    	return $this->render( 'profile', [
	    		'user' => $user,
	    		'genderMap' => $genderMap
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
    }

	public function actionSettings() {

		// Find Model
		$user				= Yii::$app->user->getIdentity();
		$this->sidebar 		= [];

		// Update/Render if exist
		if( isset( $user ) ) {

			$privacy		= UserService::findAttributeMapByType( $user, CoreGlobal::SETTINGS_PRIVACY );
			$notification	= UserService::findAttributeMapByType( $user, CoreGlobal::SETTINGS_NOTIFICATION );
			$reminder		= UserService::findAttributeMapByType( $user, CoreGlobal::SETTINGS_REMINDER );

	    	return $this->render( 'settings', [
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