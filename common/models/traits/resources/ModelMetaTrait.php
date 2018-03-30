<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\resources;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\ModelMeta;

/**
 * ModelMetaTrait can be used to add meta feature to relevant models. It allows us to
 * make existing tables expandable without adding additional columns.
 */
trait ModelMetaTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// ModelMetaTrait ------------------------

	/**
	 * @inheritdoc
	 */
	public function getModelMetas() {

		$modelMetaTable = ModelMeta::tableName();

		return $this->hasMany( ModelMeta::class, [ 'parentId' => 'id' ] )
			->where( "$modelMetaTable.parentType='$this->modelType'" );
	}

	/**
	 * @inheritdoc
	 */
	public function getModelMetasByType( $type ) {

		$modelMetaTable = ModelMeta::tableName();

		return $this->hasMany( ModelMeta::class, [ 'parentId' => 'id' ] )
			->where( "$modelMetaTable.parentType=:ptype AND $modelMetaTable.type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] )->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getModelMetaByTypeName( $type, $name ) {

		$modelMetaTable = ModelMeta::tableName();

		return $this->hasMany( ModelMeta::class, [ 'parentId' => 'id' ] )
			->where( "$modelMetaTable.parentType=:ptype AND $modelMetaTable.type=:type AND $modelMetaTable.name=:name", [ ':ptype' => $this->modelType, ':type' => $type, ':name' => $name ] )->one();
	}

	/**
	 * @inheritdoc
	 */
	public function getModelMetaNameValueMap() {

		$metas		= $this->modelMetas;
		$metasMap	= [];

		foreach ( $metas as $meta ) {

			$metasMap[ $meta->name ] = $meta->value;
		}

		return $metasMap;
	}

	/**
	 * @inheritdoc
	 */
	public function getModelMetaNameValueMapByType( $type ) {

		$metas		= $this->getModelMetasByType( $type );

		$metasMap	= [];

		foreach ( $metas as $meta ) {

			$metasMap[ $meta->name ] = $meta->value;
		}

		return $metasMap;
	}

	/**
	 * @inheritdoc
	 */
	public function getModelMetaMapByType( $type ) {

		$metas		= $this->getModelMetasByType( $type );

		$metasMap	= [];

		foreach ( $metas as $meta ) {

			$metasMap[ $meta->name ] = $meta;
		}

		return $metasMap;
	}

	/**
	 * @inheritdoc
	 */
	public function updateModelMetas( $metas, $type = CoreGlobal::TYPE_CORE ) {

		foreach( $metas as $meta ) {

			$modelMeta = self::getModelMetaByTypeName( $type, $meta->name );

			if( isset( $modelMeta ) ) {

				$modelMeta->value = $meta->value;

				$modelMeta->update();
			}
			else {

				$modelMeta->save();
			}
		}
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// ModelMetaTrait ------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
