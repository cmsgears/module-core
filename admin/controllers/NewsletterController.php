<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Newsletter;

use cmsgears\core\admin\services\NewsletterService;
use cmsgears\core\admin\services\UserService;
use cmsgears\core\admin\services\RoleService;

use cmsgears\core\common\utilities\CodeGenUtil;

class NewsletterController extends BaseController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'index'  => [ 'permission' => CoreGlobal::PERM_NEWSLETTER ],
	                'all'   => [ 'permission' => CoreGlobal::PERM_NEWSLETTER ],
	                'create' => [ 'permission' => CoreGlobal::PERM_NEWSLETTER ],
	                'update' => [ 'permission' => CoreGlobal::PERM_NEWSLETTER ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_NEWSLETTER ],
	                'members' => [ 'permission' => CoreGlobal::PERM_NEWSLETTER ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index'  => ['get'],
	                'all'   => ['get'],
	                'create' => ['get', 'post'],
	                'update' => ['get', 'post'],
	                'delete' => ['get', 'post'],
	                'members' => ['get']
                ]
            ]
        ];
    }

	// NewsletterController --------------

	public function actionIndex() {

		$this->redirect( 'all' );
	}

	public function actionAll() {

		$pagination = NewsletterService::getPagination();

	    return $this->render('all', [
	         'page' => $pagination['page'],
	         'pages' => $pagination['pages'],
	         'total' => $pagination['total']
	    ]);
	}

	public function actionCreate() {

		$model	= new Newsletter();

		$model->setScenario( "create" );

		if( $model->load( Yii::$app->request->post( "Newsletter" ), "" )  && $model->validate() ) {

			if( NewsletterService::create( $model ) ) {

				return $this->redirect( "all" );
			}
		}

    	return $this->render('create', [
    		'model' => $model
    	]);
	}

	public function actionUpdate( $id ) {
		
		// Find Model		
		$model	= NewsletterService::findById( $id );
		
		// Update/Render if exist
		if( isset( $model ) ) {

			$model->setScenario( "update" );
	
			if( $model->load( Yii::$app->request->post( "Newsletter" ), "" )  && $model->validate() ) {
	
				if( NewsletterService::update( $model ) ) {

					$this->refresh();
				}
			}

	    	return $this->render('update', [
	    		'model' => $model
	    	]);			
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model	= NewsletterService::findById( $id );
		
		// Delete/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post( "Newsletter" ), "" ) ) {
	
				if( NewsletterService::delete( $model ) ) {
		
					return $this->redirect( "all" );
				}
			}

	    	return $this->render('delete', [
	    		'model' => $model
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );	
	}

	public function actionMembers() {

		$pagination = UserService::getPaginationByNewsletter();
		$roles 		= RoleService::getIdNameList();
		$roles 		= CodeGenUtil::generateIdNameArray( $roles );

	    return $this->render('members', [
	         'page' => $pagination['page'],
	         'pages' => $pagination['pages'],
	         'total' => $pagination['total'],
	         'roles' => $roles
	    ]);
	}
}

?>