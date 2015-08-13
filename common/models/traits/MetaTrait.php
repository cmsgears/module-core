<?php
namespace cmsgears\core\common\models\traits;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\ModelMeta;

/**
 * MetaTrait can be used to add meta feature to relevant models. It allows us to make existing tables extandable without adding additional columns.
 * The model must define the member variable $metaType which is unique for the model.
 */
trait MetaTrait {

	/**
	 * @return array - ModelMeta associated with parent
	 */
	public function getModelMetas() {

		$parentType	= $this->metaType;

    	return $this->hasMany( ModelMeta::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$parentType'" );
	}

	/**
	 * @return array - ModelMeta associated with parent
	 */
	public function getModelMetasByType( $type ) {

		$parentType	= $this->metaType;

    	return $this->hasMany( ModelMeta::className(), [ 'parentId' => 'id' ] )
					->where( "parentType=:ptype AND type=:type", [ ':ptype' => $parentType, ':type' => $type ] )->all();
	}

	/**
	 * @return ModelMeta - associated with parent
	 */
	public function getModelMetaByTypeName( $type, $name ) {

		$parentType	= $this->metaType;

    	return $this->hasMany( ModelMeta::className(), [ 'parentId' => 'id' ] )
					->where( "parentType=:ptype AND type=:type AND name=:name", [ ':ptype' => $parentType, ':type' => $type, ':name' => $name ] )->one();
	}

	/**
	 * @return array - map of meta name and value
	 */
	public function getMetaNameValueMap() {

		$metas 		= $this->modelMetas;
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
}

?>