<?php
namespace cmsgears\core\admin\controllers\base\category;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class OptionController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $categoryService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		$this->setViewPath( '@cmsgears/module-core/admin/views/optiongroup/option/' );

		$this->crudPermission 	= CoreGlobal::PERM_CORE;
		$this->modelService		= Yii::$app->factory->get( 'optionService' );

		$this->categoryService	= Yii::$app->factory->get( 'categoryService' );

		// Note: Child must specify sidebar and returnUrl.
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// OptionController ----------------------

	public function actionAll( $cid ) {

		$dataProvider	= $this->modelService->getPage( [ 'conditions' => [ 'categoryId' => $cid ]] );
		$category		= $this->categoryService->getById( $cid );

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'category' => $category
		]);
	}

	public function actionCreate( $cid ) {

		$modelClass			= $this->modelService->getModelClass();
		$model				= new $modelClass;
		$model->categoryId 	= $cid;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() )  && $model->validate() ) {

			$this->modelService->create( $model );

			return $this->redirect( $this->returnUrl );
		}

    	return $this->render( 'create', [
    		'model' => $model
    	]);
	}
}
