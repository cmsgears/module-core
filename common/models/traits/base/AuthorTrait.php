<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\base;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\User;

/**
 * AuthorTrait can be used to add creator and modifier methods to relevant models.
 *
 * @property integer $createdBy
 * @property integer $modifiedBy
 *
 * @since 1.0.0
 */
trait AuthorTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// AuthorTrait ---------------------------

	/**
	 * @inheritdoc
	 */
	public function getCreator() {

		$userTable = CoreTables::TABLE_USER;

		return $this->hasOne( User::class, [ 'id' => 'createdBy' ] )->from( "$userTable as creator" );
	}

	/**
	 * @inheritdoc
	 */
	public function getModifier() {

		$userTable = CoreTables::TABLE_USER;

		return $this->hasOne( User::class, [ 'id' => 'modifiedBy' ] )->from( "$userTable as modifier" );
	}

	/**
	 * @inheritdoc
	 */
	public function isCreator( $user = null, $strict = false ) {

		if( !isset( $user ) && !$strict ) {

			$user	= Yii::$app->user->getIdentity();
		}

		if( isset( $user ) ) {

			if( isset( $this->createdBy ) ) {

				return $this->createdBy == $user->id;
			}
			else {

				return $this->createdBy == $user->id;
			}
		}

		return false;
	}

	/**
	 * @inheritdoc
	 */
	public static function queryWithCreator( $config = [] ) {

		$config[ 'relations' ]	= [ 'creator' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * @inheritdoc
	 */
	public static function queryWithModifier( $config = [] ) {

		$config[ 'relations' ]	= [ 'modifier' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * @inheritdoc
	 */
	public static function queryByCreatorId( $userId ) {

		return static::find()->where( 'createdBy=:cid', [ ':cid' => $userId ] );
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// AuthorTrait ---------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
