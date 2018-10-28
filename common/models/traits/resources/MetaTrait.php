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

/**
 * MetaTrait can be used to add meta feature to relevant models having own meta table.
 */
trait MetaTrait {

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

	// MetaTrait -----------------------------

	/**
	 * @inheritdoc
	 */
	public function getMetas() {

		$metaClass = $this->metaClass;
		$metaTable = $metaClass::tableName();

		return $this->hasMany( $metaClass, [ 'modelId' => 'id' ] )
			->orderBy( "$metaTable.id DESC" );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveMetas() {

		$metaClass = $this->metaClass;
		$metaTable = $metaClass::tableName();

		return $this->hasMany( $metaClass, [ 'modelId' => 'id' ] )
			->where( [ "$metaTable.active" => true ] )
			->orderBy( "$metaTable.id DESC" );
	}

	/**
	 * @inheritdoc
	 */
	public function getMetasByType( $type ) {

		$metaClass = $this->metaClass;
		$metaTable = $metaClass::tableName();

		return $this->hasMany( $metaClass, [ 'modelId' => 'id' ] )
			->where( [ "$metaTable.type" => $type ] )
			->orderBy( "$metaTable.id DESC" )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveMetasByType( $type ) {

		$metaClass = $this->metaClass;
		$metaTable = $metaClass::tableName();

		return $this->hasMany( $metaClass, [ 'modelId' => 'id' ] )
			->where( [ "$metaTable.active" => true, "$metaTable.type" => $type ] )
			->orderBy( "$metaTable.id DESC" )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveMetasByTypes( $types ) {

		$metaClass = $this->metaClass;
		$metaTable = $metaClass::tableName();

		return $this->hasMany( $metaClass, [ 'modelId' => 'id' ] )
			->where( [ "$metaTable.active" => true ] )
			->filterWhere( "$metaTable.type", 'in', $types )
			->orderBy( "$metaTable.id DESC" )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getMetaByTypeName( $type, $name ) {

		$metaClass = $this->metaClass;
		$metaTable = $metaClass::tableName();

		return $this->hasMany( $metaClass, [ 'modelId' => 'id' ] )
			->where( [ "$metaTable.type" => $type, "$metaTable.name" => $name ] )
			->one();
	}

	/**
	 * @inheritdoc
	 */
	public function getMetaNameValueMap() {

		$metas = $this->metas;

		$metasMap = [];

		foreach( $metas as $meta ) {

			$metasMap[ $meta->name ] = $meta->value;
		}

		return $metasMap;
	}

	/**
	 * @inheritdoc
	 */
	public function getMetaNameValueMapByType( $type ) {

		$metas = $this->getMetasByType( $type );

		$metasMap = [];

		foreach( $metas as $meta ) {

			$metasMap[ $meta->name ] = $meta->value;
		}

		return $metasMap;
	}

	/**
	 * @inheritdoc
	 */
	public function getMetaMapByType( $type ) {

		$metas = $this->getMetasByType( $type );

		$metasMap = [];

		foreach( $metas as $meta ) {

			$metasMap[ $meta->name ] = $meta;
		}

		return $metasMap;
	}

	/**
	 * @inheritdoc
	 */
	public function updateMetas( $metas, $type = CoreGlobal::TYPE_CORE ) {

		foreach( $metas as $meta ) {

			$modelMeta = self::getMetaByTypeName( $type, $meta->name );

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

	// MetaTrait -----------------------------

	// Read - Query -----------

	/**
	 * Return query to find the model with meta.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with meta.
	 */
	public static function queryWithMetas( $config = [] ) {

		$config[ 'relations' ][] = [ 'metas' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
