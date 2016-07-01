<?php
namespace cmsgears\core\common\models\traits\mappers;

// Yii Import
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Tag;
use cmsgears\core\common\models\mappers\ModelTag;

/**
 * TagTrait can be used to add tagging feature to relevant models. The model must define the member variable $tagType which is unique for the model.
 */
trait TagTrait {

	public function getModelTags() {

		return $this->hasMany( ModelTag::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$this->parentType'" );
	}

	/**
	 * @return array - ModelTag associated with parent
	 */
	public function getTags() {

    	return $this->hasMany( Tag::className(), [ 'id' => 'modelId' ] )
					->viaTable( CoreTables::TABLE_MODEL_TAG, [ 'parentId' => 'id' ], function( $query ) {

						$modelTagTable	= CoreTables::TABLE_MODEL_TAG;

                      	$query->onCondition( [ "$modelTagTable.parentType" => $this->parentType ] );
					});
	}

	public function getActiveTags() {

    	return $this->hasMany( Tag::className(), [ 'id' => 'modelId' ] )
					->viaTable( CoreTables::TABLE_MODEL_TAG, [ 'parentId' => 'id' ], function( $query ) {

						$modelTagTable	= CoreTables::TABLE_MODEL_TAG;

                      	$query->onCondition( [ "$modelTagTable.parentType" => $this->parentType, "$modelTagTable.active" => true ] );
					});
	}

	public function getTagIdList( $active = false ) {

    	$tags 		= null;
		$tagsList	= [];

		if( $active ) {

			$tags = $this->activeTags;
		}
		else {

			$tags = $this->tags;
		}

		foreach ( $tags as $tag ) {

			array_push( $tagsList, $tag->id );
		}

		return $tagsList;
	}

	public function getTagNameList( $active = false ) {

        $tags       = null;
        $tagsList   = [];

        if( $active ) {

            $tags = $this->activeTags;
        }
        else {

            $tags = $this->tags;
        }

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

    	$tags 		= $this->activeTags;
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

	public function getTagLinks( $baseUrl, $limit = 0, $wrapper = 'li' ) {

        $tags       = $this->activeTags;
        $tagLinks   = null;
        $count      = 1;

        foreach ( $tags as $tag ) {

            if( isset( $wrapper ) ) {

                $tagLinks   .= "<$wrapper><a href='$baseUrl/$tag->slug'>$tag->name</a></$wrapper>";
            }
            else {

                $tagLinks   .= " <a href='$baseUrl/$tag->slug'>$tag->name</a>";
            }

            if( $limit > 0 && $count >= $limit ) {

                break;
            }

            $count++;
        }

        return $tagLinks;
    }
}

?>