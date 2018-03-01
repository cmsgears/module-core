<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\base;

// Yii Imports
use Yii;

/**
 * Base model of all the entities, resources and mapper.
 *
 * @since 1.0.0
 */
abstract class ActiveRecord extends \yii\db\ActiveRecord {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	/**
	 * @var array list of temporary parameters passed to trait method.
	 *
	 * It can be used by models to pass variables to trait methods before method execution.
	 * This will help us to avoid maintaining object state specially required in traits on
	 * conditional basis.
	 */
	public $traitParams = [];

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

	// ActiveRecord --------------------------

	/**
	 * Check whether model already exist.
	 *
	 * It expects model to have an id column. The model classes without id column
	 * can override this method in order to work properly.
	 *
	 * @return boolean
	 */
	public function isExist() {

		return isset( $this->id ) && $this->id > 0;
	}

	/**
	 * Return class name with it's path.
	 *
	 * @return string Fully Qualified Class Name.
	 */
	public function getClasspath() {

		return get_class( $this );
	}

	/**
	 * Return class path i.e. namespace.
	 *
	 * @return string class path without class name.
	 */
	public function getNamespace() {

		$name	= get_class( $this );

		return array_slice(explode('\\', $name), 0, -1);
	}

	/**
	 * Return class name without path.
	 *
	 * @return string class name without class path.
	 */
	public function getClassname() {

		$name	= get_class( $this );

		return join( '', array_slice( explode( '\\', $name ), -1 ) );
	}

	/**
	 * Copy attributes to update a model for selected columns to target model.
	 *
	 * @param ActiveRecord $model Target Model to which attributes will be copied.
	 */
	public function copyForUpdateTo( $model, $attributes = [] ) {

		foreach ( $attributes as $attribute ) {

			$model->setAttribute( $attribute, $this->getAttribute( $attribute ) );
		}
	}

	/**
	 * Copy attributes to update a model for selected columns from target model.
	 *
	 * @param ActiveRecord $model Source Model from which attributes will be copied.
	 */
	public function copyForUpdateFrom( $model, $attributes = [] ) {

		foreach ( $attributes as $attribute ) {

			if( $this->hasAttribute( $attribute ) ) {

				$this->setAttribute( $attribute, $model->getAttribute( $attribute ) );
			}
			else {

				$this->$attribute = $model->$attribute;
			}
		}
	}

	/**
	 * Returns cut down string having pre-defined medium length.
	 *
	 * [[\cmsgears\core\common\config\CoreGlobal\CoreGlobal::TEXT_MEDIUM]] will be used by
	 * default in case application property does not override it.
	 *
	 * @param string $text to be cut down.
	 * @param string|boolean $link to be appended at the end of cut down text.
	 * @return string the cut down text.
	 */
	public function getMediumText( $text, $link = false ) {

		if( strlen( $text ) > Yii::$app->core->mediumText ) {

			$text	= substr( $text, 0, Yii::$app->core->mediumText );

			if( $link ) {

				$text	= "$text ... $link";
			}
			else {

				$text	= "$text ...";
			}
		}

		return $text;
	}

	/**
	 * Returns the selected attributes map of $this model.
	 *
	 * @param array $keys to be selected.
	 * @return array map of selected attributes.
	 */
	public function getAttributeArray( $keys ) {

		$data = [];

		foreach ( $keys as $key ) {

			$data[ $key ] = $this->$key;
		}

		return $data;
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	// CMG parent classes --------------------

	// ActiveRecord --------------------------

	/**
	 * Check whether the model is multi-site.
	 *
	 * The model must have siteId column and use [[\cmsgears\core\common\models\traits\base\MultiSiteTrait]].
	 *
	 * @return boolean
	 */
	public static function isMultiSite() {

		return false;
	}

	// Read - Query -----------

	/**
	 * Generates and return query with model relations.
	 *
	 * * Relations - The relations are method name without get prefix of methods returning
	 * query generated by one-to-one(hasOne) or one-to-many(hasMany) called within the method.
	 * * Conditions - Conditions to be applied to further filter the results of query generated
	 * after applying all the relations.
	 * * Filters - Filters applied on top of conditions to further filter the results.
	 * * Groups - Group By Column applied on the query generate after applying all the
	 * relations, conditions and filters.
	 *
	 * The final query generated by this method will be returned to generate results.
	 *
	 * @param array $config query configurations.
	 * @return \yii\db\ActiveQuery to query with related models.
	 */
	public static function queryWithAll( $config = [] ) {

		// Query Config --------

		$query			= static::find();

		$relations		= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [];
		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;
		$filters		= isset( $config[ 'filters' ] ) ? $config[ 'filters' ] : null;
		$groups			= isset( $config[ 'groups' ] ) ? $config[ 'groups' ] : null;

		// Relations -----------

		$eager	= false;
		$join	= 'LEFT JOIN';

		foreach ( $relations as $relation ) {

			if( is_array( $relation ) && isset( $relation[ 'relation' ] ) ) {

				$eager	= isset( $relation[ 'eager' ] ) ? $relation[ 'eager' ] : false;
				$join	= isset( $relation[ 'join' ] ) ? $relation[ 'join' ] : 'LEFT JOIN';

				$query->joinWith( $relation[ 'relation' ], $eager, $join );
			}
			else {

				$query->joinWith( $relation );
			}
		}

		// Conditions ----------

		if( isset( $conditions ) ) {

			foreach ( $conditions as $ckey => $condition ) {

				if( is_numeric( $ckey ) ) {

					$query->andWhere( $condition );

					unset( $conditions[ $ckey ] );
				}
			}

			$query->andWhere( $conditions );
		}

		// Filters -------------

		if( isset( $filters ) ) {

			foreach ( $filters as $filter ) {

				$query = $query->andFilterWhere( $filter );
			}
		}

		// Grouping -------------

		if( isset( $groups ) ) {

			foreach ( $groups as $group ) {

				$query	= $query->groupBy( $group );
			}
		}

		return $query;
	}

	/**
	 * Same as [[queryWithAll]] with relations limited to one-to-one.
	 *
	 * @param array $config query configurations.
	 * @return \yii\db\ActiveQuery to query with related models.
	 */
	public static function queryWithHasOne( $config = [] ) {

		return static::queryWithAll( $config );
	}

	// Read - Find ------------

	/**
	 * Returns row count for the model using given conditions.
	 *
	 * @param array $conditions
	 * @return integer|string
	 */
	public static function findCount( $conditions = [] ) {

		return static::find()->where( $conditions )->count();
	}

	/**
	 * Find and return the model using given id.
	 *
	 * @param integer $id
	 * @return ActiveRecord|array|null
	 */
	public static function findById( $id ) {

		return static::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	/**
	 * Find and delete the model using given id.
	 *
	 * @param integer $id model id
	 * @return int the number of rows deleted
	 */
	public static function deleteById( $id ) {

		return static::deleteAll( 'id=:id', [ ':id' => $id ] );
	}
}
