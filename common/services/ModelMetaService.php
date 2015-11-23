<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\ModelMeta;

/**
 * The class ModelAddressService is base class to perform database activities for ModelAddress Entity.
 */
class ModelMetaService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function findMetaByType( $parentId, $parentType, $type ) {

		return ModelMeta::findByType( $parentId, $parentType, $type );
	}

	public static function findMetaMapByType( $parentId, $parentType, $type ) {

		$metas 		= ModelMeta::findByType( $parentId, $parentType, $type );
		$metaMap	= [];

		foreach ( $metas as $meta ) {

			$metaMap[ $meta->name ] = $meta;
		}

		return $metaMap;
	}

	// Create -----------

	// Update -----------

	public static function update( $meta ) {

		$existingMeta		= ModelMeta::findByTypeName( $meta->parentId, $meta->parentType, $meta->type, $meta->name );

		if( isset( $existingMeta ) ) {

			$existingMeta->copyForUpdateFrom( $meta, [ 'value' ] );

			$existingMeta->update();

			return $existingMeta;
		}
		else {

			$meta->save();

			return $meta;
		}
	}

	// Delete -----------

}

?>