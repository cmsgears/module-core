<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\admin\config\AdminGlobalCore;

use cmsgears\core\common\services\OptionService;
use cmsgears\core\common\services\UserService;

class UserController extends BaseUserController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->layout		= AdminGlobalCore::LAYOUT_PRIVATE;

		$this->sidebar 		= [ 'parent' => 'sidebar-identity', 'child' => 'user' ];
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'index'  => [ 'permission' => CoreGlobal::PERM_IDENTITY ],
	                'all' => [ 'permission' => CoreGlobal::PERM_IDENTITY ],
	                'create' => [ 'permission' => CoreGlobal::PERM_IDENTITY ],
	                'update' => [ 'permission' => CoreGlobal::PERM_IDENTITY ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_IDENTITY ],
	                'profile' => [ 'permission' => CoreGlobal::PERM_USER ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index'  => [ 'get' ],
	                'all' => [ 'get' ],
	                'create' => [ 'get', 'post' ],
	                'update' => [ 'get', 'post' ],
	                'delete' => [ 'get', 'post' ],
	                'profile' => [ 'get', 'post' ]
                ]
            ]
        ];
    }

	// UserController --------------------

	public function actionIndex() {

		// TODO: Users Dashboard
	}

	public function actionAll() {

		Url::remember( [ 'user/all' ], 'users' );

		return parent::actionAll( null, CoreGlobal::PERM_USER );
	}

	public function actionCreate() {
		
		$this->returnUrl	= Url::previous( 'users' );

		return parent::actionCreate( CoreGlobal::TYPE_SYSTEM );
	}

	public function actionUpdate( $id ) {
		
		$this->returnUrl	= Url::previous( 'users' );

		return parent::actionUpdate( $id, CoreGlobal::TYPE_SYSTEM );
	}

	public function actionDelete( $id ) {
		
		$this->returnUrl	= Url::previous( 'users' );

		return parent::actionDelete( $id, CoreGlobal::TYPE_SYSTEM );
	}

	public function actionProfile() {

		$model		= Yii::$app->user->getIdentity();

		$model->setScenario( 'update' );

		UserService::checkNewsletterMember( $model );

		if( $model->load( Yii::$app->request->post(), 'User' ) && $model->validate() ) {

			if( UserService::update( $model ) ) {

				$this->refresh();
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