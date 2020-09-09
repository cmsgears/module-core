<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\behaviors\ActivityBehavior;

/**
 * FormController provides actions specific to form model.
 *
 * @since 1.0.0
 */
abstract class FormController extends Controller {

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

		// Views
		$this->setViewPath( '@cmsgears/module-core/admin/views/form' );

		// Permission
		$this->crudPermission = CoreGlobal::PERM_CORE;

		// Services
		$this->modelService		= Yii::$app->factory->get( 'formService' );
		$this->templateService	= Yii::$app->factory->get( 'templateService' );

		$this->templateType = $this->modelService->getParentType();
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		return [
			'rbac' => [
				'class' => Yii::$app->core->getRbacFilterClass(),
				'actions' => [
					'index' => [ 'permission' => $this->crudPermission ],
					'all' => [ 'permission' => $this->crudPermission ],
					'create' => [ 'permission' => $this->crudPermission ],
					'update' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'index' => [ 'get', 'post' ],
					'all' => [ 'get' ],
					'create' => [ 'get', 'post' ],
					'update' => [ 'get', 'post' ],
					'delete' => [ 'get', 'post' ]
				]
			],
			'activity' => [
				'class' => ActivityBehavior::class,
				'admin' => true,
				'create' => [ 'create' ],
				'update' => [ 'update' ],
				'delete' => [ 'delete' ]
			]
		];
	}

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// FormController ------------------------

	public function actionAll() {

		$modelClass	= $this->modelService->getModelClass();

		$dataProvider = $this->modelService->getPageByType( $this->type );

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'submits' => $this->submits,
			'visibilityMap' => $modelClass::$visibilityMap,
			'statusMap' => $modelClass::$statusMap
		]);
	}

	public function actionCreate() {

		$modelClass	= $this->modelService->getModelClass();

		$model = new $modelClass;

		$model->siteId		= Yii::$app->core->siteId;
		$model->type		= $this->type;
		$model->visibility	= $modelClass::VISIBILITY_PUBLIC;
		$model->captcha		= false;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->create( $model );

			if( $this->model ) {

				return $this->redirect( $this->returnUrl );
			}
		}

		$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

		return $this->render( 'create', [
			'model' => $model,
			'templatesMap' => $templatesMap,
			'visibilityMap' => $modelClass::$visibilityMap,
			'statusMap' => $modelClass::$subStatusMap
		]);
	}

	public function actionUpdate( $id ) {

		$modelClass	= $this->modelService->getModelClass();

		$model = $this->modelService->getById( $id );

		// Update if exist
		if( isset( $model ) ) {

			$template = $model->template;

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->update( $model, [
					'admin' => true, 'oldTemplate' => $template
				]);

				return $this->redirect( $this->returnUrl );
			}

			$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

			// Render
			return $this->render( 'update', [
				'model' => $model,
				'templatesMap' => $templatesMap,
				'visibilityMap' => $modelClass::$visibilityMap,
				'statusMap' => $modelClass::$subStatusMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$modelClass	= $this->modelService->getModelClass();

		$model = $this->modelService->getById( $id );

		// Delete if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) ) {

				$this->model = $model;

				$this->modelService->delete( $model );

				return $this->redirect( $this->returnUrl );
			}

			$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

			return $this->render( 'delete', [
				'model' => $model,
				'templatesMap' => $templatesMap,
				'visibilityMap' => $modelClass::$visibilityMap,
				'statusMap' => $modelClass::$subStatusMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
