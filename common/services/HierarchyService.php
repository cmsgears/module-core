<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;
use yii\db\Query;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Managing hierarchy using Nested Set Model
 */
class HierarchyService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function getLevelList( $rootId, $conditions = [] ) {

		$query	= new Query();

		$query->select( 'node.id, node.name, node.rootId, ( COUNT( parent.id ) - 1 ) AS depth' );
		$query->from( 'cmg_core_category AS node, cmg_core_category AS parent' );
		$query->where( 'node.lValue BETWEEN parent.lValue AND parent.rValue AND node.rootId=parent.rootId' );

		if( isset( $conditions ) && count( $conditions ) > 0 ) {

			$query->andWhere( $conditions );
		}

		$query->groupBy( 'node.id' );
		$query->orderBy( 'node.rootId, node.lValue, depth' );

		// Create command
		$command 	= $query->createCommand();

		// Execute the command
		$list 		= $command->queryAll();

		return $list;
	}
	
	// Create --------------

	public static function createInHierarchy( $table, $model ) {

		// Manage Hierarchy

		// Top Level Model
		if( $model->parentId <= 0 ) {

			unset( $model->parentId );

			$model->lValue	= CoreGlobal::HIERARCHY_VALUE_L;
			$model->rValue	= CoreGlobal::HIERARCHY_VALUE_R;
		}
		// Child Model
		else {

			$parentModel	= static::findById( $model->parentId );

			// Top Level Parent
			if( !$parentModel->hasParent() ) {

				// Add child at end
				$model->lValue			= $parentModel->rValue;
				$model->rValue			= $parentModel->rValue + 1;
				$parentModel->rValue	= $parentModel->rValue + 2;

				$parentModel->rootId	= $parentModel->id;
				$model->rootId			= $parentModel->rootId;
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

			// Update Parent
			$parentModel->update();
		}

		// Create Model
		$model->save();

		return $model;
	}

	// Update -----------

	public static function updateInHierarchy( $table, $model, $modelToUpdate ) {

		// Parent Unset
		if( $model->parentId <= 0 && isset( $modelToUpdate->parentId ) && $modelToUpdate->parentId > 0 ) {
			
		}
		// Parent Changed
		else if( $model->parentId > 0 ) {

			// Update Existing Parent
			if( isset( $modelToUpdate->parentId ) && $modelToUpdate->parentId > 0 && $modelToUpdate->parentId != $model->parentId ) {
				
			}
			// Parent set for First Time
			else {
				
			}
		}
		
		return $modelToUpdate;
	}

	// Delete -----------

	public static function deleteInHierarchy( $table, $modelToDelete ) {

		// Model in hierarchy
		if( isset( $modelToDelete->parentId ) && $modelToDelete->parentId > 0  ) {

		}

		return $modelToDelete;
	}
}

?>