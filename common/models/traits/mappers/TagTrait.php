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

		$modelTagTable = ModelTag::tableName();

		return $this->hasMany( ModelTag::class, [ 'parentId' => 'id' ] )
			->where( "$modelTagTable.parentType='$this->modelType'" )
			->orderBy( [ "$modelTagTable.order" => SORT_DESC, "$modelTagTable.id" => SORT_DESC ] );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveModelTags() {

		$modelTagTable = ModelTag::tableName();

		return $this->hasMany( ModelTag::class, [ 'parentId' => 'id' ] )
			->where( "$modelTagTable.parentType='$this->modelType' AND $modelTagTable.active=1" )
			->orderBy( [ "$modelTagTable.order" => SORT_DESC, "$modelTagTable.id" => SORT_DESC ] );
	}

	/**
	 * @inheritdoc
	 */
	public function getModelTagsByType( $type, $active = true ) {

		$modelTagTable = ModelTag::tableName();

		if( $active ) {

			return $this->hasMany( ModelTag::class, [ 'parentId' => 'id' ] )
				->where( "$modelTagTable.parentType=:ptype AND $modelTagTable.type=:type AND $modelTagTable.active=:active", [ ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )
				->orderBy( [ "$modelTagTable.order" => SORT_DESC, "$modelTagTable.id" => SORT_DESC ] );
		}

		return $this->hasMany( ModelTag::class, [ 'parentId' => 'id' ] )
			->where( "$modelTagTable.parentType=:ptype AND $modelTagTable.type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] )
			->orderBy( [ "$modelTagTable.order" => SORT_DESC, "$modelTagTable.id" => SORT_DESC ] );
	}

	/**
	 * @inheritdoc
	 */
	public function getTags() {

		$tagTable		= Tag::tableName();
		$modelTagTable	= ModelTag::tableName();

		return Tag::find()
			->leftJoin( $modelTagTable, "$modelTagTable.modelId=$tagTable.id" )
			->where( "$modelTagTable.parentId=:pid AND $modelTagTable.parentType=:ptype", [ ':pid' => $this->id, ':ptype' => $this->modelType ] )
			->orderBy( [ "$modelTagTable.order" => SORT_DESC, "$tagTable.name" => SORT_ASC, "$modelTagTable.id" => SORT_DESC ] )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveTags() {

		$tagTable		= Tag::tableName();
		$modelTagTable	= ModelTag::tableName();

		return Tag::find()
			->leftJoin( $modelTagTable, "$modelTagTable.modelId=$tagTable.id" )
			->where( "$modelTagTable.parentId=:pid AND $modelTagTable.parentType=:ptype AND $modelTagTable.active=1", [ ':pid' => $this->id, ':ptype' => $this->modelType ] )
			->orderBy( [ "$modelTagTable.order" => SORT_DESC, "$tagTable.name" => SORT_ASC, "$modelTagTable.id" => SORT_DESC ] )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getTagsByType( $type, $active = true ) {

		$tagTable		= Tag::tableName();
		$modelTagTable	= ModelTag::tableName();

		if( $active ) {

			return Tag::find()
				->leftJoin( $modelTagTable, "$modelTagTable.modelId=$tagTable.id" )
				->where( "$modelTagTable.parentId=:pid AND $modelTagTable.parentType=:ptype AND $modelTagTable.type=:type AND $modelTagTable.active=:active", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )
				->orderBy( [ "$modelTagTable.order" => SORT_DESC, "$tagTable.name" => SORT_ASC, "$modelTagTable.id" => SORT_DESC ] )
				->all();
		}

		return Tag::find()
			->leftJoin( $modelTagTable, "$modelTagTable.modelId=$tagTable.id" )
			->where( "$modelTagTable.parentId=:pid AND $modelTagTable.parentType=:ptype AND $modelTagTable.type=:type", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':type' => $type ] )
			->orderBy( [ "$modelTagTable.order" => SORT_DESC, "$tagTable.name" => SORT_ASC, "$modelTagTable.id" => SORT_DESC ] )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getTagIdList( $active = true ) {

		$tags = $active ? $this->activeTags : $this->tags;

		$tagsList = [];

		foreach( $tags as $tag ) {

			array_push( $tagsList, $tag->id );
		}

		return $tagsList;
	}

	/**
	 * @inheritdoc
	 */
	public function getTagNameList( $active = true ) {

		$tags = $active ? $this->activeTags : $this->tags;

		$tagsList = [];

		foreach( $tags as $tag ) {

			array_push( $tagsList, $tag->name );
		}

		return $tagsList;
	}

	/**
	 * @inheritdoc
	 */
	public function getTagIdNameList( $active = true ) {

		$tags = $active ? $this->activeTags : $this->tags;

		$tagsList = [];

		foreach( $tags as $tag ) {

			$tagsList[] = [ 'id' => $tag->id, 'name' => $tag->name ];
		}

		return $tagsList;
	}

	/**
	 * @inheritdoc
	 */
	public function getTagIdNameMap( $active = true ) {

		$tags = $active ? $this->activeTags : $this->tags;

		$tagsMap = [];

		foreach( $tags as $tag ) {

			$tagsMap[ $tag->id ] = $tag->name;
		}

		return $tagsMap;
	}

	/**
	 * @inheritdoc
	 */
	public function getTagSlugNameMap( $active = true ) {

		$tags = $active ? $this->activeTags : $this->tags;

		$tagsMap = [];

		foreach( $tags as $tag ) {

			$tagsMap[ $tag->slug ] = $tag->name;
		}

		return $tagsMap;
	}

	/**
	 * @inheritdoc
	 */
	public function getTagCsv( $limit = 0, $active = true ) {

		$tags = $active ? $this->activeTags : $this->tags;

		$tagsCsv = [];

		foreach( $tags as $tag ) {

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
	public function getTagLinks( $baseUrl, $config = [] ) {

		$wrapper	= isset( $config[ 'wrapper' ] ) ? $config[ 'wrapper' ] : true;
		$wrapperTag	= isset( $config[ 'wrapperTag' ] ) ? $config[ 'wrapperTag' ] : 'li';
		$limit		= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 0;
		$active		= isset( $config[ 'active' ] ) ? $config[ 'active' ] : true;
		$csv		= isset( $config[ 'csv' ] ) ? $config[ 'csv' ] : false;

		$tags = $active ? $this->activeTags : $this->tags;

		$tagLinks = [];

		foreach( $tags as $tag ) {

			if( $wrapper ) {

				$tagLinks[] = "<$wrapperTag><a href=\"$baseUrl/$tag->slug\">$tag->name</a></$wrapperTag>";
			}
			else {

				$tagLinks[] = "<a href=\"$baseUrl/$tag->slug\">$tag->name</a>";
			}
		}

		if( $limit > 0 ) {

			$tagLinks = array_splice( $tagLinks, $limit );
		}

		if( $csv ) {

			$tagLinks = join( ', ', $tagLinks );
		}
		else {

			$tagLinks = join( '', $tagLinks );
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
