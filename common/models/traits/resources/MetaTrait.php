<?php
namespace cmsgears\core\common\models\traits\resources;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\ModelMeta;

/**
 * MetaTrait can be used to add meta feature to relevant models. It allows us to make existing tables extandable without adding additional columns.
 */
trait MetaTrait {

	/**
	 * @return array - ModelMeta associated with parent
	 */
	public function getModelMetas() {

		return $this->hasMany( ModelMeta::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$this->mParentType'" );
	}

	/**
	 * @return array - ModelMeta associated with parent
	 */
	public function getModelMetasByType( $type ) {

		return $this->hasMany( ModelMeta::className(), [ 'parentId' => 'id' ] )
					->where( "parentType=:ptype AND type=:type", [ ':ptype' => $this->mParentType, ':type' => $type ] )->all();
	}

	/**
	 * @return ModelMeta - associated with parent
	 */
	public function getModelMetaByTypeName( $type, $name ) {

		return $this->hasMany( ModelMeta::className(), [ 'parentId' => 'id' ] )
					->where( "parentType=:ptype AND type=:type AND name=:name", [ ':ptype' => $this->mParentType, ':type' => $type, ':name' => $name ] )->one();
	}

	/**
	 * @return array - map of meta name and value
	 */
	public function getMetaNameValueMap() {

		$metas		= $this->modelMetas;
		$metasMap	= array();

		foreach ( $metas as $meta ) {

			$metasMap[ $meta->name ] = $meta->value;
		}

		return $metasMap;
	}

	/**
	 * @return array - map of meta name and value by type
	 */
	public function getMetaNameValueMapByType( $type ) {

		$metas		= $this->getModelMetasByType( $type );

		$metasMap	= array();

		foreach ( $metas as $meta ) {

			$metasMap[ $meta->name ] = $meta->value;
		}

		return $metasMap;
	}

	/**
	 * @return array - map of meta name and meta by type
	 */
	public function getMetaMapByType( $type ) {

		$metas		= $this->getModelMetasByType( $type );

		$metasMap	= array();

		foreach ( $metas as $meta ) {

			$metasMap[ $meta->name ] = $meta;
		}

		return $metasMap;
	}

	public function updateMetas( $metaArray, $type = CoreGlobal::TYPE_CORE ) {

		foreach( $metaArray as $metaElement ) {

			$meta = self::getModelMetaByTypeName( $type, $metaElement->name );

			if( isset( $meta ) ) {

				$meta->value = $metaElement->value;

				$meta->update();
			}
			else {

				$metaElement->save();
			}
		}
	}
}
