<?php
namespace cmsgears\core\common\models\entities;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\utilities\MessageUtil;

abstract class NamedActiveRecord extends CmgEntity {

	// Instance Methods --------------------------------------------

    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$entity = static::findByName( $this->name );

            if( $entity ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingEntity = static::findByName( $this->name );

			if( isset( $existingEntity ) && $existingEntity->id != $this->id && strcmp( $existingEntity->name, $this->name ) == 0 ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	public static function findById( $id ) {

		// To be implemented by child classes
	}

	public static function findByName( $name ) {

		// To be implemented by child classes
	}
}

?>