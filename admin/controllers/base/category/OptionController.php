<?php
namespace cmsgears\core\admin\controllers\base\category;

// Yii Imports
use \Yii;  
use yii\helpers\Url;
use yii\filters\VerbFilter; 
use yii\base\Exception;
use yii\web\HttpException;

// CMG Imports 
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\models\entities\Option;

use cmsgears\core\admin\services\OptionService;
use cmsgears\core\admin\services\CategoryService;

class OptionController extends \cmsgears\core\admin\controllers\base\Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
		
		$this->returnUrl	= Url::previous( 'options' );
	}

	// Instance Methods ------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'all'  => [ 'permission' => CoreGlobal::PERM_CORE ], 
	                'create'  => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'update'  => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'delete'  => [ 'permission' => CoreGlobal::PERM_CORE ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'all'  => [ 'get' ], 
	                'create'  => [ 'get', 'post' ],
	                'update'  => [ 'get', 'post' ],
	                'delete'  => [ 'get', 'post' ]
                ]
            ]
        ];
    }

	// OptionController --------------------
 
	public function actionAll( $id ) {

		$dataProvider	= OptionService::getPagination( [ 'conditions' => [ 'categoryId' => $id ]] );
		$category		= CategoryService::findById( $id );
		
		return $this->render( '@cmsgears/module-core/admin/views/category/option/all', [
			'dataProvider' => $dataProvider,
			'category' => $category
		] );
	}
	
	public function actionCreate( $id ) {

		$model		= new Option();		  
		
		$model->setScenario( "create" );
		 
		if( $model->load( Yii::$app->request->post(), "Option" )  && $model->validate() ) {

			if( OptionService::create( $model ) ) { 

				$this->redirect( $this->returnUrl );
			}
		} 
		
    	return $this->render('@cmsgears/module-core/admin/views/category/option/create', [ 
    		'model' => $model,
    		'id' => $id
    	]);
	}
	
	public function actionUpdate( $id ) {

		$model		= OptionService::findById( $id );		 
		 
		if( $model->load( Yii::$app->request->post(), "Option" )  && $model->validate() ) {

			if( OptionService::update( $model ) ) { 

				$this->redirect( $this->returnUrl );
			}
		} 
		
    	return $this->render('@cmsgears/module-core/admin/views/category/option/update', [ 
    		'model' => $model,
    		'id' => $id
    	]);
	} 
	
	public function actionDelete( $id ) {

		// Find Model
		$model		= OptionService::findById( $id );

		// Delete/Render if exist
		
		if( isset( $model ) ) {  

			if( $model->load( Yii::$app->request->post(), 'Option' )  && $model->validate() ) { 
						
				try {
					
			    	OptionService::delete( $model );
					
					$this->redirect( $this->returnUrl );
			    } 
			    catch( Exception $e) {
			    	 
				    throw new HttpException(409,  Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  ); 
				}					 
			}
 
	    	return $this->render( '@cmsgears/module-core/admin/views/category/option/delete', [
	    		'model' => $model
	    	]);
		}
		
		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
?>