<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\base\Exception;
use yii\web\HttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Template; 

use cmsgears\core\admin\services\TemplateService;

abstract class BaseTemplateController extends BaseController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
		
		$this->returnUrl	= Url::previous( 'templates' );
	}

	// Instance Methods --------------------------------------------

	// BaseRoleController -----------------

	public function actionAll( $sidebar = [], $type = null ) {

		$dataProvider = TemplateService::getPaginationByType( $type );

	    return $this->render( '@cmsgears/module-core/admin/views/template/all', [

			'dataProvider' => $dataProvider,
    		'sidebarParent' => $sidebar[ 'parent' ],
    		'sidebarChild' => $sidebar[ 'child' ]
	    ]);
	}

	public function actionCreate( $sidebar = [], $type = null ) {

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
    		'returnUrl' => $this->returnUrl,
    		'sidebarParent' => $sidebar[ 'parent' ],
    		'sidebarChild' => $sidebar[ 'child' ]
    	]);
	}	
 	
	public function actionUpdate( $id, $sidebar = [], $type = null ) {

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
	    		'returnUrl' => $this->returnUrl,
    			'sidebarParent' => $sidebar[ 'parent' ],
    			'sidebarChild' => $sidebar[ 'child' ]
	    	]);
		}
		
		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id, $sidebar = [], $type = null ) {

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
	    		'returnUrl' => $this->returnUrl,
    			'sidebarParent' => $sidebar[ 'parent' ],
    			'sidebarChild' => $sidebar[ 'child' ]
	    	]);
		}
		
		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>