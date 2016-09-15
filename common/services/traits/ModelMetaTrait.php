<?php
namespace cmsgears\core\common\services\traits;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Used by services with base model having metas trait.
 */
trait ModelMetaTrait {

    // Instance methods --------------------------------------------

    // Yii parent classes --------------------

    // CMG interfaces ------------------------

    // CMG parent classes --------------------

    // ModelMetaTrait ------------------------

    // Data Provider ------

    // Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

    public function getIdMetaMapByType( $model, $type ) {

        $modelMetaService = Yii::$app->factory->get( 'modelMetaService' );

        return $modelMetaService->getIdMetaMapByType( $model->id, static::$parentType, $type );
    }

    public function getNameMetaMapByType( $model, $type ) {

        $modelMetaService = Yii::$app->factory->get( 'modelMetaService' );

        return $modelMetaService->getNameMetaMapByType( $model->id, static::$parentType, $type );
    }

    // Read - Others ---

    // Create -------------

    // Update -------------

    public function updateModelMetas( $model, $metas ) {

        $modelMetaService = Yii::$app->factory->get( 'modelMetaService' );

        foreach ( $metas as $meta ) {

            if( $model->id == $meta->parentId ) {

                $modelMetaService->update( $meta );
            }
        }

        return true;
    }

    // Delete -------------

    // Static Methods ----------------------------------------------

    // CMG parent classes --------------------

    // ModelMetaTrait ------------------------

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
