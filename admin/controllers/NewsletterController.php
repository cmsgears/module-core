<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Newsletter;

use cmsgears\core\admin\services\NewsletterService;
use cmsgears\core\admin\services\NewsletterMemberService;
use cmsgears\core\admin\services\TemplateService;

class NewsletterController extends base\Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
		
		$this->sidebar 	= [ 'parent' => 'sidebar-newsletter', 'child' => 'newsletter' ];
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'index'  => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'all'   => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'create' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'update' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'members' => [ 'permission' => CoreGlobal::PERM_CORE ]
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

		$this->redirect( [ "all" ] );
	}

	public function actionAll() {

		$dataProvider = NewsletterService::getPagination();

	    return $this->render('all', [
	         'dataProvider' => $dataProvider
	    ]);
	}

	public function actionCreate() {

		$model	= new Newsletter();

		$model->setScenario( "create" );

		if( $model->load( Yii::$app->request->post(), "Newsletter" )  && $model->validate() ) {

			if( NewsletterService::create( $model ) ) {

				$this->redirect( [ "all" ] );
			}
		}

		$templatesMap	= TemplateService::getIdNameMapByType( CoreGlobal::TYPE_NEWSLETTER );
		$templatesMap	= ArrayHelper::merge( [ '0' => 'Choose Template' ], $templatesMap );

    	return $this->render('create', [
    		'model' => $model,
    		'templatesMap' => $templatesMap
    	]);
	}

	public function actionUpdate( $id ) {
		
		// Find Model		
		$model	= NewsletterService::findById( $id );
		
		// Update/Render if exist
		if( isset( $model ) ) {

			$model->setScenario( "update" );
	
			if( $model->load( Yii::$app->request->post(), "Newsletter" )  && $model->validate() ) {
	
				if( NewsletterService::update( $model ) ) {

					$this->redirect( [ "all" ] );
				}
			}

			$templatesMap	= TemplateService::getIdNameMapByType( CoreGlobal::TYPE_NEWSLETTER );
			$templatesMap	= ArrayHelper::merge( [ '0' => 'Choose Template' ], $templatesMap );

	    	return $this->render( 'update', [
	    		'model' => $model,
	    		'templatesMap' => $templatesMap
	    	]);			
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model	= NewsletterService::findById( $id );
		
		// Delete/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), "Newsletter" ) ) {
	
				if( NewsletterService::delete( $model ) ) {
		
					$this->redirect( [ "all" ] );
				}
			}

			$templatesMap	= TemplateService::getIdNameMapByType( CoreGlobal::TYPE_NEWSLETTER );
			$templatesMap	= ArrayHelper::merge( [ '0' => 'Choose Template' ], $templatesMap );

	    	return $this->render( 'delete', [
	    		'model' => $model,
	    		'templatesMap' => $templatesMap
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );	
	}

	public function actionMembers() {

		$this->sidebar 	= [ 'parent' => 'sidebar-newsletter', 'child' => 'member' ];
		$dataProvider 	= NewsletterMemberService::getPagination();

	    return $this->render( 'members', [
	         'dataProvider' => $dataProvider
	    ]);
	}
}

?>