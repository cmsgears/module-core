<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\CmgFile;
use cmsgears\core\common\models\entities\Site;

use cmsgears\core\admin\services\SiteService;

class SitesController extends \cmsgears\core\admin\controllers\base\Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->sidebar 	= [ 'parent' => 'sidebar-core', 'child' => 'site' ];
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'index' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'all' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'create' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'update' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_CORE ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index' => [ 'get' ],
	                'all' => [ 'get' ],
	                'create' => [ 'get', 'post' ],
	                'update' => [ 'get', 'post' ],
	                'delete' => [ 'get', 'post' ]
                ]
            ]
        ];
    }

	// RoleController --------------------

	public function actionIndex() {

		$this->redirect( 'all' );
	}

	public function actionAll() {

		$dataProvider = SiteService::getPagination();

	    return $this->render( 'all', [
			'dataProvider' => $dataProvider
	    ]);
	}

	public function actionCreate() {

		$model		= new Site();
		$avatar 	= CmgFile::loadFile( $model->avatar, 'Avatar' ); 
		$banner 	= CmgFile::loadFile( $model->banner, 'Banner' );

		$model->setScenario( 'create' );

		if( $model->load( Yii::$app->request->post(), 'Site' )  && $model->validate() ) {

			if( SiteService::create( $model, $avatar, $banner ) ) {

				return $this->redirect( 'all' );
			}
		}

    	return $this->render( 'create', [
    		'model' => $model,
    		'avatar' => $avatar,
    		'banner' => $banner
    	]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model		= SiteService::findById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {
 
			$avatar 	= CmgFile::loadFile( $model->avatar, 'Avatar' ); 
			$banner 	= CmgFile::loadFile( $model->banner, 'Banner' );

			$model->setScenario( 'update' );

			if( $model->load( Yii::$app->request->post(), 'Site' )  && $model->validate() ) {

				if( SiteService::update( $model, $avatar, $banner ) ) {

					return $this->redirect( 'all' );
				} 
			}

	    	return $this->render( 'update', [
	    		'model' => $model, 
	    		'avatar' => $avatar,
	    		'banner' => $banner
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model		= SiteService::findById( $id );

		// Delete/Render if exist

		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), 'Site' ) ) {

				try {

					SiteService::delete( $model, $avatar, $banner );

					return $this->redirect( 'all' );
				}
				catch( Exception $e ) {

					throw new HttpException( 409,  Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  ); 
				}
			}

			$avatar	= $model->avatar;
			$banner	= $model->banner;

	    	return $this->render( 'delete', [
	    		'model' => $model, 
	    		'avatar' => $avatar,
	    		'banner' => $banner
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>