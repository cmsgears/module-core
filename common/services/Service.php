<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;
use yii\db\Query;
use yii\data\ActiveDataProvider;
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
	 * The method getDataProvider accept the entity class and various parameters to generate the active data provider.
	 * @param Model $entity
	 * @param array $args
	 * @return ActiveDataProvider
	 */
	public static function getDataProvider( $entity, $args ) {
		
		// args
		$query			= isset( $args[ 'query' ] ) ? $args[ 'query' ] : null;
		$limit			= isset( $args[ 'limit' ] ) ? $args[ 'limit' ] : null;
		$conditions		= isset( $args[ 'conditions' ] ) ? $args[ 'conditions' ] : null;
		$filter			= isset( $args[ 'filter' ] ) ? $args[ 'filter' ] : null;
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

		if( isset( $filter ) ) {
			
			$query 	= $query->andFilterWhere( $filter );
		}

		// TODO: multiple column filters
		// $query->andFilterWhere(['like', 'alias', $this->alias]);
        // $query->andFilterWhere(['like', 'title', $this->title]);

		// Sorting ------------
		/*
		if( isset( $sort ) ) {

			$query->orderBy( $sort->orders );
		}
		*/
		// Searching ----------

		$searchTerms	= Yii::$app->request->getQueryParam( "search" );

		if( isset( $searchTerms ) && strlen( $searchTerms ) > 0 && isset( $searchCol ) ) {

			$searchTerms	= HtmlPurifier::process( $searchTerms );
			$searchQuery 	= CMGEntity::generatSearchQuery( $searchCol, $searchTerms );
			$query 			= $query->andWhere( $searchQuery );
		}

		// $command = $query->createCommand(); var_dump( $command );

		// Generate Results ---
		/*
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
		 */

	    $dataProvider	= new ActiveDataProvider([
            'query' => $query,
            'sort' => $sort,
            'pagination' => [
            	'pageSize' => $limit
            ]
        ]);

		return $dataProvider;
	}

	// Maps -------------------------------------------------------

	/**
	 * The method findMap returns an associative array for the defined table and columns. It also apply the provided conditions.
	 * @param string $keyColumn
	 * @param string $valueColumn
	 * @param string $table
	 * @param array $conditions
	 */
	public static function findMap( $keyColumn, $valueColumn, $table, $conditions = [] ) {

		$arrayList  = self::findNameValueList( $keyColumn, $valueColumn, $table, $conditions );
		$map		= [];
		
		foreach ( $arrayList as $item ) {
			
			$map[ $item['name'] ] = $item['value']; 
		}

		return $map;
	}

	// Lists ------------------------------------------------------

	/**
	 * The method findNameList returns an array of list for given column
	 * @param string $column
	 * @param string $table
	 * @param array $conditions 
	 */
	public static function findList( $column, $table, $conditions = [] ) {
		
		$query	= new Query();

		// Build Query
		if( isset( $conditions ) ) {

			$query->select( $column )
			 	  ->from( $table )->where( $conditions );
		}
		else {

			$query->select( $column )
				  ->from( $table );
		}
		
		// Get result as array
		$query->asArray();

		// Create command
		$command 	= $query->createCommand();

		// Execute the command
		$list 		= $command->queryAll();

		return $list;
	}

	/**
	 * The method findNameValueList returns an array of associative arrays having name and value as keys for the defined columns.
	 * @param string $nameColumn
	 * @param string $valueColumn
	 * @param string $table
	 * @param array $conditions
	 * @param boolean $asArray
	 */
	public static function findNameValueList( $nameColumn, $valueColumn, $table, $conditions = [], $asArray = false ) {

		$query 		= new Query();

		// Build Query
		if( isset( $conditions ) ) {

			$query->select( $nameColumn.' as name,'. $valueColumn .' as value' )
			 	  ->from( $table )->where( $conditions );
		}
		else {

			$query->select( $nameColumn.' as name,'. $valueColumn .' as value' )
				  ->from( $table );			
		}

		// Get result as array instead of associative array		
		if( $asArray ) {

			$query->asArray();
		}

		// Create command
		$command = $query->createCommand();

		// Execute the command
		$arrayList = $command->queryAll();

		return $arrayList;
	}

	/**
	 * The method findIdNameList returns an array of associative arrays having id and name as keys for the defined columns.
	 * @param string $idColumn
	 * @param string $nameColumn
	 * @param string $table
	 * @param array $conditions
	 * @param boolean $asArray 
	 */
	public static function findIdNameList( $idColumn, $nameColumn, $table, $conditions = [], $asArray = false ) {

		$query 		= new Query();

		// Build Query
		if( isset( $conditions ) ) {

			$query->select( $idColumn.' as id,'. $nameColumn .' as name' )
			 	  ->from( $table )->where( $conditions );
		}
		else {

			$query->select( $idColumn.' as id,'. $nameColumn .' as name' )
				  ->from( $table );
		}
		
		// Get result as array instead of associative array		
		if( $asArray ) {

			$query->asArray();
		}

		// Create command
		$command = $query->createCommand();

		// Execute the command
		$arrayList = $command->queryAll();

		return $arrayList;
	}
}

?>