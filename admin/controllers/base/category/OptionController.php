<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\admin\controllers\base\category;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\admin\controllers\base\Controller;

/**
 * OptionController provides actions specific to option model.
 *
 * @since 1.0.0
 */
class OptionController extends Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $categoryService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Views
		$this->setViewPath( '@cmsgears/module-core/admin/views/optiongroup/option/' );

		// Services
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

		$model = $this->modelService->getModelObject();

		$model->categoryId	= $cid;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->create( $model );

			return $this->redirect( "all?cid=$cid" );
		}

		return $this->render( 'create', [
			'model' => $model
		]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model = $this->modelService->getById( $id );

		// Update if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->update( $model );

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
		$model = $this->modelService->getById( $id );

		// Delete if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				try {

					$this->model = $model;

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
