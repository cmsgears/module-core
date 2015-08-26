<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\db\IntegrityException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;  

use cmsgears\core\common\services\CountryService;

use cmsgears\core\common\models\entities\Country;

// BM Imports
use billmaid\core\common\config\BmCoreGlobal;  

class CountryController extends BaseController {

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
	                'all'  => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'create'  => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'update'  => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'delete'  => [ 'permission' => CoreGlobal::PERM_CORE ]
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

	// DropdownController --------------------

	public function actionAll() { 
		
		$dataProvider = CountryService::getPagination( );
	 
	    return $this->render( 'all', [
	         'dataProvider' => $dataProvider
	    ]);
	}
	
	public function actionCreate() { 

		$model		= new Country();		  
		
		$model->setScenario( "create" );
		 
		if( $model->load( Yii::$app->request->post(), "Country" )  && $model->validate() ) {

			if( CountryService::create( $model ) ) { 

				$this->redirect( [ 'all' ] );
			}
		} 
		
    	return $this->render('create', [ 
    		'model' => $model
    	]);
	} 
	
	public function actionUpdate( $id ) {

		$model		= CountryService::findById( $id );		  
		 		 
		if( $model->load( Yii::$app->request->post(), "Country" )  && $model->validate() ) {

			if( CountryService::update( $model ) ) { 

				$this->redirect( [ 'all' ] );
			}
		} 
		
    	return $this->render('update', [ 
    		'model' => $model
    	]);
	} 
	
	public function actionDelete( $id ) {

		// Find Model
		$model		= CountryService::findById( $id );

		// Delete/Render if exist
		
		if( isset( $model ) ) {  

			if( $model->load( Yii::$app->request->post(), 'Country' )  && $model->validate() ) { 
						
				try {
					
			    	CountryService::delete( $model );
					
					return $this->redirect( [ 'all' ] );
			    } 
			    catch( Exception $e) {
			    	 
				    throw new HttpException(409,  Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  ); 
				}					 
			}
 
	    	return $this->render( 'delete', [
	    		'model' => $model
	    	]);
		}
		
		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	} 
}
?>