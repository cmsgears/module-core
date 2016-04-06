<?php
namespace cmsgears\core\common\services\base;

// Yii Imports
use \Yii;

use yii\db\Query;
use yii\data\ActiveDataProvider;

use yii\helpers\HtmlPurifier;
use yii\helpers\ArrayHelper;

/**
 * The class Service defines several static methods used for pagination and generating map and list by specifying the columns.
 */
class Service extends \yii\base\Component {

	/**
	 * The default page limit.
	 */
	const PAGE_LIMIT	= 10;

	// Pagination -------------------------------------------------

	/**
	 * The method getDataProvider accept the entity class and various parameters to generate the active data provider.
	 * @param Model $entity
	 * @param array $config
	 * @return ActiveDataProvider
	 */
	public static function getDataProvider( $entity, $config ) {

		// params to generate query
		$query			= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $entity::find();
		$limit			= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : self::PAGE_LIMIT;
		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;
		$filters		= isset( $config[ 'filters' ] ) ? $config[ 'filters' ] : null;
		$searchCol		= isset( $config[ 'search-col' ] ) ? $config[ 'search-col' ] : null;
		$sort			= isset( $config[ 'sort' ] ) ? $config[ 'sort' ] : false;
		$route			= isset( $config[ 'route' ] ) ? $config[ 'route' ] : null;

		$pagination	= array();

		// Filtering -----------

		if( isset( $conditions ) ) {

			$query 	= $query->andWhere( $conditions );
		}

		// Searching ----------

		$searchTerms	= Yii::$app->request->getQueryParam( 'search' );

		if( isset( $searchTerms ) && strlen( $searchTerms ) > 0 && isset( $searchCol ) ) {

			$searchTerms	= HtmlPurifier::process( $searchTerms );
			$searchQuery 	= self::generateSearchQuery( $searchCol, $searchTerms );
			$query 			= $query->andWhere( $searchQuery );
		}

		// Additional Filters --

		if( isset( $filters ) ) {

			foreach ( $filters as $filter ) {

				$query 	= $query->andFilterWhere( $filter );
			}
		}

		// Print to Debug -------

		if( isset( $config[ 'pquery' ] ) && $config[ 'pquery' ] ) {

			$command = $query->createCommand();

			var_dump( $command );
		}

		// Data Provider --------

	    $dataProvider	= new ActiveDataProvider([
            'query' => $query,
            'sort' => $sort,
            'pagination' => [
            	'pageSize' => $limit
            ]
        ]);

		return $dataProvider;
	}

	/**
	 * It generate search query for specified columns by parsing the comma seperated search terms
	 */
	public static function generateSearchQuery( $columns, $searchTerms ) {

		$searchTerms	= preg_split( '/,/', $searchTerms );
		$searchQuery	= "";

		if( is_array( $columns ) ) {

			foreach ( $columns as $ckey => $column ) {

				$query	= null;

				foreach ( $searchTerms as $skey => $term ) {

					if( $skey  == 0 ) {

						$query = " $column like '%$term%' ";
					}
					else {

						$query .= " OR $column like '%$term%' ";
					}
				}

				if( isset( $query ) ) {

					if( $ckey  == 0 ) {

						$searchQuery = 	"( $query )";
					}
					else {

						$searchQuery .= " OR ( $query )";
					}
				}
			}
		}
		else {

			foreach ( $searchTerms as $key => $value ) {

				if( $key  == 0 ) {

					$searchQuery .= " $columns like '%$value%' ";
				}
				else {

					$searchQuery .= " OR $columns like '%$value%' ";
				}
			}
		}

		return $searchQuery;
	}

	// Maps -------------------------------------------------------

	/**
	 * The method findMap returns an associative array for the defined table and columns. It also apply the provided conditions.
	 * @param string $keyColumn
	 * @param string $valueColumn
	 * @param string $tableName
	 * @param array $conditions
	 */
	public static function findMap( $keyColumn, $valueColumn, $tableName, $config = [] ) {

		$arrayList  = self::findNameValueList( $keyColumn, $valueColumn, $tableName, $config );
		$map		= [];

		foreach ( $arrayList as $item ) {

			$map[ $item['name'] ] = $item['value'];
		}

		return $map;
	}

	/**
	 * The method findObjectMap returns an associative array for the defined table and columns. It also apply the provided conditions.
	 * @param string $keyColumn
	 * @param object $entity
	 * @param string $tableName
	 * @param array $conditions
	 */
	public static function findObjectMap( $keyColumn, $entity, $config = [] ) {

		$query			= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $entity::find();
		$limit			= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : self::PAGE_LIMIT;
		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;
		$filters		= isset( $config[ 'filters' ] ) ? $config[ 'filters' ] : null;
		$map			= [];

		// Filtering -----------

		if( isset( $conditions ) ) {

			$query 	= $query->andWhere( $conditions );
		}

		if( isset( $filters ) ) {

			foreach ( $filters as $filter ) {

				$query 	= $query->andFilterWhere( $filter );
			}
		}

		$objects	= $query->all();

		foreach ( $objects as $object ) {

			$map[ $object->$keyColumn ] = $object;
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
	public static function findList( $column, $tableName, $config = [] ) {

		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;
		$filters		= isset( $config[ 'filters' ] ) ? $config[ 'filters' ] : null;
		$order			= isset( $config[ 'order' ] ) ? $config[ 'order' ] : null;

		$query			= new Query();

		// Build Query
		if( isset( $conditions ) ) {

			$query->select( $column )
			 	  ->from( $tableName )->where( $conditions );
		}
		else {

			$query->select( $column )
				  ->from( $tableName );
		}

		// Multiple Columns
		if( isset( $filters ) ) {

			foreach ( $filters as $filter ) {

				$query 	= $query->andFilterWhere( $filter );
			}
		}

		if( isset( $order ) ) {

			$query->orderBy( $order );
		}

		// Get column
		$query->column();

		// Create command
		$command 	= $query->createCommand();

		// Execute the command
		$list 		= $command->queryAll();
		$resultList	= [];

		foreach ( $list as $item ) {

			$resultList[] = $item[ $column ];
		}

		return $resultList;
	}

	/**
	 * The method findNameValueList returns an array of associative arrays having name and value as keys for the defined columns.
	 * @param string $nameColumn
	 * @param string $valueColumn
	 * @param string $tableName
	 * @param array $conditions
	 */
	public static function findNameValueList( $nameColumn, $valueColumn, $tableName, $config = [] ) {

		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;
		$filters		= isset( $config[ 'filters' ] ) ? $config[ 'filters' ] : null;
		$prepend		= isset( $config[ 'prepend' ] ) ? $config[ 'prepend' ] : [];
		$append			= isset( $config[ 'append' ] ) ? $config[ 'append' ] : [];

		$query 			= new Query();

		// Build Query
		if( isset( $conditions ) ) {

			$query->select( $nameColumn.' as name,'. $valueColumn .' as value' )
			 	  ->from( $tableName )->where( $conditions );
		}
		else {

			$query->select( $nameColumn.' as name,'. $valueColumn .' as value' )
				  ->from( $tableName );
		}

		// Multiple Columns
		if( isset( $filters ) ) {

			foreach ( $filters as $filter ) {

				$query 	= $query->andFilterWhere( $filter );
			}
		}

		// Create command
		$command = $query->createCommand();

		// Execute the command
		$arrayList = $command->queryAll();

		// Prepend given list
		if( count( $prepend ) > 0 ) {

			$arrayList = ArrayHelper::merge( $prepend, $arrayList );
		}

		// Append given list
		if( count( $append ) > 0 ) {

			$arrayList = ArrayHelper::merge( $arrayList, $append );
		}

		return $arrayList;
	}

	/**
	 * The method findIdNameList returns an array of associative arrays having id and name as keys for the defined columns.
	 * @param string $idColumn
	 * @param string $nameColumn
	 * @param string $tableName
	 * @param array $conditions
	 */
	public static function findIdNameList( $idColumn, $nameColumn, $tableName, $config = [] ) {

		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;
		$filters		= isset( $config[ 'filters' ] ) ? $config[ 'filters' ] : null;
		$prepend		= isset( $config[ 'prepend' ] ) ? $config[ 'prepend' ] : [];
		$append			= isset( $config[ 'append' ] ) ? $config[ 'append' ] : [];

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

		// Multiple Columns
		if( isset( $filters ) ) {

			foreach ( $filters as $filter ) {

				$query 	= $query->andFilterWhere( $filter );
			}
		}

		// Create command
		$command = $query->createCommand();

		// Execute the command
		$arrayList = $command->queryAll();

		// Prepend given list
		if( count( $prepend ) > 0 ) {

			$arrayList = ArrayHelper::merge( $prepend, $arrayList );
		}

		// Append given list
		if( count( $append ) > 0 ) {

			$arrayList = ArrayHelper::merge( $arrayList, $append );
		}

		return $arrayList;
	}
}

?>