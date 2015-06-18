<?php
namespace cmsgears\core\frontend\controllers;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\frontend\config\WebGlobalCore;

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
	                'home' => [ 'permission' => CoreGlobal::PERM_USER ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'home' => ['get']
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

			if( $model->load( Yii::$app->request->post(), "User" )  && $model->validate() ) {

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
}

?>