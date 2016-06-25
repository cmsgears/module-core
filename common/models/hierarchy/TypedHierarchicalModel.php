<?php
namespace cmsgears\core\common\models\hierarchy;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * TypedHierarchicalModel - It can be used by models which need parent child relationship and support type.
 *
 * @property long $parentId
 * @property long $rootId
 * @property string $name
 * @property string $type
 * @property integer lValue
 * @property integer rValue
 */
abstract class TypedHierarchicalModel extends HierarchicalModel {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

    /**
     * Validates to ensure that name is used only for one model for a particular type and belonging to same parent
     */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByNameType( $this->name, $this->type, $this->parentId ) ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

    /**
     * Validates to ensure that name is used only for one model for a particular type and belonging to same parent
     */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            $existingModel = self::findByNameType( $this->name, $this->type, $this->parentId );

            if( isset( $existingModel ) && $existingModel->id != $this->id ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	// TypedHierarchicalModel ----------------

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	// CMG parent classes --------------------

	// TypedHierarchicalModel ----------------

	// Read - Query -----------

	// Read - Find ------------

    /**
     * @return Category - by type and name
     */
    public static function findByNameType( $name, $type, $parentId = null ) {

		if( static::$multiSite ) {

	        $siteId = Yii::$app->cmgCore->siteId;

	        if( isset( $parentId ) ) {

	            return self::find()->where( 'parentId=:pid AND name=:name AND type=:type AND siteId=:siteId', [ ':pid' => $parentId, ':name' => $name, ':type' => $type, ':siteId' => $siteId ] )->one();
	        }
	        else {

	            return self::find()->where( 'name=:name AND type=:type AND siteId=:siteId', [ ':name' => $name, ':type' => $type, ':siteId' => $siteId ] )->one();
	        }
		}
		else {

	        if( isset( $parentId ) ) {

	            return self::find()->where( 'parentId=:pid AND name=:name AND type=:type', [ ':pid' => $parentId, ':name' => $name, ':type' => $type ] )->one();
	        }
	        else {

	            return self::find()->where( 'name=:name AND type=:type', [ ':name' => $name, ':type' => $type ] )->one();
	        }
		}
    }

    /**
     * @return Category - checks whether category exist by type and name
     */
    public static function isExistByNameType( $name, $type, $parentId = null ) {

        $category = self::findByNameType( $name, $type, $parentId );

        return isset( $category );
    }

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}

?>