<?php
namespace cmsgears\core\common\models\base;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * NamedCmgEntity Entity
 * It's the parent entity for all the CMSGears based entity which need unique name.
 */
abstract class NamedCmgEntity extends CmgEntity {

    // Variables ---------------------------------------------------

    // Constants/Statics --

	/**
	 * It can be used to determine whether entity is available only for a specific site.
	 */
	protected static $siteSpecific	= false;

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

	// Instance Methods --------------------------------------------

    // yii\base\Component ----------------

    // yii\base\Model --------------------

    // NamedCmgEntity --------------------

	/**
	 * Validate name on creation to ensure that name is unique for all rows.
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$entity = static::findByName( $this->name );

            if( $entity ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validate name on creation to ensure that name is unique for all rows.
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingEntity = static::findByName( $this->name );

			if( isset( $existingEntity ) && $existingEntity->id != $this->id ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

    // Static Methods ----------------------------------------------

    // yii\db\ActiveRecord ---------------

    // NamedCmgEntity --------------------

    // Create -------------

    // Read ---------------

	/**
	 * @return ActiveRecord - by name
	 */
	public static function findByName( $name ) {

		if( static::$siteSpecific ) {

			$siteId	= Yii::$app->cmgCore->siteId;

			return static::find()->where( 'name=:name AND siteId=:siteId', [ ':name' => $name, ':siteId' => $siteId ] )->one();
		}
		else {

			return static::find()->where( 'name=:name', [ ':name' => $name ] )->one();
		}
	}

    // Update -------------

    // Delete -------------
}

?>