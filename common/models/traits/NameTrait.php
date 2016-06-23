<?php
namespace cmsgears\core\common\models\traits;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

trait NameTrait {

    // Constants/Statics --

	/**
	 * It can be used to determine whether entity is available only for a specific site.
	 */
	protected static $siteSpecific	= false;

	// Validators ---------

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