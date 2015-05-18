<?php
namespace cmsgears\core\common\models\traits;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\ModelTag;

/**
 * TagTrait can be used to add tagging feature to relevant models. The model must define the member variable $tagType which is unique for the model.
 */
trait TagTrait {

	/**
	 * @return array - ModelTag associated with parent
	 */
	public function getTags() {

		$parentType	= $this->tagType;

    	return $this->hasMany( ModelTag::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$parentType'" );
	}

	/**
	 * @return array - map of tag name and description
	 */
	public function getTagsMap() {

		$tags 		= $this->tags;
		$tagsMap	= array();

		foreach ( $tags as $tag ) {

			$tagsMap[ $tag->name ] = $tag->description;
		}

		return $tagsMap;
	}

	public function getTagsCsv() {

    	$tags		= $this->tags;
		$tagsCsv	= [];

		foreach ( $tags as $tag ) {

			$tagsCsv[] = $tag->name; 
		}

		return implode( ",", $tagsCsv );
	}
}

?>