<?php
namespace cmsgears\core\frontend\controllers;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\frontend\config\WebGlobalCore;

class UserController extends \cmsgears\core\frontend\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $userService;

	protected $optionService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		$this->crudPermission	= CoreGlobal::PERM_USER;

		$this->userService 		= Yii::$app->factory->get( 'userService' );

		$this->optionService 	= Yii::$app->factory->get( 'optionService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->core->getRbacFilterClass(),
                'actions' => [
	                'index' => [ 'permission' => $this->crudPermission ],
	                'home' => [ 'permission' => $this->crudPermission ],
	                'profile' => [ 'permission' => $this->crudPermission ],
	                'settings' => [ 'permission' => $this->crudPermission ]
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

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// UserController ------------------------

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

			$genderMap 	= $this->optionService->getIdNameMapByCategorySlug( CoreGlobal::CATEGORY_GENDER, [ [ 'id' => '0', 'name' => 'Choose Gender' ] ] );

	    	return $this->render( WebGlobalCore::PAGE_PROFILE, [
	    		'user' => $user,
	    		'genderMap' => $genderMap
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
    }

	public function actionSettings() {

		// Find Model
		$user		= Yii::$app->user->getIdentity();

		// Update/Render if exist
		if( isset( $user ) ) {

			$privacy		= $this->userService->getAttributeMapByType( $user, CoreGlobal::SETTINGS_PRIVACY );
			$notification	= $this->userService->getAttributeMapByType( $user, CoreGlobal::SETTINGS_NOTIFICATION );
			$reminder		= $this->userService->getAttributeMapByType( $user, CoreGlobal::SETTINGS_REMINDER );

			// NOTE: Rest of the attributes can be loaded in view.

			// TODO: Check for options to cache all the user attributes.

	    	return $this->render( WebGlobalCore::PAGE_SETTINGS, [
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
