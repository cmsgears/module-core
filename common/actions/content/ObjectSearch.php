<?php
namespace cmsgears\core\common\actions\content;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

class ObjectSearch extends AutoSearch {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public $admin = false;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// AutoSearch ----------------------------

	public function run() {

		$name	= Yii::$app->request->post( 'name' );
		$type	= Yii::$app->request->post( 'type' );
		$data	= [];

		// For models having type columns
		if( isset( $type ) ) {

			$data = $this->searchByNameType( $name, $type );
		}
		else {

			$data = $this->searchByName( $name );
		}

		// Trigger Ajax Success
		return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
	}

	private function searchByName( $name, $config = [] ) {

		$modelService = $this->controller->modelService;

		$modelClass	= $modelService->getModelClass();
		$modelTable	= $modelService->getModelTable();

		$config[ 'query' ]		= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$config[ 'columns' ]	= isset( $config[ 'columns' ] ) ? $config[ 'columns' ] : [ "$modelTable.id", "$modelTable.name" ];
		$config[ 'array' ]		= isset( $config[ 'array' ] ) ? $config[ 'array' ] : true;
		$config[ 'siteId' ]		= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : ($modelClass::isMultiSite() ? Yii::$app->core->siteId : null );
		$config[ 'sort' ]		= isset( $config[ 'sort' ] ) ? $config[ 'sort' ] : [ 'name' => SORT_ASC ];

		$config[ 'query' ]->andWhere( "$modelTable.`admin`=:admin AND $modelTable.`shared`=1 AND $modelTable.`name` like :name", [ ':admin' => $this->admin, ':name' => "$name%" ] );

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		if( $modelClass::isMultiSite() && !$ignoreSite ) {

			$config[ 'conditions' ][ "$modelTable.siteId" ]	= $config[ 'siteId' ];
		}

		$config[ 'advanced' ] = true;

		return $modelService->getModels( $config );
	}

	private function searchByNameType( $name, $type, $config = [] ) {

		$modelService = $this->controller->modelService;

		$modelTable	= $modelService->getModelTable();

		$config[ 'conditions' ][ "$modelTable.type" ] = $type;

		$config[ 'sort' ] = isset( $config[ 'sort' ] ) ? $config[ 'sort' ] : [ 'name' => SORT_ASC, 'type' => SORT_ASC ];

		return $this->searchByName( $name, $config );
	}

}
