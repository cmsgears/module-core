<?php
namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\admin\services\ModelCommentService;
use cmsgears\core\admin\services\TemplateService;

abstract class TestimonialController extends Controller {

	protected $type;
	protected $templateType;

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );

		$this->returnUrl	= Url::previous( 'testimonials' );
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
	                'update' => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'delete' => [ 'permission' => CoreGlobal::PERM_CORE ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'all' => [ 'get' ],
	                'update' => [ 'get', 'post' ],
	                'delete' => [ 'get', 'post' ]
                ]
            ]
        ];
    }

	// TestimonialController -----------------
	
	public function actionAll() {

		$dataProvider = ModelCommentService::getPaginationByType( $this->type );

		Url::remember( [ 'testimonial/all' ], 'testimonials' );

	    return $this->render( '@cmsgears/module-core/admin/views/testimonial/all', [
	         'dataProvider' => $dataProvider
	    ]);
	}
    
    public function actionUpdate( $id ) {

        // Find Model
        $model  = ModelCommentService::findById( $id );
        Url::remember( [ 'testimonial/all' ], 'testimonials' );

        // Update/Render if exist
        if( isset( $model ) ) {

            $model->type    = $this->type;
    
            if( $model->load( Yii::$app->request->post(), 'ModelComment' )  && $model->validate() ) {
    
                if( ModelCommentService::update( $model ) ) {

                    return $this->redirect( $this->returnUrl );
                }
            }

            $templatesMap   = TemplateService::getIdNameMapByType( $this->templateType, [ 'default' => true ] );

            return $this->render( '@cmsgears/module-core/admin/views/testimonial/update', [
                'model' => $model,
                'templatesMap' => $templatesMap
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

            $model->type    = $this->type;
    
            if( $model->load( Yii::$app->request->post(), 'ModelComment' )  && $model->validate() ) {
    
                if( ModelCommentService::delete( $model ) ) {

                    return $this->redirect( $this->returnUrl );
                }
            }

            $templatesMap   = TemplateService::getIdNameMapByType( $this->templateType, [ 'default' => true ] );

            return $this->render( '@cmsgears/module-core/admin/views/testimonial/delete', [
                'model' => $model,
                'templatesMap' => $templatesMap
            ]);
        }

        // Model not found
        throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
    }
}

?>