<?php
namespace cmsgears\core\admin\controllers\dropdown;

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

use cmsgears\core\admin\controllers\BaseController;

// BM Imports
use billmaid\core\common\config\BmCoreGlobal;  

class OptionController extends BaseController {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods ------------------

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'index'  => [ 'permission' => BmCoreGlobal::PERM_ACCOUNT ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'all'  => ['get'], 
	                'create'  => ['get', 'post'],
	                'update'  => ['get', 'post'],
	                'delete'  => ['get', 'post']
                ]
            ]
        ];
    }

	// OptionController --------------------
 
	public function actionAll( $id ) {
		 
		Url::remember( [ "all?id=$id" ], 'options' );
		$dataProvider	= OptionService::getPagination( [ 'conditions' => [ 'categoryId' => $id ]] );
		$category		= CategoryService::findById( $id );
		
		return $this->render( '@cmsgears/module-core/admin/views/category/option/all', [
			'dataProvider' => $dataProvider,
			'category' => $category
		] );
	}
	
	public function actionCreate( $id ) { // $id = categoryId

		$model		= new Option();		  
		
		$model->setScenario( "create" );
		 
		if( $model->load( Yii::$app->request->post(), "Option" )  && $model->validate() ) {

			if( OptionService::create( $model ) ) { 

				$this->redirect( Url::previous( 'options' ) );
			}
		} 
		
    	return $this->render('@cmsgears/module-core/admin/views/category/option/create', [ 
    		'model' => $model,
    		'id' => $id,
    		'returnUrl'	=> Url::previous( "options" )
    	]);
	}
	
	public function actionUpdate( $id ) { // $id = categoryId

		$model		= OptionService::findById( $id );		 
		 
		if( $model->load( Yii::$app->request->post(), "Option" )  && $model->validate() ) {

			if( OptionService::update( $model ) ) { 

				$this->redirect( Url::previous( 'options' ) );
			}
		} 
		
    	return $this->render('@cmsgears/module-core/admin/views/category/option/update', [ 
    		'model' => $model,
    		'id' => $id,
    		'returnUrl'	=> Url::previous( "options" )
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
					
					return $this->redirect( Url::previous( 'options' ) );
			    } 
			    catch( Exception $e) {
			    	 
				    throw new HttpException(409,  Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  ); 
				}					 
			}
 
	    	return $this->render( '@cmsgears/module-core/admin/views/category/option/delete', [
	    		'model' => $model, 
	    		'returnUrl' => Url::previous( "options" )
	    	]);
		}
		
		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
?>