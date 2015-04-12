<?php
namespace cmsgears\core\common\models\entities;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * NamedActiveRecord Entity
 * It's the parent entity for all the CMSGears based entity which need unique name.
 */
abstract class NamedActiveRecord extends CmgEntity {

	// Instance Methods --------------------------------------------
	
	/**
	 * Validate name on creation to ensure that name is unique for all rows.
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$entity = static::findByName( $this->name );

            if( $entity ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validate name on creation to ensure that name is unique for all rows.
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingEntity = static::findByName( $this->name );

			if( isset( $existingEntity ) && $existingEntity->id != $this->id && strcmp( $existingEntity->name, $this->name ) == 0 ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	// Read

	public static function findById( $id ) {

		return Role::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByName( $name ) {

		return Role::find()->where( 'name=:name', [ ':name' => $name ] )->one();
	}
}

?>