<?php
namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use \Yii;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

abstract class CommentController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $commentUrl;

	// Protected --------------

	protected $parentType;
	protected $commentType;

	protected $parentService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		$this->setViewPath( '@cmsgears/module-core/admin/views/comment' );

		$this->crudPermission 	= CoreGlobal::PERM_CORE;
		$this->modelService		= Yii::$app->factory->get( 'modelCommentService' );

		// Notes: Configure sidebar, commentUrl, parentType, commentType, parentService and returnUrl exclusively in child classes
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

    // CommentController ---------------------

    public function actionAll( $pid = null ) {

        Url::remember( [ "$this->commentUrl/all?pid=$pid" ], $this->commentUrl );

		$model			= null;
        $dataProvider   = null;

        if( isset( $pid ) ) {

			$model			= $this->parentService->findById( $pid );
            $dataProvider 	= $this->modelService->getPageByParent( $model->id, $this->parentType, [ 'conditions' => [ 'type' => $this->commentType ] ] );
        }
		else {

			$dataProvider 	= $this->modelService->getPageByParentType( $this->parentType, [ 'conditions' => [ 'type' => $this->commentType ] ] );
		}

        return $this->render( 'all', [
             'dataProvider' => $dataProvider,
             'model' => $model
        ]);
    }

    public function actionCreate( $pid ) {

		$modelClass			= $this->modelService->getModelClass();
		$model				= new $modelClass;
        $model->parentId    = $pid;
        $model->parentType  = $this->parentType;
        $model->type        = $this->commentType;
		$parentModel    	= $this->parentService->findById( $pid );

        if( isset( $parentModel ) ) {

            $model->parentId    = $parentModel->id;
        }

        if( $model->load( Yii::$app->request->post(), $model->getClassName() )  && $model->validate() ) {

            $this->modelService->create( $model );

            return $this->redirect( $this->returnUrl );
        }

        return $this->render( 'create', [
            'model' => $model
        ]);
    }
}
