<?php
namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use \Yii;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Template; 

use cmsgears\core\admin\services\TemplateService;

abstract class TemplateController extends Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
		
		$this->returnUrl	= Url::previous( 'templates' );
	}

	// Instance Methods --------------------------------------------

	// BaseRoleController -----------------

	public function actionAll( $type = null ) {

		$dataProvider = TemplateService::getPaginationByType( $type );

	    return $this->render( '@cmsgears/module-core/admin/views/template/all', [

			'dataProvider' => $dataProvider
	    ]);
	}

	public function actionCreate( $type = null ) {

		$model		= new Template();

		$model->setScenario( 'create' );

		if( isset( $type ) ) {

			$model->type = $type;
		}

		if( $model->load( Yii::$app->request->post(), 'Template' )  && $model->validate() ) {

			if( TemplateService::create( $model ) ) { 

				return $this->redirect( $this->returnUrl );
			}
		}

    	return $this->render( '@cmsgears/module-core/admin/views/template/create', [
    		'model' => $model,
    		'renderers' => Yii::$app->cmgCore->renderers
    	]);
	}	
 	
	public function actionUpdate( $id, $type = null ) {

		// Find Model
		$model		= TemplateService::findById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$model->setScenario( 'update' );

			if( $model->load( Yii::$app->request->post(), 'Template' )  && $model->validate() ) {

				if( TemplateService::update( $model ) ) {

					return $this->redirect( $this->returnUrl );
				} 
			}

	    	return $this->render( '@cmsgears/module-core/admin/views/template/update', [
	    		'model' => $model,
	    		'renderers' => Yii::$app->cmgCore->renderers
	    	]);
		}
		
		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id, $type = null ) {

		// Find Model
		$model	= TemplateService::findById( $id );

		// Delete/Render if exist		
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), 'Template' )  && $model->validate() ) {

				if( TemplateService::delete( $model ) ) { 

					return $this->redirect( $this->returnUrl );
				}
			}

	    	return $this->render( '@cmsgears/module-core/admin/views/template/delete', [
	    		'model' => $model,
	    		'renderers' => Yii::$app->cmgCore->renderers
	    	]);
		}
		
		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>