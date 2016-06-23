<?php
namespace cmsgears\core\common\models\base;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Entity - It's the parent entity for all the entities.
 */
abstract class Entity extends \yii\db\ActiveRecord {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    // Public -------------

	/**
	 * It can be used to differentiate multisite models.
	 */
	public $multisite = false;

	/**
	 * It can be used by models to pass variables to trait methods before calling them. This will help us to avoid maintaining object state in traits.
	 */
    public $traitParams = [];

    // Private/Protected --

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

    // yii\base\Component ----------------

    // yii\base\Model --------------------

    // Entity ----------------------------

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

	public function getMediumText( $attribute ) {

		if( strlen( $attribute ) > CoreGlobal::DISPLAY_TEXT_MEDIUM ) {

			$attribute	= substr( $attribute, 0, CoreGlobal::DISPLAY_TEXT_MEDIUM );
			$attribute	= "$notes ...";
		}

		return $attribute;
	}

	public function getClasspath() {

		return get_class( $this );
	}

	public function getNamespace() {

		$name	= get_class( $this );

		return array_slice(explode('\\', $name), 0, -1);
	}

	public function getClassname() {

		$name	= get_class( $this );

		return join( '', array_slice( explode( '\\', $name ), -1 ) );
	}

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

    // Entity ----------------------------

    // Create -------------

    // Read ---------------

    // Update -------------

    // Delete -------------
}

?>