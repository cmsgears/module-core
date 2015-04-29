<?php
namespace cmsgears\core\common\models\traits;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\ModelTag;

trait TagTrait {

	public function getTags() {

		$parentType	= $this->tagType;

    	return $this->hasMany( ModelTag::className(), [ 'parentId' => 'id' ] )
					->where( "parentType=$parentType" );
	}

	public function getTagsNameDescMap() {

		$tags 		= $this->tags;
		$tagsMap	= array();

		foreach ( $tags as $tag ) {

			$tagsMap[ $tag->name ] = $tag->description;
		}

		return $tagsMap;
	}
}

?>