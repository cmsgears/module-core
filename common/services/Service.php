<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;
use yii\db\Query;
use yii\data\Pagination;
use yii\helpers\HtmlPurifier;

// CMG Imports
use cmsgears\core\common\models\entities\CMGEntity;

/**
 * The class Service defines several static methods used for pagination and generating map and list by specifying the columns.
 */
class Service {

	/**
	 * The default page limit.
	 */
	const PAGE_LIMIT	= 10;

	// Pagination -------------------------------------------------
	
	/**
	 * The method getPaginationDetails accept the entity class and various parameters to generate the pagination.
	 */
	public static function getPaginationDetails( $entity, $args ) {
		
		// args
		$query			= isset( $args[ 'query' ] ) ? $args[ 'query' ] : null;
		$limit			= isset( $args[ 'limit' ] ) ? $args[ 'limit' ] : null;
		$conditions		= isset( $args[ 'conditions' ] ) ? $args[ 'conditions' ] : null;
		$sort			= isset( $args[ 'sort' ] ) ? $args[ 'sort' ] : null;
		$searchCol		= isset( $args[ 'search-col' ] ) ? $args[ 'search-col' ] : null;
		$route			= isset( $args[ 'route' ] ) ? $args[ 'route' ] : null;

		$pagination	= array();
		
		if( !isset( $query ) ) {

			$query 	= $entity::find();
		}

		// Page Limit ----------

		if( $limit == null ) {

			$limit = self::PAGE_LIMIT;
		}

		// Filtering -----------

		if( isset( $conditions ) ) {

			$query 	= $query->where( $conditions );
		}
		
		// Sorting ------------

		if( isset( $sort ) ) {

			$query->orderBy( $sort->orders );
		}

		// Searching ----------

		$searchTerms	= Yii::$app->request->getQueryParam( "search" );

		if( isset( $searchTerms ) && strlen( $searchTerms ) > 0 && isset( $searchCol ) ) {

			$searchTerms	= HtmlPurifier::process( $searchTerms );
			$searchQuery 	= CMGEntity::generatSearchQuery( $searchCol, $searchTerms );
			$query 			= $query->andWhere( $searchQuery );
		}

		// $command = $query->createCommand(); var_dump( $command );

		// Generate Results ---

	    $countQuery 			= clone $query;
		$total					= $countQuery->count();
	    $pages 					= new Pagination( [ 'totalCount' => $total, 'route' => $route ] );
		$pages->pageSize		= $limit;
	    $models 				= $query->offset( $pages->offset )
				        			    ->limit( $pages->limit )
	        						    ->all();

		$pagination['page']		= $models;
		$pagination['pages']	= $pages;
		$pagination['total']	= $total;

		return $pagination;
	}

	// Maps -------------------------------------------------------

	/**
	 * The method findNameValueMap returns an associative array for the defined table and columns. It also apply the provided conditions.
	 */
	public static function findNameValueMap( $nameColumn, $valueColumn, $model, $conditions = [] ) {

		$arrayList  = self::findNameValueList( $nameColumn, $valueColumn, $model, $conditions );
		$map		= [];
		
		foreach ( $arrayList as $item ) {
			
			$map[ $item['name'] ] = $item['value']; 
		}

		return $map;
	}

	// Lists ------------------------------------------------------

	/**
	 * The method findNameList returns an array of list for given column
	 */
	public static function findList( $column, $model, $conditions = [] ) {
		
		$query	= new Query();

		// Build Query
		if( isset( $conditions ) ) {

			$query->select( $column.' as col' )
			 	  ->from( $model )->where( $conditions );
		}
		else {

			$query->select( $column.' as col' )
				  ->from( $model );
		}

		// Create command
		$command 	= $query->createCommand();

		// Execute the command
		$list 		= $command->queryAll();


		$keyList	= array();

		foreach ( $list as $item ) {
			
			$keyList[] = $item[ 'col' ]; 
		}

		return $keyList;
	}

	/**
	 * The method findNameValueList returns an array of associative arrays having name and value as keys for the defined columns.
	 */
	public static function findNameValueList( $nameColumn, $valueColumn, $model, $conditions = [] ) {

		$query 		= new Query();

		// Build Query
		if( isset( $conditions ) ) {

			$query->select( $nameColumn.' as name,'. $valueColumn .' as value' )
			 	  ->from( $model )->where( $conditions );
		}
		else {

			$query->select( $nameColumn.' as name,'. $valueColumn .' as value' )
				  ->from( $model );			
		}

		// Create command
		$command = $query->createCommand();

		// Execute the command
		$arrayList = $command->queryAll();

		return $arrayList;
	}

	/**
	 * The method findIdNameList returns an array of associative arrays having id and name as keys for the defined columns.
	 */
	public static function findIdNameList( $idColumn, $nameColumn, $model, $conditions = [] ) {

		$query 		= new Query();

		// Build Query
		if( isset( $conditions ) ) {

			$query->select( $idColumn.' as id,'. $nameColumn .' as name' )
			 	  ->from( $model )->where( $conditions );
		}
		else {

			$query->select( $idColumn.' as id,'. $nameColumn .' as name' )
				  ->from( $model );
		}

		// Create command
		$command = $query->createCommand();

		// Execute the command
		$arrayList = $command->queryAll();

		return $arrayList;
	}
}

?>