<?php
namespace cmsgears\core\admin\controllers\base\category;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class OptionController extends \cmsgears\core\admin\controllers\base\Controller {

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

		$this->crudPermission	= CoreGlobal::PERM_CORE;
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
		$model->categoryId	= $cid;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() )	&& $model->validate() ) {

			$this->modelService->create( $model );

			return $this->redirect( $this->returnUrl );
		}

		return $this->render( 'create', [
			'model' => $model
		]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model	= $this->modelService->getById( $id );

		// Update if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->modelService->update( $model );

				return $this->redirect( $this->returnUrl );
			}

			// Render view
			return $this->render( 'update', [
				'model' => $model
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$model	= $this->modelService->getById( $id );

		// Delete if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				try {

					$this->modelService->delete( $model );

					return $this->redirect( $this->returnUrl );
				}
				catch( Exception $e ) {

					throw new HttpException( 409,  Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  );
				}
			}

			// Render view
			return $this->render( 'delete', [
				'model' => $model
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
