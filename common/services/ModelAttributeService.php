<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\ModelAttribute;

/**
 * The class ModelAttributeService is base class to perform database activities for ModelAttribute Entity.
 */
class ModelAttributeService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function findAttributeByType( $parentId, $parentType, $type ) {

		return ModelAttribute::findByType( $parentId, $parentType, $type );
	}

	public static function findAttributeMapByType( $parentId, $parentType, $type ) {

		$attributes 	= ModelAttribute::findByType( $parentId, $parentType, $type );
		$attributeMap	= [];

		foreach ( $attributes as $attribute ) {

			$attributeMap[ $attribute->name ] = $attribute;
		}

		return $attributeMap;
	}

	// Create -----------

	// Update -----------

	public static function update( $attribute ) {

		$existingAttribute	= ModelAttribute::findByTypeName( $attribute->parentId, $attribute->parentType, $attribute->type, $attribute->name );

		if( isset( $existingAttribute ) ) {

			if( isset( $attribute->valueType ) ) {

				$existingAttribute->copyForUpdateFrom( $attribute, [ 'valueType', 'value' ] );
			}
			else {

				$existingAttribute->copyForUpdateFrom( $attribute, [ 'value' ] );
			}

			$existingAttribute->update();

			return $existingAttribute;
		}
		else {

			$attribute->save();

			return $attribute;
		}
	}

	public static function updateAll( $settings ) {

		foreach ( $settings as $key => $setting ) {

			$setting->update();
		}
	}

	// Delete -----------
}

?>