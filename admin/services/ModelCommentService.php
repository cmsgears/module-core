<?php
namespace cmsgears\core\admin\services;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\entities\ModelComment;

/**
 * The class ModelCommentService is base class to perform database activities for ModelComment Entity.
 */
class ModelCommentService extends \cmsgears\core\common\services\ModelCommentService {

	// Static Methods ----------------------------------------------

	// Read ----------------

	// Pagination -------

    public static function getPagination( $config = [] ) {

        $sort = new Sort([
            'attributes' => [
                'owner' => [
                    'asc' => [ 'createdBy' => SORT_ASC ],
                    'desc' => ['createdBy' => SORT_DESC ],
                    'default' => SORT_DESC,
                    'label' => 'owner'
                ],
                'name' => [
                    'asc' => [ 'name' => SORT_ASC ],
                    'desc' => ['name' => SORT_DESC ],
                    'default' => SORT_DESC,
                    'label' => 'name'
                ],
                'slug' => [
                    'asc' => [ 'slug' => SORT_ASC ],
                    'desc' => ['slug' => SORT_DESC ],
                    'default' => SORT_DESC,
                    'label' => 'slug'
                ],
                'title' => [
                    'asc' => [ 'title' => SORT_ASC ],
                    'desc' => ['title' => SORT_DESC ],
                    'default' => SORT_DESC,
                    'label' => 'title'
                ]
            ]
        ]);

        if( !isset( $config[ 'conditions' ] ) ) {

            $config[ 'conditions' ] = [];
        }

        // Restrict to site

        if( !isset( $config[ 'sort' ] ) ) {

            $config[ 'sort' ] = $sort;
        }

        if( !isset( $config[ 'search-col' ] ) ) {

            $config[ 'search-col' ] = 'name';
        }

        return self::getDataProvider( new ModelComment(), $config );
    }

    /**
     * @return ActiveDataProvider
     */
    public static function getPaginationByType( $type, $config = [] ) {

        $config[ 'conditions' ][ 'type' ] = $type;

        return self::getPagination( $config );
    }

        // Update -----------

    public static function update( $comment ) {

        // Find existing Comment
        $commentToUpdate    = self::getById( $comment->id );

        // Copy and set Attributes
        $commentToUpdate->copyForUpdateFrom( $comment, [ 'name', 'email', 'avatarUrl', 'websiteUrl', 'rating', 'content', 'status' ] );

        // Update Comment
        $commentToUpdate->update();

        // Return updated Comment
        return $commentToUpdate;
    }

}

?>