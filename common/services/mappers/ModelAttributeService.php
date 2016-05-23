<?php
namespace cmsgears\core\common\services\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\mappers\ModelAttribute;

/**
 * The class ModelAttributeService is base class to perform database activities for ModelAttribute Entity.
 */
class ModelAttributeService extends \cmsgears\core\common\services\base\Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function findById( $id ) {

		return ModelAttribute::findById( $id );
	}

	public static function findByType( $parentId, $parentType, $type ) {

		return ModelAttribute::findByType( $parentId, $parentType, $type );
	}

	public static function findByTypeName( $parentId, $parentType, $type, $name ) {

		return ModelAttribute::findByTypeName( $parentId, $parentType, $type, $name );
	}

	public static function findOrGetByTypeName( $parentId, $parentType, $type, $name, $valueType = 'text' ) {

		$attribute	= self::findByTypeName( $parentId, $parentType, $type, $name );

		if( !isset( $attribute ) ) {

			$attribute				= new ModelAttribute();
			$attribute->parentId	= $parentId;
			$attribute->parentType	= $parentType;
			$attribute->name		= $name;
			$attribute->type		= $type;
			$attribute->valueType	= $valueType;
		}

		return $attribute;
	}

	// Maps --

	public static function findMapByType( $parentId, $parentType, $type ) {

		$attributes 	= ModelAttribute::findByType( $parentId, $parentType, $type );
		$attributeMap	= [];

		foreach ( $attributes as $attribute ) {

			$attributeMap[ $attribute->name ] = $attribute;
		}

		return $attributeMap;
	}

	// Create ----------------

 	public static function create( $model, $config = [] ) {

		if( !isset( $model->label ) || strlen( $model->label ) <= 0 ) {

			$model->label = $model->name;
		}

		if( !isset( $model->valueType ) ) {

			$model->valueType = ModelAttribute::VALUE_TYPE_TEXT;
		}

		$model->save();

		return $model;
 	}

	// Update -----------

	public static function update( $model, $config = [] ) {

        $existingModel  = self::findByTypeName( $model->parentId, $model->parentType, $model->type, $model->name );

		// Create if it does not exist
		if( !isset( $existingModel ) ) {

			return self::create( $model );
		}

		if( isset( $model->valueType ) ) {

			$existingModel->copyForUpdateFrom( $model, [ 'valueType', 'value' ] );
		}
		else {

			$existingModel->copyForUpdateFrom( $model, [ 'value' ] );
		}

		$existingModel->update();

		return $existingModel;
	}

	public static function updateMultiple( $models, $config = [] ) {

		$parent	= $config[ 'parent' ];

		foreach( $models as $model ) {

			if( $model->parentId == $parent->id ) {

				self::update( $model );
			}
		}
	}

	public static function updateByForm( $form, $config = [] ) {

		$attributes = $form->getArrayToStore();

		foreach ( $attributes as $attribute ) {

			if( !isset( $attribute[ 'valueType' ] ) ) {

				$attribute[ 'valueType' ]	= ModelAttribute::VALUE_TYPE_TEXT;
			}

			$model			= self::findOrGetByTypeName( $config[ 'parentId' ], $config[ 'parentType' ], $config[ 'type' ], $attribute[ 'name' ], $attribute[ 'valueType' ] );

			$model->value	= $attribute[ 'value' ];
			$model->label	= $form->getAttributeLabel( $attribute[ 'name' ] );

			self::update( $model );
		}
 	}

	// Delete -----------

	public static function delete( $model, $config = [] ) {

		$model->delete();
	}
}

?>