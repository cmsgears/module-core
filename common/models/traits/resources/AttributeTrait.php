<?php
namespace cmsgears\core\common\models\traits\resources;

// Yii Import
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\ModelAttribute;

/**
 * AttributeTrait can be used to add attribute feature to relevant models. It allows us to make existing tables extandable without adding additional columns.
 * The model must define the member variable $attributeType which is unique for the model.
 */
trait AttributeTrait {

	/**
	 * @return array - ModelAttribute associated with parent
	 */
	public function getModelAttributes() {

    	return $this->hasMany( ModelAttribute::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$this->mParentType'" );
	}

	/**
	 * @return array - ModelAttribute associated with parent
	 */
	public function getModelAttributesByType( $type ) {

    	return $this->hasMany( ModelAttribute::className(), [ 'parentId' => 'id' ] )
					->where( "parentType=:ptype AND type=:type", [ ':ptype' => $this->mParentType, ':type' => $type ] )->all();
	}

	/**
	 * @return ModelAttribute - associated with parent
	 */
	public function getModelAttributeByTypeName( $type, $name ) {

    	return $this->hasMany( ModelAttribute::className(), [ 'parentId' => 'id' ] )
					->where( "parentType=:ptype AND type=:type AND name=:name", [ ':ptype' => $this->mParentType, ':type' => $type, ':name' => $name ] )->one();
	}

	/**
	 * @return array - map of attribute name and value
	 */
	public function getAttributeNameValueMap() {

		$attributes 		= $this->ModelAttributes;
		$attributesMap	= array();

		foreach ( $attributes as $attribute ) {

			$attributesMap[ $attribute->name ] = $attribute->value;
		}

		return $attributesMap;
	}

	/**
	 * @return array - map of attribute name and value by type
	 */
	public function getAttributeNameValueMapByType( $type ) {

		$attributes		= $this->getModelAttributesByType( $type );

		$attributesMap	= array();

		foreach ( $attributes as $attribute ) {

			$attributesMap[ $attribute->name ] = $attribute->value;
		}

		return $attributesMap;
	}

	/**
	 * @return array - map of attribute name and attribute by type
	 */
	public function getAttributeMapByType( $type ) {

		$attributes		= $this->getModelAttributesByType( $type );

		$attributesMap	= array();

		foreach ( $attributes as $attribute ) {

			$attributesMap[ $attribute->name ] = $attribute;
		}

		return $attributesMap;
	}

	public function updateAttributes( $attributeArray, $type = CoreGlobal::TYPE_CORE ) {

		foreach( $attributeArray as $attributeElement ) {

			$attribute = self::getModelAttributeByTypeName( $type, $attributeElement->name );

			if( isset( $attribute ) ) {

				$attribute->value = $attributeElement->value;

				$attribute->update();
			}
			else {

				$attributeElement->save();
			}
		}
	}
}
