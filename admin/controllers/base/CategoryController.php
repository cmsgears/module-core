<?php
namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\Category;

abstract class CategoryController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $type;
	protected $templateType;

	protected $templateService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		$this->setViewPath( '@cmsgears/module-core/admin/views/category' );

		$this->crudPermission 	= CoreGlobal::PERM_CORE;
		$this->modelService		= Yii::$app->factory->get( 'categoryService' );

		$this->type				= CoreGlobal::TYPE_SITE;
		$this->templateType		= CoreGlobal::TYPE_CATEGORY;

		$this->templateService	= Yii::$app->factory->get( 'templateService' );

		// Notes: Configure sidebar and returnUrl exclusively in child classes. We can also change type and templateType in child classes.
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CategoryController --------------------

	public function actionAll() {

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

		$categoryMap	= $this->modelService->getIdNameMapByType( $this->type, [ 'prepend' => [ [ 'name' => 'Choose Category', 'id' => 0 ] ] ] );
		$templatesMap	= $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

    	return $this->render( 'create', [
    		'model' => $model,
    		'categoryMap' => $categoryMap,
    		'templatesMap' => $templatesMap
    	]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model	= $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() )  && $model->validate() ) {

				$this->modelService->update( $model );

				return $this->redirect( $this->returnUrl );
			}

			$categoryMap	= $this->modelService->getIdNameMapByType( $this->type, [
									'prepend' => [ [ 'name' => 'Choose Category', 'id' => 0 ] ],
									'filters' => [ [ 'not in', 'id', [ $id ] ] ]
								]);

			$templatesMap	= $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

	    	return $this->render( 'update', [
	    		'model' => $model,
	    		'categoryMap' => $categoryMap,
	    		'templatesMap' => $templatesMap
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model	= $this->modelService->getById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) ) {

				$this->modelService->delete( $model );

				return $this->redirect( $this->returnUrl );
			}

			$categoryMap	= $this->modelService->getIdNameMapByType( $this->type, [ 'prepend' => [ [ 'name' => 'Choose Category', 'id' => 0 ] ] ] );
			$templatesMap	= $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

	    	return $this->render( 'delete', [
	    		'model' => $model,
	    		'categoryMap' => $categoryMap,
	    		'templatesMap' => $templatesMap
	    	]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
