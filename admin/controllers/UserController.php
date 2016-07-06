<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class UserController extends \cmsgears\core\admin\controllers\base\UserController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		$this->roleType			= CoreGlobal::TYPE_SYSTEM;
		$this->permissionSlug	= CoreGlobal::PERM_USER;
		$this->showCreate 		= true;
		$this->sidebar			= [ 'parent' => 'sidebar-identity', 'child' => 'user' ];

		$this->returnUrl		= Url::previous( 'users' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/user/all' ], true );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

    public function behaviors() {

		$behaviours	= parent::behaviors();

		$behaviours[ 'rbac' ][ 'actions' ][ 'profile'] 		= [ 'permission' => CoreGlobal::PERM_USER ];
		$behaviours[ 'rbac' ][ 'actions' ][ 'settings'] 	= [ 'permission' => CoreGlobal::PERM_USER ];

		$behaviours[ 'verbs' ][ 'actions' ][ 'profile' ] 	= [ 'get', 'post' ];
		$behaviours[ 'verbs' ][ 'actions' ][ 'settings' ] 	= [ 'get', 'post' ];

		return $behaviours;
    }

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// UserController ------------------------

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

			$genderMap 	= $this->optionService->getIdNameMapByCategorySlug( CoreGlobal::CATEGORY_GENDER, [ [ 'id' => '0', 'name' => 'Choose Gender' ] ] );

	    	return $this->render( 'profile', [
	    		'user' => $user,
	    		'genderMap' => $genderMap
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
    }

	public function actionSettings() {

		// Find Model
		$user				= Yii::$app->user->getIdentity();
		$this->sidebar 		= [];

		// Update/Render if exist
		if( isset( $user ) ) {

			$privacy		= $this->modelService->getAttributeMapByType( $user, CoreGlobal::SETTINGS_PRIVACY );
			$notification	= $this->modelService->getAttributeMapByType( $user, CoreGlobal::SETTINGS_NOTIFICATION );
			$reminder		= $this->modelService->getAttributeMapByType( $user, CoreGlobal::SETTINGS_REMINDER );

	    	return $this->render( 'settings', [
	    		'user' => $user,
	    		'privacy' => $privacy,
	    		'notification' => $notification,
	    		'reminder' => $reminder
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
