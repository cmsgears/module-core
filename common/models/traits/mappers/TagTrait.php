<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\mappers;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Tag;
use cmsgears\core\common\models\mappers\ModelTag;

/**
 * TagTrait can be used to add tagging feature to relevant models.
 */
trait TagTrait {

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

	// TagTrait ------------------------------

	/**
	 * @inheritdoc
	 */
	public function getModelTags() {

		$modelTagTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_TAG );

		return $this->hasMany( ModelTag::class, [ 'parentId' => 'id' ] )
			->where( "$modelTagTable.parentType='$this->modelType'" );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveModelTags() {

		$modelTagTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_TAG );

		return $this->hasMany( ModelTag::class, [ 'parentId' => 'id' ] )
			->where( "$modelTagTable.parentType='$this->modelType' AND $modelTagTable.active=1" );
	}

	/**
	 * @inheritdoc
	 */
	public function getModelTagsByType( $type, $active = true ) {

		$modelTagTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_TAG );

		return $this->hasOne( ModelTag::class, [ 'parentId' => 'id' ] )
			->where( "$modelTagTable.parentType=:ptype AND $modelTagTable.type=:type AND $modelTagTable.active=:active", [ ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getTags() {

		$modelTagTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_TAG );

		return $this->hasMany( Tag::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelTagTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$modelTagTable ) {

					$query->onCondition( [ "$modelTagTable.parentType" => $this->modelType ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveTags() {

		$modelTagTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_TAG );

		return $this->hasMany( Tag::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelTagTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$modelTagTable ) {

					$query->onCondition( [ "$modelTagTable.parentType" => $this->modelType, "$modelTagTable.active" => true ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getTagsByType( $type, $active = true ) {

		$modelTagTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_TAG );

		return $this->hasMany( Tag::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelTagTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$type, &$active, &$modelTagTable ) {

					$query->onCondition( [ "$modelTagTable.parentType" => $this->modelType, "$modelTagTable.type" => $type, "$modelTagTable.active" => $active ] );
				}
			)->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getTagIdList( $active = true ) {

		$tags		= $active ? $this->activeTags : $this->tags;
		$tagsList	= [];

		foreach( $tags as $tag ) {

			array_push( $tagsList, $tag->id );
		}

		return $tagsList;
	}

	/**
	 * @inheritdoc
	 */
	public function getTagNameList( $active = true ) {

		$tags		= $active ? $this->activeTags : $this->tags;
		$tagsList	= [];

		foreach( $tags as $tag ) {

			array_push( $tagsList, $tag->name );
		}

		return $tagsList;
	}

	/**
	 * @inheritdoc
	 */
	public function getTagIdNameList( $active = true ) {

		$tags		= $active ? $this->activeTags : $this->tags;
		$tagsList	= [];

		foreach( $tags as $tag ) {

			$tagsList[] = [ 'id' => $tag->id, 'name' => $tag->name ];
		}

		return $tagsList;
	}

	/**
	 * @inheritdoc
	 */
	public function getTagIdNameMap( $active = true ) {

		$tags		= $active ? $this->activeTags : $this->tags;
		$tagsMap	= [];

		foreach ( $tags as $tag ) {

			$tagsMap[ $tag->id ] = $tag->name;
		}

		return $tagsMap;
	}

	/**
	 * @inheritdoc
	 */
	public function getTagSlugNameMap( $active = true ) {

		$tags		= $active ? $this->activeTags : $this->tags;
		$tagsMap	= [];

		foreach ( $tags as $tag ) {

			$tagsMap[ $tag->slug ] = $tag->name;
		}

		return $tagsMap;
	}

	/**
	 * @inheritdoc
	 */
	public function getTagCsv( $limit = 0, $active = true ) {

		$tags		= $active ? $this->activeTags : $this->tags;
		$tagsCsv	= [];

		foreach ( $tags as $tag ) {

			$tagsCsv[] = $tag->name;
		}

		if( $limit > 0 ) {

			$tagsCsv = array_splice( $tagsCsv, $limit );
		}

		return implode( ", ", $tagsCsv );
	}

	/**
	 * @inheritdoc
	 */
	public function getTagLinks( $baseUrl, $wrapper = 'li', $limit = 0, $active = true ) {

		$tags		= $active ? $this->activeTags : $this->tags;
		$tagLinks	= null;

		foreach ( $tags as $tag ) {

			if( isset( $wrapper ) ) {

				$tagLinks	.= "<$wrapper><a href='$baseUrl/$tag->slug'>$tag->name</a></$wrapper>";
			}
			else {

				$tagLinks	.= " <a href='$baseUrl/$tag->slug'>$tag->name</a>";
			}
		}

		if( $limit > 0 ) {

			$tagLinks = array_splice( $tagLinks, $limit );
		}

		return $tagLinks;
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// TagTrait ------------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
