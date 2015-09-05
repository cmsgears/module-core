<?php
namespace cmsgears\core\admin\controllers\country;

// Yii Imports
use \Yii;  
use yii\helpers\Url;
use yii\filters\VerbFilter; 
use yii\base\Exception;
use yii\web\HttpException;

// CMG Imports 
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\models\entities\Province;

use cmsgears\core\common\services\ProvinceService;
use cmsgears\core\common\services\CountryService;

use cmsgears\core\admin\controllers\BaseController; 

class ProvinceController extends BaseController {

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

	// OptionController --------------------
 
	public function actionAll( $id ) {
		  
		$dataProvider	= ProvinceService::getPagination( [ 'conditions' => [ 'countryId' => $id ] ] ); 
		
		Url::remember( [ 'country/province/all?id='.$id ], 'provinces' );
		
		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'countryId' => $id
		] );
	}
	
	public function actionCreate( $id ) { 

		$model		= new Province();		  
		
		$model->countryId	= $id;
		$model->setScenario( "create" );
		 
		if( $model->load( Yii::$app->request->post(), "Province" )  && $model->validate() ) {

			if( ProvinceService::create( $model ) ) { 

				$this->redirect( Url::previous( 'provinces' ) );
			}
		} 
		
    	return $this->render('create', [ 
    		'model' => $model,
    		'returnUrl' => Url::previous( 'provinces' )
    	]);
	}
	
	public function actionUpdate( $id ) { 

		$model		= ProvinceService::findById( $id );		
		
		$model->setScenario( "update" ); 
		 
		if( $model->load( Yii::$app->request->post(), "Province" )  && $model->validate() ) {

			if( ProvinceService::update( $model ) ) { 

				$this->redirect( Url::previous( 'provinces' ) );
				 			 
			} 
		} 
	 
    	return $this->render('update', [ 
    		'model' => $model,
    		'id' => $id,
    		'returnUrl'	=> Url::previous( "provinces" )
    	]);
	} 
	
	public function actionDelete( $id ) {

		// Find Model
		$model		= ProvinceService::findById( $id );

		// Delete/Render if exist
		
		if( isset( $model ) ) {  

			if( $model->load( Yii::$app->request->post(), 'Province' )  && $model->validate() ) { 
						
				try {
					
			    	ProvinceService::delete( $model );
					
					return $this->redirect( Url::previous( 'provinces' ) );
			    } 
			    catch( Exception $e) {
			    	 
				    throw new HttpException(409,  Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  ); 
				}					 
			}
 
	    	return $this->render( 'delete', [
	    		'model' => $model, 
	    		'returnUrl' => Url::previous( "provinces" )
	    	]);
		}
		
		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
?>