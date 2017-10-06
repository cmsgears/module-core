<?php
namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\Form;

abstract class FormController extends CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $type;
	protected $submits;
	protected $templateType;

	// Private ----------------

	private $templateService;

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->type				= CoreGlobal::TYPE_SYSTEM;
		$this->submits			= true;
		$this->templateType		= CoreGlobal::TYPE_FORM;
		
		$this->setViewPath( '@cmsgears/module-core/admin/views/form' );

		// Permissions
		$this->crudPermission	= CoreGlobal::PERM_CORE;
		
		// Services
		$this->modelService		= Yii::$app->factory->get( 'formService' );
		$this->templateService	= Yii::$app->factory->get( 'templateService' );
		
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// FormController ------------------------

	public function actionAll() {

		$dataProvider = $this->modelService->getPageByType( $this->type );

		return $this->render( 'all', [
			 'dataProvider' => $dataProvider,
			 'submits' => $this->submits
		]);
	}

	public function actionCreate() {

		$modelClass		= $this->modelService->getModelClass();
		$model			= new $modelClass;
		$model->type	= $this->type;
		$model->siteId	= Yii::$app->core->siteId;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->modelService->create( $model );

			return $this->redirect( "update?id=$model->id" );
		}

		$templatesMap	= $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

		// Render
		return $this->render( 'create', [
			'model' => $model,
			'templatesMap' => $templatesMap,
			'visibilityMap' => $modelClass::$visibilityMap
		]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$modelClass	= $this->modelService->getModelClass();
		$model		= $this->modelService->getById( $id );

		// Update if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->modelService->update( $model );

				return $this->redirect( $this->returnUrl );
			}

			$templatesMap	= $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

			// Render
			return $this->render( 'update', [
				'model' => $model,
				'templatesMap' => $templatesMap,
				'visibilityMap' => $modelClass::$visibilityMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$modelClass	= $this->modelService->getModelClass();
		$model		= $this->modelService->getById( $id );

		// Delete if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) ) {

				$this->modelService->delete( $model );

				return $this->redirect( $this->returnUrl );
			}

			$templatesMap	= $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

			// Render
			return $this->render( 'delete', [
				'model' => $model,
				'templatesMap' => $templatesMap,
				'visibilityMap' => $modelClass::$visibilityMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
