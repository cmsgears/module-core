<?php
namespace cmsgears\core\common\services\mappers;

// Yii Imports
use \Yii;
use yii\db\Query;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\mappers\ModelCategory;

/**
 * The class ModelCategoryService is base class to perform database activities for ModelCategory Entity.
 */
class ModelCategoryService extends \cmsgears\core\common\services\base\Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	// Read - Models ---

	public static function findByParentType( $parentType ) {

		return ModelCategory::findByParentType( $parentType );
	}

	public static function findByParentId( $parentId ) {

		return ModelCategory::findByParentId( $parentId );
	}

	public static function findActiveByParentId( $parentId ) {

		return ModelCategory::findActiveByParentId( $parentId );
	}

	public static function findByCategoryId( $parentId, $parentType, $categoryId ) {

		return ModelCategory::findByCategoryId( $parentId, $parentType, $categoryId );
	}

	public static function findByParent( $parentId, $parentType ) {

		return ModelCategory::findByParent( $parentId, $parentType );
	}

	public static function findActiveByCategoryIdParentType( $categoryId, $parentType ) {

		return ModelCategory::findActiveByCategoryIdParentType( $categoryId, $parentType );
	}

	public static function findActiveByParent( $parentId, $parentType ) {

		return ModelCategory::findActiveByParent( $parentId, $parentType );
	}

	public static function getModelCounts( $parentType, $categoryType ) {

		$categoryTable	= CoreTables::TABLE_CATEGORY;
		$mcategoryTable	= CoreTables::TABLE_MODEL_CATEGORY;
		$query			= new Query();

    	$query->select( [ 'slug', "count($categoryTable.id) as total" ] )
				->from( $categoryTable )
				->leftJoin( $mcategoryTable, "$mcategoryTable.categoryId=$categoryTable.id" )
				->where( "$mcategoryTable.parentType='$parentType' AND $categoryTable.type='$categoryType'" )
				->groupBy( "$categoryTable.id" );

		$counts 	= $query->all();
		$returnArr	= [];
		$counter	= 0;

		foreach ( $counts as $count ) {

			$returnArr[ $count[ 'slug' ] ] = $count[ 'total' ];

			$counter	= $counter + $count[ 'total' ];
		}

		$returnArr[ 'all' ] = $counter;

		return $returnArr;
	}

	// Read - Lists ----

	public static function findActiveCategoryIdList( $categoryId, $parentType ) {

		$models = ModelCategory::findActiveByCategoryIdParentType( $categoryId, $parentType );
		$ids	= [];

		foreach ( $models as $model ) {

			$category 	= $model->category;

			$ids[]		= $category->id;
		}

		return $ids;
	}

	// Create -----------

	public static function create( $categoryId, $parentId, $parentType ) {

		$modelCategory				= new ModelCategory();

		$modelCategory->categoryId	= $categoryId;
		$modelCategory->parentId	= $parentId;
		$modelCategory->parentType	= $parentType;

		$modelCategory->save();
	}

	// Update -----------

	public static function update( $parentId, $parentType, $categoryId ) {

		$existingModelCategory	= self::findByCategoryId( $parentId, $parentType, $categoryId );

		if( isset( $existingModelCategory ) ) {

			self::updateActive( $existingModelCategory, 1 );
		}
		else {

			$modelCategory				= new ModelCategory();
			$modelCategory->categoryId	= $categoryId;
			$modelCategory->parentId	= $parentId;
			$modelCategory->parentType	= $parentType;

			self::create( $modelCategory );
		}
	}

	public static function updateActive( $model, $active ) {

		$model->active	= $active;

		$model->update();
	}

	public static function bindCategories( $binder, $parentType ) {

		$parentId	= $binder->binderId;
		$allData	= $binder->allData;
		$activeData	= $binder->bindedData;

		foreach ( $allData as $id ) {

			$toSave		= ModelCategory::findByCategoryId( $parentId, $parentType, $id );

			// Existing mapping
			if( isset( $toSave ) ) {

				if( in_array( $id, $activeData ) ) {

					$toSave->active	= true;
				}
				else {

					$toSave->active	= false;
				}

				$toSave->update();
			}
			// Save only required data
			else if( in_array( $id, $activeData ) ) {

				$toSave		= new ModelCategory();

				$toSave->parentId	= $parentId;
				$toSave->parentType	= $parentType;
				$toSave->categoryId	= $id;
				$toSave->active		= true;

				$toSave->save();
			}
		}

		return true;
	}

	// Delete -----------

	public static function deleteByCategoryId( $categoryId ) {

		return ModelCategory::deleteByCategoryId( $categoryId );
	}
}

?>