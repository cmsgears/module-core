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

use cmsgears\core\common\models\entities\CmgFile; 

use cmsgears\core\common\models\entities\Category; 

use cmsgears\core\admin\services\CategoryService;  
use cmsgears\core\admin\services\OptionService; 

abstract class BaseCategoryController extends BaseController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// BaseRoleController -----------------

	public function actionAll( $type = null, $dropDown = false  ) {

		$dataProvider = null;

		if( isset( $type ) ) {

			$dataProvider = CategoryService::getPaginationByType( $type );
		}
		else {

			$dataProvider = CategoryService::getPagination();
		}

	    return $this->render( '@cmsgears/module-core/admin/views/category/all', [
	    
			'dataProvider' => $dataProvider,
    		'dropDown' => $dropDown
	    ]);
	}

	public function actionCreate( $type = null, $dropDown = false ) {

		$model				= new Category();
		$avatar 			= new CmgFile(); 
		$this->returnUrl	= Url::previous( 'categories' );

		$model->setScenario( 'create' );

		if( isset( $type ) ) {

			$model->type = $type;
		}

		if( $model->load( Yii::$app->request->post(), 'Category' )  && $model->validate() ) {

			$avatar->load( Yii::$app->request->post(), 'Avatar' ); 

			if( CategoryService::create( $model, $avatar ) ) { 

				return $this->redirect( $this->returnUrl );
			} 
		} 

    	return $this->render( '@cmsgears/module-core/admin/views/category/create', [
    		'model' => $model, 
    		'avatar' => $avatar,
    		'dropDown' => $dropDown
    	]);
	}	
 	
	public function actionUpdate( $id, $type = null, $dropDown = false  ) {
		
		// Find Model
		$model				= CategoryService::findById( $id );
		$this->returnUrl	= Url::previous( 'categories' );

		// Update/Render if exist
		if( isset( $model ) ) {
 
			$avatar 	= new CmgFile(); 

			$model->setScenario( 'update' );

			if( $model->load( Yii::$app->request->post(), 'Category' )  && $model->validate() ) {
	
				$avatar->load( Yii::$app->request->post(), 'Avatar' ); 

				if( CategoryService::update( $model, $avatar ) ) {
 

					return $this->redirect( $this->returnUrl );
				} 
			}

			$avatar			= $model->avatar;

	    	return $this->render( '@cmsgears/module-core/admin/views/category/update', [
	    		'model' => $model, 
	    		'avatar' => $avatar,
    			'dropDown' => $dropDown
	    	]);
		}
		
		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	} 
	
	public function actionDelete( $id, $type = null, $dropDown = false  ) {

		// Find Model
		$model				= CategoryService::findById( $id );
		$this->returnUrl	= Url::previous( 'categories' );

		// Delete/Render if exist
		
		if( isset( $model ) ) {
 
			$avatar 	= new CmgFile();  

			if( $model->load( Yii::$app->request->post(), 'Category' )  && $model->validate() ) {
	
				$avatar->load( Yii::$app->request->post(), 'Avatar' ); 
				
				$categoryOptions	= OptionService::findByCategoryId( $id );
				
				if( isset( $categoryOptions ) ) {
				
					foreach( $categoryOptions as $option ) { 
						
						try {
							
					    	OptionService::delete( $option );
					    } 
					    catch( Exception $e) {
					    	 
						    throw new HttpException( 409,  Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  ); 
						}
					}
				}

				if( CategoryService::delete( $model, $avatar ) ) { 

					return $this->redirect( $this->returnUrl );
				}
			}

			$avatar	= $model->avatar;

	    	return $this->render( '@cmsgears/module-core/admin/views/category/delete', [
	    		'model' => $model, 
	    		'avatar' => $avatar,
    			'dropDown' => $dropDown
	    	]);
		}
		
		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>