<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\ModelCategory;

/**
 * The class ModelCategoryService is base class to perform database activities for ModelCategory Entity.
 */
class ModelCategoryService extends Service {

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

	public static function findByParentIdParentType( $parentId, $parentType ) {

		return ModelCategory::findByParentIdParentType( $parentId, $parentType );
	}
	
	public static function findActiveByCategoryIdParentType( $categoryId, $parentType ) {
		
		return ModelCategory::findActiveByCategoryIdParentType( $categoryId, $parentType );
	}

	public static function findActiveByParentIdParentType( $parentId, $parentType ) {

		return ModelCategory::findActiveByParentIdParentType( $parentId, $parentType );
	}

	// Read - Lists ----

	public static function findActiveCategoryIdList( $parentId, $parentType ) {

		$models = ModelCategory::findActiveByParentIdParentType( $parentId, $parentType );
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

	public static function bindCategories( $binder, $type ) {

		$parentId	= $binder->binderId;
		$allData	= $binder->allData;
		$activeData	= $binder->bindedData;

		foreach ( $allData as $id ) {

			$toSave		= ModelCategory::findByCategoryId( $parentId, $type, $id );

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
				$toSave->parentType	= $type;
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