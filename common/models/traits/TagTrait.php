<?php
namespace cmsgears\core\common\models\traits;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Tag;
use cmsgears\core\common\models\entities\ModelTag;

/**
 * TagTrait can be used to add tagging feature to relevant models. The model must define the member variable $tagType which is unique for the model.
 */
trait TagTrait {

	public function getTagModels() {

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

	/**
	 * @return array - map of tag name and description
	 */
	public function getTagMap() {

		$tags 		= $this->tags;
		$tagsMap	= array();

		foreach ( $tags as $tag ) {

			$tagsMap[ $tag->tagId ] = $tag->name;
		}

		return $tagsMap;
	}

	public function getTagCsv() {

    	$tags		= $this->tags;
		$tagsCsv	= [];

		foreach ( $tags as $tag ) {

			$tagsCsv[] = $tag->name; 
		}

		return implode( ",", $tagsCsv );
	}
}

?>