<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\admin\config\AdminGlobalCore;
use cmsgears\core\frontend\config\WebGlobalCore;
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\services\OptionService;
use cmsgears\core\common\services\UserService;

class UserController extends BaseUserController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->sidebar 		= [ 'parent' => 'sidebar-identity', 'child' => 'user' ];
		$this->createUrl	= '/cmgcore/user/create';		
		
		$this->layout	= AdminGlobalCore::LAYOUT_PRIVATE;
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
	                'delete' => [ 'permission' => CoreGlobal::PERM_IDENTITY ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index'  => ['get'],
	                'all' => ['get'],
	                'create' => ['get', 'post'],
	                'update' => ['get', 'post'],
	                'delete' => ['get', 'post']
                ]
            ]
        ];
    }

	// UserController --------------------

	public function actionIndex() {

		// TODO: Users Dashboard
	}
	
	 public function actionProfile() {

		// Find Model
		$model		= Yii::$app->user->getIdentity();

		// Update/Render if exist
		if( isset( $model ) ) {

			$model->setScenario( "update" );

			UserService::checkNewsletterMember( $model );

			if( $model->load( Yii::$app->request->post(), "User" ) && $model->validate() ) {

				// Update User and Site Member
				if( UserService::update( $model ) ) {

					$this->refresh();
				}
			}

			$genders 	= OptionService::getIdNameMapByCategoryName( CoreGlobal::CATEGORY_GENDER );

	    	return $this->render( WebGlobalCore::PAGE_PROFILE, [
	    		'model' => $model,
	    		'genders' => $genders
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
    }

	public function actionAll() {

		Url::remember( [ 'user/all' ], 'users' );

		return parent::actionAll( null, CoreGlobal::PERM_USER );
	}

	public function actionCreate() {

		return parent::actionCreate( Url::previous( 'users' ) );
	}

	public function actionUpdate( $id ) {

		return parent::actionUpdate( Url::previous( 'users' ), $id );
	}

	public function actionDelete( $id ) {

		return parent::actionDelete( Url::previous( 'users' ), $id );
	}
}

?>