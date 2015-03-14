<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\admin\config\AdminGlobalCore;

use cmsgears\core\common\models\entities\Newsletter;
use cmsgears\core\common\models\entities\Permission;

use cmsgears\core\admin\services\NewsletterService;
use cmsgears\core\admin\services\UserService;
use cmsgears\core\admin\services\RoleService;

use cmsgears\core\common\utilities\CodeGenUtil;
use cmsgears\core\common\utilities\MessageUtil;

class NewsletterController extends BaseController {

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
                'permissions' => [
	                'index'  => Permission::PERM_NEWSLETTER,
	                'all'   => Permission::PERM_NEWSLETTER,
	                'create' => Permission::PERM_NEWSLETTER,
	                'update' => Permission::PERM_NEWSLETTER,
	                'delete' => Permission::PERM_NEWSLETTER,
	                'members' => Permission::PERM_NEWSLETTER
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

	// NewsletterController

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

				return $this->redirect( [ self::URL_ALL ] );
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
		throw new NotFoundHttpException( MessageUtil::getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model	= NewsletterService::findById( $id );
		
		// Delete/Render if exist
		if( isset( $model ) ) {

			if( isset( $_POST ) && count( $_POST ) > 0 ) {
	
				if( NewsletterService::delete( $model ) ) {
		
					return $this->redirect( [ self::URL_ALL ] );
				}
			}

	    	return $this->render('delete', [
	    		'model' => $model
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( MessageUtil::getMessage( CoreGlobal::ERROR_NOT_FOUND ) );	
	}

	public function actionMembers() {

		$pagination = UserService::getPaginationByNewsletter();
		$roles 		= RoleService::getIdNameArrayList();
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