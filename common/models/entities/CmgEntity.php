<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\ActiveRecord;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

abstract class CmgEntity extends ActiveRecord {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    // Public -------------

    public $traitParams = [];

    // Private/Protected --

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

    // Check whether model already exist    
    public function isExisting() {

        return isset( $this->id ) && $this->id > 0;
    }

    /**
     * The method allows to update a model for selected columns to target model.
     */
    public function copyForUpdateTo( $toModel, $attributes = [] ) {

        foreach ( $attributes as $attribute ) {

            $toModel->setAttribute( $attribute, $this->getAttribute( $attribute ) ); 
        }
    }

    /**
     * The method allows to update a model for selected columns from target model.
     */
    public function copyForUpdateFrom( $fromModel, $attributes = [] ) {

        foreach ( $attributes as $attribute ) {

            $this->setAttribute( $attribute, $fromModel->getAttribute( $attribute ) );
        }
    }

    // yii\base\Component ----------------

    // yii\base\Model --------------------

    // CmgEntity -------------------------

    // Static Methods ----------------------------------------------

    /**
     * Returns row count for the model
     */
    public static function getCount( $conditions = [] ) {

        return self::find()->where( $conditions )->count();
    }

    // Default Searching - Useful for id based models

    public static function findById( $id ) {

        return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
    }

    // yii\db\ActiveRecord ---------------

    // CmgEntity -------------------------

    // Create -------------

    // Read ---------------

    // Update -------------

    // Delete -------------
}

?>