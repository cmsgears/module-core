<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\admin\config\AdminGlobalCore;

use cmsgears\core\admin\services\OptionService;
use cmsgears\core\admin\services\UserService;

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

		$behaviours[ 'rbac' ][ 'actions' ][ 'profile'] 	= [ 'permission' => CoreGlobal::PERM_USER ];

		$behaviours[ 'verbs' ][ 'actions' ][ 'profile' ] 	= [ 'get', 'post' ];
		
		return $behaviours;
    }

	// UserController --------------------

	public function actionAll() {

		Url::remember( [ 'user/all' ], 'users' );

		return parent::actionAll();
	}

	public function actionProfile() {

		$this->sidebar 	= [ ];
		$model			= Yii::$app->user->getIdentity();
		$email			= $model->email;
		$username		= $model->username;

		$model->setScenario( 'profile' );

		UserService::checkNewsletterMember( $model );

		if( $model->load( Yii::$app->request->post(), 'User' ) && $model->validate() ) {

			if( UserService::update( $model ) ) {

				return $this->refresh();
			}
		}

		$genders 	= OptionService::getIdNameMapByCategoryName( CoreGlobal::CATEGORY_GENDER, [ [ 'name' => null, 'value' => 'Select Gender' ] ] );

    	return $this->render( 'profile', [
    		'model' => $model,
    		'genders' => $genders
    	]);
    }
}

?>