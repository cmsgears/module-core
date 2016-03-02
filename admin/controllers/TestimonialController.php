<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\ModelComment;

use cmsgears\core\admin\services\ModelCommentService;

class TestimonialController extends \cmsgears\core\admin\controllers\base\Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->returnUrl	= Url::previous( 'testimonials' );

		$this->sidebar 		= [ 'parent' => 'sidebar-core', 'child' => 'testimonials' ];
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

	// Default RBAC and Verbs
    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'all' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'create' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'update' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_CORE ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'all' => [ 'get' ],
	                'create' => [ 'get', 'post' ],
	                'update' => [ 'get', 'post' ],
	                'delete' => [ 'get', 'post' ]
                ]
            ]
        ];
    }

	// TestimonialController -----------------

	public function actionAll() {

		$dataProvider = ModelCommentService::getPaginationByType( ModelComment::TYPE_TESTIMONIAL );

		Url::remember( [ 'testimonial/all' ], 'testimonials' );

	    return $this->render( 'all', [
	         'dataProvider' => $dataProvider
	    ]);
	}

    public function actionCreate() {

        // Find Model
        $model  = new ModelComment();

		$model->parentId	= Yii::$app->cmgCore->siteId;
		$model->parentType	= CoreGlobal::TYPE_SITE;
		$model->type    	= ModelComment::TYPE_TESTIMONIAL;

		if( $model->load( Yii::$app->request->post(), 'ModelComment' )  && $model->validate() ) {

			ModelCommentService::create( $model );

			return $this->redirect( $this->returnUrl );
		}

		return $this->render( 'create', [
			'model' => $model,
			'statusMap' => ModelComment::$statusMap
		]);
    }

    public function actionUpdate( $id ) {

        // Find Model
        $model  = ModelCommentService::findById( $id );

        // Update/Render if exist
        if( isset( $model ) ) {

            if( $model->load( Yii::$app->request->post(), 'ModelComment' )  && $model->validate() ) {
    
                ModelCommentService::update( $model );

				return $this->redirect( $this->returnUrl );
            }

            return $this->render( 'update', [
				'model' => $model,
				'statusMap' => ModelComment::$statusMap
            ]);
        }

        // Model not found
        throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
    }

    public function actionDelete( $id ) {

        // Find Model
        $model  = ModelCommentService::findById( $id );

        // Update/Render if exist
        if( isset( $model ) ) {

            if( $model->load( Yii::$app->request->post(), 'ModelComment' )  && $model->validate() ) {

                ModelCommentService::delete( $model );

				return $this->redirect( $this->returnUrl );
            }

            return $this->render( 'delete', [
				'model' => $model,
				'statusMap' => ModelComment::$statusMap
            ]);
        }

        // Model not found
        throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
    }
}

?>