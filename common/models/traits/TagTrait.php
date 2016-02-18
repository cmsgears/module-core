<?php
namespace cmsgears\core\common\models\traits;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Tag;
use cmsgears\core\common\models\entities\ModelTag;

/**
 * TagTrait can be used to add tagging feature to relevant models. The model must define the member variable $tagType which is unique for the model.
 */
trait TagTrait {

	public function getModelTags() {

		$parentType	= $this->tagType;

		return $this->hasMany( ModelTag::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$parentType'" );
	}

	/**
	 * @return array - ModelTag associated with parent
	 */
	public function getTags() {

    	return $this->hasMany( Tag::className(), [ 'id' => 'tagId' ] )
					->viaTable( CoreTables::TABLE_MODEL_TAG, [ 'parentId' => 'id' ], function( $query ) {

						$modelTagTable	= CoreTables::TABLE_MODEL_TAG;

                      	$query->onCondition( [ "$modelTagTable.parentType" => $this->tagType ] );
					});
	}

	public function getActiveTags() {

    	return $this->hasMany( Tag::className(), [ 'id' => 'tagId' ] )
					->viaTable( CoreTables::TABLE_MODEL_TAG, [ 'parentId' => 'id' ], function( $query ) {

						$modelTagTable	= CoreTables::TABLE_MODEL_TAG;

                      	$query->onCondition( [ "$modelTagTable.parentType" => $this->tagType, "$modelTagTable.active" => true ] );
					});
	}

	public function getTagIdList() {

    	$tags 		= $this->tags;
		$tagsList	= [];

		foreach ( $tags as $tag ) {

			array_push( $tagsList, $tag->categoryId );
		}

		return $tagsList;
	}

	public function getTagNameList() {

    	$tags 		= $this->tags;
		$tagsList	= [];

		foreach ( $tags as $tag ) {

			array_push( $tagsList, $tag->name );
		}

		return $tagsList;
	}

	public function getTagIdNameList() {

    	$tags 		= $this->tags;
		$tagsList	= [];

		foreach ( $tags as $tag ) {

			$tagsList[] = [ 'id' => $tag->id, 'name' => $tag->name ];
		}

		return $tagsList;
	}

	/**
	 * @return array - map of tag name and description
	 */
	public function getTagMap() {

		$tags 		= $this->tags;
		$tagsMap	= [];

		foreach ( $tags as $tag ) {

			$tagsMap[ $tag->id ] = $tag->name;
		}

		return $tagsMap;
	}

	public function getTagSlugNameMap() {

		$tags 		= $this->tags;
		$tagsMap	= [];

		foreach ( $tags as $tag ) {

			$tagsMap[ $tag->slug ] = $tag->name;
		}

		return $tagsMap;
	}

	public function getTagCsv( $limit = 0 ) {

    	$tags 		= $this->tags;
		$tagsCsv	= [];
		$count		= 1;

		foreach ( $tags as $tag ) {

			$tagsCsv[] = $tag->name;

			if( $limit > 0 && $count >= $limit ) {

				break;
			}

			$count++;
		}

		return implode( ", ", $tagsCsv );
	}
}

?>