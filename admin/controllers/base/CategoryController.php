<?php
namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use Yii;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

abstract class CategoryController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $type;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Views
		$this->setViewPath( '@cmsgears/module-core/admin/views/category' );

		// Permission
		$this->crudPermission	= CoreGlobal::PERM_CORE;

		// Services
		$this->modelService		= Yii::$app->factory->get( 'categoryService' );

		// Type
		$this->type				= CoreGlobal::TYPE_SITE;

		// Notes: Configure sidebar and returnUrl exclusively in child classes. We can also change type in child classes.
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
		$model->type	= $this->type;
		$model->siteId	= Yii::$app->core->siteId;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() )	&& $model->validate() ) {

			$this->modelService->create( $model );

			return $this->redirect( "update?id=$model->id" );
		}

		$categoryMap	= $this->modelService->getIdNameMapByType( $this->type, [ 'prepend' => [ [ 'name' => 'Choose Category', 'id' => 0 ] ] ] );

		return $this->render( 'create', [
			'model' => $model,
			'categoryMap' => $categoryMap
		]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model	= $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() )	&& $model->validate() ) {

				$this->modelService->update( $model );

				return $this->refresh();
			}

			$categoryMap	= $this->modelService->getIdNameMapByType( $this->type, [
									'prepend' => [ [ 'name' => 'Choose Category', 'id' => 0 ] ],
									'filters' => [ [ 'not in', 'id', [ $id ] ] ]
								]);

			return $this->render( 'update', [
				'model' => $model,
				'categoryMap' => $categoryMap
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

			return $this->render( 'delete', [
				'model' => $model,
				'categoryMap' => $categoryMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
