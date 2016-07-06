<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class OptiongroupController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $type;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		$this->crudPermission 	= CoreGlobal::PERM_CORE;
		$this->modelService		= Yii::$app->factory->get( 'categoryService' );
		$this->sidebar			= [ 'parent' => 'sidebar-core', 'child' => 'option-group' ];
		$this->type				= CoreGlobal::TYPE_OPTION_GROUP;

		$this->returnUrl		= Url::previous( 'ogroups' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/optiongroup/all' ], true );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// OptionGroupController -----------------

	public function actionAll() {

		Url::remember( [ 'optiongroup/all' ], 'ogroups' );

		$dataProvider = $this->modelService->getPageByType( $this->type );

	    return $this->render( 'all', [
			'dataProvider' => $dataProvider
	    ]);
	}

	public function actionCreate() {

		$modelClass		= $this->modelService->getModelClass();
		$model			= new $modelClass;
		$model->type 	= $this->type;
		$model->siteId	= Yii::$app->core->siteId;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() )  && $model->validate() ) {

			$this->modelService->create( $model );

			return $this->redirect( $this->returnUrl );
		}

    	return $this->render( 'create', [
    		'model' => $model
    	]);
	}
}
