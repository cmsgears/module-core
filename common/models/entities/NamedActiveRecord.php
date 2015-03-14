<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use yii\db\ActiveRecord;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\utilities\MessageUtil;

abstract class NamedActiveRecord extends ActiveRecord {

	// Abstract Methods --------------------------------------------

	abstract protected function getId();
    abstract protected function getName();

	// Instance Methods --------------------------------------------

    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$entity = static::findByName( $this->getName() );

            if( $entity ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingEntity = static::findByName( $this->getName() );

			if( isset( $existingEntity ) && $existingEntity->getId() != $this->getId() && strcmp( $existingEntity->getName(), $this->getName() ) == 0 ) {

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