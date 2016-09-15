<?php
namespace cmsgears\core\common\services\traits;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Used by services with base model having name, slug and type columns with sluggable behaviour which allows unique name for a type.
 */
trait NameTypeTrait {

    // Instance methods --------------------------------------------

    // Yii parent classes --------------------

    // CMG interfaces ------------------------

    // CMG parent classes --------------------

    // NameSlugTrait -------------------------

    // Data Provider ------

    public function getPageByType( $type, $config = [] ) {

        $modelTable	= static::$modelTable;

        $config[ 'conditions' ][ "$modelTable.type" ] 	= $type;

        return $this->getPage( $config );
    }

    // Read ---------------

    // Read - Models ---

    /**
     * The method is useful for models having unique name irrespective of their type. In such cases $first must be true.
     *
     * If model allows unique name for a particular type, we might have multiple models having same name. In such cases $first must be false.
     */
    public function getByName( $name, $first = false ) {

        $modelClass = static::$modelClass;

        return $modelClass::findByName( $name, $first );
    }

    /**
     * It can be used to get all the models for given type or first model if $first is set to true.
     */
    public function getByType( $type, $first = false ) {

        $modelClass = static::$modelClass;

        return $modelClass::findByType( $type, $first );
    }

    /**
     * It returns single model having given name and type.
     */
    public function getByNameType( $name, $type ) {

        $modelClass = static::$modelClass;

        return $modelClass::findByNameType( $name, $type );
    }

    public function searchByName( $name, $config = [] ) {

        $modelClass					= static::$modelClass;
        $modelTable					= static::$modelTable;

        $config[ 'query' ] 			= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
        $config[ 'columns' ]		= isset( $config[ 'columns' ] ) ? $config[ 'columns' ] : [ "$modelTable.id", "$modelTable.name" ];
        $config[ 'array' ]			= isset( $config[ 'array' ] ) ? $config[ 'array' ] : true;

        $config[ 'query' ]->andWhere( "$modelTable.name like '$name%'" );

        if( $modelClass::$multiSite ) {

            $config[ 'conditions' ][ "$modelTable.siteId" ]	= Yii::$app->core->siteId;
        }

        return static::searchModels( $config );
    }

    public function searchByNameType( $name, $type, $config = [] ) {

        $modelTable		= static::$modelTable;

        $config[ 'conditions' ][ "$modelTable.type" ]	= $type;

        return $this->searchByName( $name, $config );
    }

    // Read - Lists ----

    public function getIdListByType( $type, $config = [] ) {

        $config[ 'conditions' ][ 'type' ] = $type;

        return static::findIdList( $config );
    }

    public function getIdNameListByType( $type, $config = [] ) {

        $config[ 'conditions' ][ 'type' ] = $type;

        return $this->getIdNameList( $config );
    }

    // Read - Maps -----

    public function getIdNameMapByType( $type, $config = [] ) {

        $config[ 'conditions' ][ 'type' ] = $type;

        return $this->getIdNameMap( $config );
    }

    // Create -------------

    // Update -------------

    // Delete -------------

    // Static Methods ----------------------------------------------

    // CMG parent classes --------------------

    // NameSlugTrait -------------------------

    // Data Provider ------

    // Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

    // Read - Others ---

    // Create -------------

    // Update -------------

    // Delete -------------

}
