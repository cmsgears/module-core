<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
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

	public function actionAll( $type = null ) {

		$dataProvider = null;

		if( isset( $type ) ) {

			$dataProvider = CategoryService::getPaginationByType( $type );
		}
		else {

			$dataProvider = CategoryService::getPagination();
		}

	    return $this->render( 'all', [
	    
			'dataProvider' => $dataProvider
	    ]);
	}

	public function actionCreate( $returnUrl, $type = null ) {

		$model		= new Category();
		$avatar 	= new CmgFile(); 

		$model->setScenario( 'create' );

		if( isset( $type ) ) {

			$model->type = $type;
		}

		if( $model->load( Yii::$app->request->post(), 'Category' )  && $model->validate() ) {

			$avatar->load( Yii::$app->request->post(), 'Avatar' ); 

			if( CategoryService::create( $model, $avatar ) ) { 

				return $this->redirect( [ 'all' ] );
			} 
		} 

    	return $this->render( 'create', [
    		'model' => $model, 
    		'avatar' => $avatar,
    		'returnUrl' => $returnUrl
    	]);
	}	
 	
	public function actionUpdate( $id, $returnUrl, $type = null ) {
		

		// Find Model
		$model		= CategoryService::findById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {
 
			$avatar 	= new CmgFile(); 

			$model->setScenario( 'update' );

			if( $model->load( Yii::$app->request->post(), 'Category' )  && $model->validate() ) {
	
				$avatar->load( Yii::$app->request->post(), 'Avatar' ); 

				if( CategoryService::update( $model, $avatar ) ) {
 

				return $this->redirect( [ 'all' ] );
			} 
			}

			$avatar			= $model->avatar;

	    	return $this->render( 'update', [
	    		'model' => $model, 
	    		'avatar' => $avatar,
	    		'returnUrl' => $returnUrl
	    	]);
		}
		
		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	} 
	
	public function actionDelete( $id, $returnUrl, $type = null ) {

		// Find Model
		$model		= CategoryService::findById( $id );

		// Update/Render if exist
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
					    	 
						    throw new HttpException(409,  Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  ); 
						}
					}
				}

				if( CategoryService::delete( $model, $avatar ) ) { 

					return $this->redirect( [ 'all' ] );
				}
			}

			$avatar	= $model->avatar;

	    	return $this->render( 'delete', [
	    		'model' => $model, 
	    		'avatar' => $avatar,
	    		'returnUrl' => $returnUrl
	    	]);
		}
		
		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>