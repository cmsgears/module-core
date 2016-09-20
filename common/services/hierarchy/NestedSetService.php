<?php
namespace cmsgears\core\common\services\hierarchy;

// Yii Imports
use \Yii;
use yii\db\Query;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\interfaces\hierarchy\INestedSetService;

// Reference: http://mikehillyer.com/articles/managing-hierarchical-data-in-mysql/, http://we-rc.com/blog/2015/07/19/nested-set-model-practical-examples-part-i

/**
 * Managing hierarchy using Nested Set Model
 */
abstract class NestedSetService extends HierarchyService implements INestedSetService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// HierarchyService ----------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	public function getChildNodes( $parentId, $config = [] ) {

		return self::findChildNodes( $parentId, $config );
	}

	public function getLeafNodes( $rootId, $config = [] ) {

		return self::findLeafNodes( $rootId, $config );
	}

	public function getParentNodes( $leafId, $config = [] ) {

		return self::findParentNodes( $leafId, $config );
	}

	// Read - Lists ----

	public function getLevelList( $config = [] ) {

		return self::findLevelList( $config );
	}

	public function getSubLevelList( $parentId, $rootId, $config = [] ) {

		return self::findSubLevelList( $parentId, $rootId, $config );
	}

	public function getSubLevelIdList( $parentId, $rootId, $config = [] ) {

		$parent		= isset( $config[ 'parent' ] ) ? $config[ 'parent' ] : true;
		$categories = self::findSubLevelList( $parentId, $rootId, $config );
		$ids		= [];

		foreach ( $categories as $key => $value ) {

			$id	= $value[ 'id' ];

			if( !$parent && $id == $parentId ) {

				continue;
			}

			$ids[] = $id;
		}

		return $ids;
	}

	public function getChildIdListById( $modelId ) {

		$model	= self::findById( $modelId );

		return $this->getChildIdList( $model );
	}

	public function getChildIdList( $model ) {

		return $this->getSubLevelIdList( $model->id, $model->rootId );
	}

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	public function createInHierarchy( $model ) {

		$table		= static::$modelTable;

		// Manage Hierarchy

		// Top Level Model
		if( $model->parentId <= 0 ) {

			unset( $model->parentId );

			$model->lValue	= CoreGlobal::HIERARCHY_VALUE_L;
			$model->rValue	= CoreGlobal::HIERARCHY_VALUE_R;

			// Create Model
			$model->save();

			// Update Root Id
			$model			= $this->getById( $model->id );
			$model->rootId	= $model->id;

			$model->update();
		}
		// Child Model
		else {

			$parentModel	= $this->getById( $model->parentId );

			// Top Level Parent
			if( !$parentModel->hasParent() ) {

				// Add child at end
				$model->lValue			= $parentModel->rValue;
				$model->rValue			= $parentModel->rValue + 1;
				$parentModel->rValue	= $parentModel->rValue + 2;
				$model->rootId			= $parentModel->rootId;

				// Update Parent
				$parentModel->update();
			}
			// Lower Level Parent
			else {

				$children	= $parentModel->children;
				$parentRoot	= $parentModel->rootId;

				// Has Children, add as last child
				if( count( $children ) > 0 ) {

					$incCharger	= $parentModel->rValue - 1;
				}
				// No Children
				else {

					$incCharger	= $parentModel->lValue;
				}

				// Update left and right values
				Yii::$app->db->createCommand( "UPDATE $table set lValue = lValue + 2 WHERE rootId = $parentRoot and lValue > $incCharger" )->execute();
				Yii::$app->db->createCommand( "UPDATE $table set rValue = rValue + 2 WHERE rootId = $parentRoot and rValue > $incCharger" )->execute();

				// Configure child
				$model->lValue	= $parentModel->rValue;
				$model->rValue	= $parentModel->rValue + 1;
				$model->rootId	= $parentRoot;
			}

			// Create Model
			$model->save();
		}

		return $model;
	}

	// Update -------------

	public function updateInHierarchy( $model, $modelToUpdate ) {

		$table		= static::$modelTable;

		$rootId		= $model->rootId;
		$lValue		= $model->lValue;
		$rValue		= $model->rValue;

		// Remove from hierarchy
		$this->deleteAllInHierarchy( $modelToUpdate );

		// Parent Unset
		if( $model->parentId <= 0 && isset( $modelToUpdate->parentId ) && $modelToUpdate->parentId > 0 ) {

			$diff	= $lValue - 1;

			// Promote Children
			Yii::$app->db->createCommand( "UPDATE $table set lValue = lValue - $diff, rValue = rValue - $diff WHERE rootId = $modelToUpdate->id" )->execute();

			// Update Parent Id
			Yii::$app->db->createCommand( "UPDATE $table set parentId = NULL WHERE id = $modelToUpdate->id" )->execute();
		}
		// Parent Changed
		else if( $model->parentId > 0 ) {

			// Find Parent
			$parent			= $this->getById( $model->parentId );

			$diff			= $rValue - $lValue + 1;
			$prValue		= $parent->rValue;
			$rootId			= $parent->rootId;
			$cdiff			= $prValue - $lValue;

			$parent->rValue	= $parent->rValue + $diff;

			// Top Level Parent
			if( !$parent->hasParent() ) {

				// Promote Children
				Yii::$app->db->createCommand( "UPDATE $table set lValue = lValue + $cdiff, rValue = rValue + $cdiff, rootId = $parent->id WHERE rootId = $modelToUpdate->id" )->execute();

				// Update Parent Id
				Yii::$app->db->createCommand( "UPDATE $table set lValue=$prValue, parentId = $parent->id WHERE id = $modelToUpdate->id" )->execute();
			}
			else {

				// Update left and right values
				Yii::$app->db->createCommand( "UPDATE $table set lValue = lValue + $diff WHERE rootId = $rootId and lValue > $prValue" )->execute();
				Yii::$app->db->createCommand( "UPDATE $table set rValue = rValue + $diff WHERE rootId = $rootId and rValue > $prValue" )->execute();

				// Promote Children
				Yii::$app->db->createCommand( "UPDATE $table set lValue = lValue + $cdiff, rValue = rValue + $cdiff, rootId = $rootId WHERE rootId = $modelToUpdate->id" )->execute();

				// Update Parent Id
				Yii::$app->db->createCommand( "UPDATE $table set parentId = $parent->id WHERE id = $modelToUpdate->id" )->execute();
			}

			// Update Parent
			$parent->update();
		}

		return $modelToUpdate;
	}

	// Delete -------------

	public function deleteInHierarchy( $model ) {

		$table		= static::$modelTable;

		$parent		= $model->parent;
		$children	= $model->children;
		$rootId		= $model->rootId;
		$lValue		= $model->lValue;
		$rValue		= $model->rValue;

		// Has children - not a leaf node
		if( count( $children ) > 0 ) {

			// Not a top level node
			if( isset( $parent ) ) {

				$children	= $this->getSubLevelList( $model->id, $model->rootId, [ 'having' => 'depth=1' ] );
				$ids		= [];

				foreach ( $children as $child ) {

					$ids[]	= $child[ 'id' ];
				}

				$ids	= join( ',', $ids );

				// Update Parent Id
				Yii::$app->db->createCommand( "UPDATE $table set parentId = $parent->id WHERE id IN( $ids )" )->execute();

				// Promote Children
				Yii::$app->db->createCommand( "UPDATE $table set lValue = lValue - 1, rValue = rValue - 1 WHERE rootId = $rootId and lValue BETWEEN $lValue and $rValue" )->execute();

				// Update lr values
				Yii::$app->db->createCommand( "UPDATE $table set lValue = lValue - 2 WHERE rootId = $rootId and lValue > $rValue" )->execute();
				Yii::$app->db->createCommand( "UPDATE $table set rValue = rValue - 2 WHERE rootId = $rootId and rValue > $rValue" )->execute();
			}
			// Top level node
			else {

				$children	= $this->getSubLevelList( $model->id, $model->rootId, [ 'having' => 'depth=1' ] );

				// first child and it's children ---------

				$id			= $children[ 0 ][ 'id' ];
				$rightVal	= $children[ 0 ][ 'rValue' ];

				// Promote Children
				Yii::$app->db->createCommand( "UPDATE $table set lValue = lValue - 1, rValue = rValue - 1, rootId = $id WHERE rootId = $rootId AND lValue < $rightVal" )->execute();

				// Update Parent Id
				Yii::$app->db->createCommand( "UPDATE $table set parentId = NULL WHERE id=$id" )->execute();

				// remaining children --------------------

				$count	= count( $children );

				if( $count > 1 ) {

					for( $i = $count - 1; $i > 0; $i-- ) {

						$id			= $children[ $i ][ 'id' ];
						$rightVal	= $children[ $i - 1 ][ 'rValue' ];

						// Promote Children
						Yii::$app->db->createCommand( "UPDATE $table set lValue = lValue - $rightVal, rValue = rValue - $rightVal, rootId = $id WHERE rootId = $rootId AND lValue > $rightVal" )->execute();

						// Update Parent Id
						Yii::$app->db->createCommand( "UPDATE $table set parentId = NULL WHERE id=$id" )->execute();
					}
				}
			}
		}
		// No children - leaf node
		else {

			// Not a top level node
			if( isset( $parent ) ) {

				// Update lr values
				Yii::$app->db->createCommand( "UPDATE $table set lValue = lValue - 2 WHERE rootId = $rootId and lValue > $rValue" )->execute();
				Yii::$app->db->createCommand( "UPDATE $table set rValue = rValue - 2 WHERE rootId = $rootId and rValue > $rValue" )->execute();
			}

			// Do nothing for top level node and simply delete it
		}

		// Unlink root id
		$model->rootId	= null;

		$model->update();

		return $model;
	}

	public function deleteAllInHierarchy( $model ) {

		$table		= static::$modelTable;

		$parent		= $model->parent;
		$children	= $model->children;
		$rootId		= $model->rootId;
		$lValue		= $model->lValue;
		$rValue		= $model->rValue;

		// Has children - not a leaf node
		if( count( $children ) > 0 ) {

			// Not a top level node
			if( isset( $parent ) ) {

				$children	= $this->getSubLevelList( $model->id, $model->rootId );
				$count		= count( $children ) * 2;

				// Update lr values
				Yii::$app->db->createCommand( "UPDATE $table set rootId = $model->id WHERE rootId = $rootId and lValue >= $lValue and rValue <= $rValue" )->execute();
				Yii::$app->db->createCommand( "UPDATE $table set lValue = lValue - $count WHERE rootId = $rootId and lValue > $rValue" )->execute();
				Yii::$app->db->createCommand( "UPDATE $table set rValue = rValue - $count WHERE rootId = $rootId and rValue > $rValue" )->execute();
			}

			// Do nothing for top level node and simply delete it
		}
		// No children - leaf node
		else {

			// Not a top level node
			if( isset( $parent ) ) {

				// Update lr values
				Yii::$app->db->createCommand( "UPDATE $table set rootId = $model->id WHERE id=$model->id" )->execute();
				Yii::$app->db->createCommand( "UPDATE $table set lValue = lValue - 2 WHERE rootId = $rootId and lValue > $rValue" )->execute();
				Yii::$app->db->createCommand( "UPDATE $table set rValue = rValue - 2 WHERE rootId = $rootId and rValue > $rValue" )->execute();
			}

			// Do nothing for top level node and simply delete it
		}

		return $model;
	}

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// HierarchyService ----------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	/** Return all the possible trees in hierarchical fashion with depth level and given conditions.
	 * Example Query for category without conditions:
		select node.id, node.name, node.rootId, ( COUNT( parent.id ) - 1 ) AS depth
		from cmg_core_category AS node, cmg_core_category AS parent
		where node.lValue BETWEEN parent.lValue AND parent.rValue AND node.rootId=parent.rootId
		group by node.id
		order by node.rootId, node.lValue, depth;
	 * Example Query for category with condition having specific type:
		select node.id, node.name, node.rootId, ( COUNT( parent.id ) - 1 ) AS depth
		from cmg_core_category AS node, cmg_core_category AS parent
		where node.lValue BETWEEN parent.lValue AND parent.rValue AND node.rootId=parent.rootId AND node.type = '<type value>'
		group by node.id
		order by node.rootId, node.lValue, depth;
	 */
	public static function findLevelList( $config = [] ) {

		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : [];

		$modelTable		= static::$modelTable;
		$query			= new Query();

		$query->select( 'node.id, node.name, node.rootId, node.lValue, node.rValue, ( COUNT( parent.id ) - 1 ) AS depth' );
		$query->from( "$modelTable AS node, $modelTable AS parent" );
		$query->where( 'node.lValue BETWEEN parent.lValue AND parent.rValue AND node.rootId=parent.rootId' );

		if( count( $conditions ) > 0 ) {

			$query->andWhere( $conditions );
		}

		$query->groupBy( 'node.id' );
		$query->orderBy( 'node.rootId, node.lValue, depth' );

		// Create command
		$command	= $query->createCommand();

		// Execute the command
		$list		= $command->queryAll();

		return $list;
	}

	/** Return all the possible sub trees in hierarchical fashion with depth level for parent id, root id and given conditions.
	 * We can also enhance the below mentioned query by applying filter on depth value by providing $having. Ex: 'depth <= 1'
	 * Example Query for category without conditions:
		select node.id, node.name, node.rootId, (COUNT(parent.id) - (sub_tree.depth + 1)) AS depth
		from cmg_core_category AS node, cmg_core_category AS parent,
			cmg_core_category AS sub_parent,
					(
							SELECT node.id, node.name, (COUNT(parent.id) - 1) AS depth
							FROM cmg_core_category AS node,
							cmg_core_category AS parent
							WHERE node.lValue BETWEEN parent.lValue AND parent.rValue AND node.rootId=parent.rootId AND node.rootId=<root id>
							AND node.id = <parent id>
							GROUP BY node.id
							ORDER BY node.lValue
					)AS sub_tree
		where node.lValue BETWEEN parent.lValue AND parent.rValue AND node.rootId=parent.rootId AND node.rootId=<root id>
			AND node.lValue BETWEEN sub_parent.lValue AND sub_parent.rValue
			AND sub_parent.id = sub_tree.id
		group by node.id
		order by node.rootId, node.lValue, depth;
	 */
	public static function findSubLevelList( $parentId, $rootId, $config = [] ) {

		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;
		$having			= isset( $config[ 'having' ] ) ? $config[ 'having' ] : null;

		$modelTable		= static::$modelTable;
		$query			= new Query();

		$subSelect	= "(
							SELECT `node`.`id`, `node`.`name`, (COUNT(`parent`.`id`) - 1) AS `depth`
							FROM `$modelTable` AS `node`,
							`$modelTable` AS `parent`
							WHERE `node`.`lValue` BETWEEN `parent`.`lValue` AND `parent`.`rValue` AND `node`.`rootId`=`parent`.`rootId` AND `node`.`rootId`=$rootId
							AND `node`.`id` = $parentId
							GROUP BY `node`.`id`
							ORDER BY `node`.`lValue`
						)AS sub_tree";

		$query->select( 'node.id, node.name, node.rootId, node.lValue, node.rValue, (COUNT(parent.id) - (sub_tree.depth + 1)) AS depth' );
		$query->from( "$modelTable AS node, $modelTable AS parent, $modelTable AS sub_parent, $subSelect" );
		$query->where( "node.lValue BETWEEN parent.lValue AND parent.rValue AND node.rootId=parent.rootId AND node.rootId=$rootId" );
		$query->andWhere( "node.lValue BETWEEN sub_parent.lValue AND sub_parent.rValue" );
		$query->andWhere( "sub_parent.id = sub_tree.id" );

		if( isset( $conditions ) && count( $conditions ) > 0 ) {

			$query->andWhere( $conditions );
		}

		$query->groupBy( 'node.id' );

		$query->orderBy( 'node.rootId, node.lValue, depth' );

		if( isset( $having ) ) {

			$query->having( $having );
		}

		// Create command
		$command	= $query->createCommand();

		// Execute the command
		$list		= $command->queryAll();

		return $list;
	}

	/** Return all the child nodes for given parent id
	 * Example query for category without conditions:
		SELECT node.id, node.name
		FROM cmg_core_category AS node, cmg_core_category AS parent
		WHERE node.lValue BETWEEN parent.lValue AND parent.rValue AND node.rootId=parent.rootId AND parent.id=<parent id> order by node.lValue;
	 */
	public static function findChildNodes( $parentId, $config = [] ) {

		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;

		$modelTable		= static::$modelTable;
		$query			= new Query();

		$query->select( 'node.id, node.name' );
		$query->from( "$modelTable AS node, $modelTable AS parent" );
		$query->where( "node.lValue BETWEEN parent.lValue AND parent.rValue AND node.rootId=parent.rootId AND parent.id=$parentId" );

		if( isset( $conditions ) && count( $conditions ) > 0 ) {

			$query->andWhere( $conditions );
		}

		$query->orderBy( 'node.lValue' );

		// Create command
		$command	= $query->createCommand();

		// Execute the command
		$list		= $command->queryAll();

		return $list;
	}

	/** Return all the leaf nodes for given root id
	 * Example query for category without conditions:
		SELECT id, name
		FROM cmg_core_category
		WHERE rValue = lValue + 1 AND rootId=<root id>;
	 */
	public static function findLeafNodes( $rootId, $config = [] ) {

		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;

		$modelTable		= static::$modelTable;
		$query			= new Query();

		$query->select( 'id, name' );
		$query->from( $modelTable );
		$query->where( "rValue = lValue + 1 AND rootId=$rootId" );

		if( isset( $conditions ) && count( $conditions ) > 0 ) {

			$query->andWhere( $conditions );
		}

		// Create command
		$command	= $query->createCommand();

		// Execute the command
		$list		= $command->queryAll();

		return $list;
	}

	/** Return all the parents for given leaf id
	 * Example query for category without conditions:
		SELECT parent.id, parent.name
		FROM cmg_core_category AS node, cmg_core_category AS parent
		where node.lValue BETWEEN parent.lValue AND parent.rValue AND parent.rootId = node.rootId AND node.id = <leaf id>
		ORDER BY node.lValue;
	 */
	public static function findParentNodes( $leafId, $config = [] ) {

		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;

		$modelTable		= static::$modelTable;
		$query			= new Query();

		$query->select( 'parent.id, parent.name' );
		$query->from( "$modelTable AS node, $modelTable AS parent" );
		$query->where( "where node.lValue BETWEEN parent.lValue AND parent.rValue AND parent.rootId = node.rootId AND node.id=$leafId" );

		if( isset( $conditions ) && count( $conditions ) > 0 ) {

			$query->andWhere( $conditions );
		}

		$query->orderBy( 'node.lValue' );

		// Create command
		$command	= $query->createCommand();

		// Execute the command
		$list		= $command->queryAll();

		return $list;
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
