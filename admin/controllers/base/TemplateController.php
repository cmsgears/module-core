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

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\File;

/**
 * TemplateController provide actions specific to template management.
 *
 * @since 1.0.0
 */
abstract class TemplateController extends CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $title;

	public $fileRender;

	// Protected --------------

	protected $type;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Views
		$this->setViewPath( '@cmsgears/module-core/admin/views/template' );

		// Permission
		$this->crudPermission = CoreGlobal::PERM_CORE;

		// Config
		$this->fileRender = true;

		// Services
		$this->modelService = Yii::$app->factory->get( 'templateService' );

		// Notes: Configure type, sidebar and returnUrl exclusively in child classes.
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TemplateController --------------------

	public function actionAll( $config = [] ) {

		$dataProvider = $this->modelService->getPageByType( $this->type );

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'type' => $this->type
		]);
	}

	public function actionCreate( $config = [] ) {

		$model = $this->modelService->getModelObject();

		$model->siteId	= Yii::$app->core->siteId;
		$model->type	= $this->type;

		$preview = File::loadFile( null, 'Banner' );

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->add( $model, [ 'admin' => true, 'preview' => $preview ] );

			if( $this->model ) {

				return $this->redirect( 'all' );
			}
		}

		return $this->render( 'create', [
			'model' => $model,
			'preview' => $preview
		]);
	}

	public function actionUpdate( $id, $config = [] ) {

		// Find Model
		$model = $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$preview = File::loadFile( $model->preview, 'Banner' );

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->update( $model, [
					'admin' => true, 'preview' => $preview
				]);

				return $this->redirect( $this->returnUrl );
			}

			return $this->render( 'update', [
				'model' => $model,
				'preview' => $preview
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionData( $id ) {

		// Find Model
		$model = $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$dataClass = $model->dataPath;

			$data = new $dataClass( $model->getDataMeta( 'tdata' ) );

			$this->setViewPath( $model->dataForm );

			if( $data->load( Yii::$app->request->post(), $data->getClassName() ) && $data->validate() ) {

				$this->modelService->updateDataMeta( $model, 'tdata', $data );

				return $this->redirect( $this->returnUrl );
			}

			return $this->render( 'data', [
				'model' => $model,
				'data' => $data
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionAttributes( $id ) {

		// Find Model
		$model = $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$attributesClass = $model->attributesPath;

			$attributes = new $attributesClass( $model->getDataMeta( 'tattributes' ) );

			$this->setViewPath( $model->attributesForm );

			if( $attributes->load( Yii::$app->request->post(), $attributes->getClassName() ) && $attributes->validate() ) {

				$this->modelService->updateDataMeta( $model, 'tattributes', $attributes );

				return $this->redirect( $this->returnUrl );
			}

			return $this->render( 'attributes', [
				'model' => $model,
				'attributes' => $attributes
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionConfig( $id ) {

		// Find Model
		$model = $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$configClass = $model->configPath;

			$config = new $configClass( $model->getDataMeta( 'tconfig' ) );

			$this->setViewPath( $model->configForm );

			if( $config->load( Yii::$app->request->post(), $config->getClassName() ) && $config->validate() ) {

				$this->modelService->updateDataMeta( $model, 'tconfig', $config );

				return $this->redirect( $this->returnUrl );
			}

			return $this->render( 'config', [
				'model' => $model,
				'config' => $config
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionSettings( $id ) {

		// Find Model
		$model = $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$settingsClass = $model->settingsPath;

			$settings = new $settingsClass( $model->getDataMeta( 'tsettings' ) );

			$this->setViewPath( $model->settingsForm );

			if( $settings->load( Yii::$app->request->post(), $settings->getClassName() ) && $settings->validate() ) {

				$this->modelService->updateDataMeta( $model, 'tsettings', $settings );

				return $this->redirect( $this->returnUrl );
			}

			return $this->render( 'settings', [
				'model' => $model,
				'settings' => $settings
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
