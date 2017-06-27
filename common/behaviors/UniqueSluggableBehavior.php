<?php
namespace cmsgears\core\common\behaviors;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\validators\UniqueSlugValidator;

class UniqueSluggableBehavior extends \yii\behaviors\SluggableBehavior {

	public $ensureUnique = true;

    /**
     * @inheritdoc
     */
	protected function validateSlug( $slug ) {

        $validator = Yii::createObject(array_merge(
            [
                'class' => UniqueSlugValidator::className(),
            ],
            $this->uniqueValidator
        ));

        $model = clone $this->owner;
        $model->clearErrors();
        $model->{$this->slugAttribute} = $slug;

        $validator->validateAttribute( $model, $this->slugAttribute );

        return !$model->hasErrors();
    }
}
