<?php
namespace cmsgears\core\frontend\controllers;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\frontend\config\WebGlobalCore;

use cmsgears\core\common\models\entities\ModelMeta;

use cmsgears\core\common\services\OptionService;
use cmsgears\core\frontend\services\UserService;

class UserController extends BaseController {

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
	                'home' => [ 'permission' => CoreGlobal::PERM_USER ],
	                'profile' => [ 'permission' => CoreGlobal::PERM_USER ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'home' => [ 'get' ],
                    'profile' => [ 'get', 'post' ]
                ]
            ]
        ];
    }

	// UserController

    public function actionHome() {

        return $this->render( WebGlobalCore::PAGE_INDEX );
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
	
	public function actionSetting() {

		// Find Model
		
		$user				= Yii::$app->user->getIdentity();
		$metaInfo			= UserService::findMetaByType(  $user->id, CoreGlobal::TYPE_USER, 'show info'  );
		$metaNotification	= UserService::findMetaByType(  $user->id, CoreGlobal::TYPE_USER, 'show notification'  );		 

		// Update/Render if exist
		
		if( isset( $model ) ) {			
			 
	    	return $this->render( WebGlobalCore::PAGE_SETTING, [
	    		'metaInfo' => $metaInfo,
	    		'metaNotification' => $metaNotification
	    	]);
		}
		else {
			
			$model	= new ModelMeta();
			
			return $this->render( WebGlobalCore::PAGE_SETTING, [
	    		'metaInfo' => $metaInfo,
	    		'metaNotification' => $metaNotification
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
    }
}

?>