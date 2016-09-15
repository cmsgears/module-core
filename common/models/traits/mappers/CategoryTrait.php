<?php
namespace cmsgears\core\common\models\traits\mappers;

// Yii Import
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Category;
use cmsgears\core\common\models\mappers\ModelCategory;

/**
 * CategoryTrait can be used to categories relevant models. The model must define the member variable $categoryType which is unique for the model.
 */
trait CategoryTrait {

    /**
     * @return array - ModelCategory associated with parent
     */
    public function getModelCategories() {

        return $this->hasMany( ModelCategory::className(), [ 'parentId' => 'id' ] )
                    ->where( "parentType='$this->mParentType'" );
    }

    /**
     * @return array - Category associated with parent
     */
    public function getCategories() {

        $category	= CoreTables::TABLE_CATEGORY;

        $query = $this->hasMany( Category::className(), [ 'id' => 'modelId' ] )
                    ->viaTable( CoreTables::TABLE_MODEL_CATEGORY, [ 'parentId' => 'id' ], function( $query ) {

                        $modelCategoryTable	= CoreTables::TABLE_MODEL_CATEGORY;

                        $query->onCondition( [ "$modelCategoryTable.parentType" => $this->mParentType ] );
                    })
                    ->where( "$category.type='$this->categoryType'" );

        return $query;
    }

    public function getActiveCategories() {

        $category	= CoreTables::TABLE_CATEGORY;

        $query = $this->hasMany( Category::className(), [ 'id' => 'modelId' ] )
                    ->viaTable( CoreTables::TABLE_MODEL_CATEGORY, [ 'parentId' => 'id' ], function( $query ) {

                        $modelCategoryTable	= CoreTables::TABLE_MODEL_CATEGORY;

                        $query->onCondition( [ "$modelCategoryTable.parentType" => $this->mParentType, "$modelCategoryTable.active" => true ] );
                    })
                    ->where( "$category.type='$this->categoryType'" );

        return $query;
    }

    public function getCategoriesByType( $type ) {

        $category	= CoreTables::TABLE_CATEGORY;

        $categories = $this->hasMany( Category::className(), [ 'id' => 'modelId' ] )
                            ->viaTable( CoreTables::TABLE_MODEL_CATEGORY, [ 'parentId' => 'id' ], function( $query ) {

                                $modelCategory	= CoreTables::TABLE_MODEL_CATEGORY;

                                $query->onCondition( [ "$modelCategory.parentType" => $this->mParentType, "$modelCategory.active" => true ] );
                            })
                            ->where( "$category.type='$type'" )
                            ->all();

        return $categories;
    }

    /**
     * @return array - list of category id associated with parent
     */
    public function getCategoryIdList( $active = false ) {

        $categories 	= null;
        $categoriesList	= [];

        if( $active ) {

            $categories = $this->activeCategories;
        }
        else {

            $categories = $this->categories;
        }

        foreach ( $categories as $category ) {

            array_push( $categoriesList, $category->id );
        }

        return $categoriesList;
    }

    public function getCategoryIdListByType( $type ) {

        $categories 		= $this->getCategoriesByType( $type );
        $categoriesList		= [];

        foreach ( $categories as $category ) {

            array_push( $categoriesList, $category->id );
        }

        return $categoriesList;
    }

    public function getCategoryNameList( $active = false ) {

        $categories 		= null;
        $categoriesList		= [];

        if( $active ) {

            $categories = $this->activeCategories;
        }
        else {

            $categories = $this->categories;
        }

        foreach ( $categories as $category ) {

            array_push( $categoriesList, $category->name );
        }

        return $categoriesList;
    }

    public function getCategoryNameListByType( $type ) {

        $categories 		= $this->getCategoriesByType( $type );
        $categoriesList		= [];

        foreach ( $categories as $category ) {

            array_push( $categoriesList, $category->name );
        }

        return $categoriesList;
    }

    /**
     * @return array - list of category id and name associated with parent
     */
    public function getCategoryIdNameList( $active = false ) {

        $categories 		= null;
        $categoriesList		= [];

        if( $active ) {

            $categories = $this->activeCategories;
        }
        else {

            $categories = $this->categories;
        }

        foreach ( $categories as $category ) {

            $categoriesList[] = [ 'id' => $category->id, 'name' => $category->name ];
        }

        return $categoriesList;
    }

    /**
     * @return array - map of category id and name associated with parent
     */
    public function getCategoryIdNameMap( $active = false ) {

        $categories 	= null;
        $categoriesMap	= [];

        if( $active ) {

            $categories 	= $this->activeCategories;
        }
        else {

            $categories 	= $this->categories;
        }

        foreach ( $categories as $category ) {

            $categoriesMap[ $category->id ] = $category->name;
        }

        return $categoriesMap;
    }

    public function getCategorySlugNameMap( $active = false ) {

        $categories 	= null;
        $categoriesMap	= [];

        if( $active ) {

            $categories 	= $this->activeCategories;
        }
        else {

            $categories 	= $this->categories;
        }

        foreach ( $categories as $category ) {

            $categoriesMap[ $category->slug ] = $category->name;
        }

        return $categoriesMap;
    }

    public function getCategoryCsv( $limit = 0, $active = true ) {

        $categories 			= null;
        $categoriesCsv			= [];

        if( $active ) {

            $categories 	= $this->activeCategories;
        }
        else {

            $categories 	= $this->categories;
        }

        foreach ( $categories as $category ) {

            $categoriesCsv[] = $category->name;
        }

        return implode( ", ", $categoriesCsv );
    }

    public function getCategoryLinks( $baseUrl, $limit = 0, $wrapper = 'li', $active = true ) {

        $categories 	= null;
        $categoryLinks	= null;
        $count			= 1;

        if( $active ) {

            $categories 	= $this->activeCategories;
        }
        else {

            $categories 	= $this->categories;
        }

        foreach ( $categories as $category ) {

            if( isset( $wrapper ) ) {

                $categoryLinks	.= "<$wrapper><a href='$baseUrl/$category->slug'>$category->name</a></$wrapper>";
            }
            else {

                $categoryLinks	.= "<a href='$baseUrl/$category->slug'>$category->name</a>";
            }

            if( $limit > 0 && $count >= $limit ) {

                break;
            }

            $count++;
        }

        return $categoryLinks;
    }
}
