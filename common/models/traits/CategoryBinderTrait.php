<?php
namespace cmsgears\core\common\models\traits;

use cmsgears\core\common\models\entities\ModelCategory;

/**
 * CategoryBinderTrait can be used to bind categories using ModelCategory.
 */
trait CategoryBinderTrait {

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
}

?>