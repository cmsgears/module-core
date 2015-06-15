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
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public static function getDataProvider( $entity, $params ) {

		// params to generate query
		$query			= isset( $params[ 'query' ] ) ? $params[ 'query' ] : $entity::find();
		$limit			= isset( $params[ 'limit' ] ) ? $params[ 'limit' ] : self::PAGE_LIMIT;
		$conditions		= isset( $params[ 'conditions' ] ) ? $params[ 'conditions' ] : null;
		$filters		= isset( $params[ 'filters' ] ) ? $params[ 'filters' ] : null;
		$searchCol		= isset( $params[ 'search-col' ] ) ? $params[ 'search-col' ] : null;
		$sort			= isset( $params[ 'sort' ] ) ? $params[ 'sort' ] : null;
		$route			= isset( $params[ 'route' ] ) ? $params[ 'route' ] : null;

		$pagination	= array();

		// Filtering -----------

		if( isset( $conditions ) ) {

			$query 	= $query->where( $conditions );
		}

		// Searching ----------

		// Single Column
		$searchTerms	= Yii::$app->request->getQueryParam( "search" );

		if( isset( $searchTerms ) && strlen( $searchTerms ) > 0 && isset( $searchCol ) ) {

			$searchTerms	= HtmlPurifier::process( $searchTerms );
			$searchQuery 	= CMGEntity::generatSearchQuery( $searchCol, $searchTerms );
			$query 			= $query->andWhere( $searchQuery );
		}

		// Multiple Columns
		if( isset( $filters ) ) {

			foreach ( $filters as $filter ) {

				$query 	= $query->andFilterWhere( $filter );	
			}
		}

		// Print Query
		if( isset( $params[ 'pquery' ] ) && $params[ 'pquery' ] ) {

			$command = $query->createCommand();

			var_dump( $command );
		}

		// Data Provider
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
	 * @param string $tableName
	 * @param array $conditions
	 */
	public static function findMap( $keyColumn, $valueColumn, $tableName, $conditions = [] ) {

		$arrayList  = self::findNameValueList( $keyColumn, $valueColumn, $tableName, $conditions );
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
	 * @param string $tableName
	 * @param array $conditions 
	 */
	public static function findList( $column, $tableName, $conditions = [] ) {
		
		$query	= new Query();

		// Build Query
		if( isset( $conditions ) ) {

			$query->select( $column )
			 	  ->from( $tableName )->where( $conditions );
		}
		else {

			$query->select( $column )
				  ->from( $tableName );
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
	 * @param string $tableName
	 * @param array $conditions
	 * @param boolean $asArray
	 */
	public static function findNameValueList( $nameColumn, $valueColumn, $tableName, $conditions = [], $asArray = false ) {

		$query 		= new Query();

		// Build Query
		if( isset( $conditions ) ) {

			$query->select( $nameColumn.' as name,'. $valueColumn .' as value' )
			 	  ->from( $tableName )->where( $conditions );
		}
		else {

			$query->select( $nameColumn.' as name,'. $valueColumn .' as value' )
				  ->from( $tableName );			
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
	 * @param string $tableName
	 * @param array $conditions
	 * @param boolean $asArray 
	 */
	public static function findIdNameList( $idColumn, $nameColumn, $tableName, $conditions = [], $asArray = false ) {

		$query 		= new Query();

		// Build Query
		if( isset( $conditions ) ) {

			$query->select( $idColumn.' as id,'. $nameColumn .' as name' )
			 	  ->from( $tableName )->where( $conditions );
		}
		else {

			$query->select( $idColumn.' as id,'. $nameColumn .' as name' )
				  ->from( $tableName );
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