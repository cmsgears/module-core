<?php
namespace cmsgears\core\common\data;

class ActiveDataProvider extends yii\data\ActiveDataProvider {

    /**
     * @inheritdoc
     */
    protected function prepareTotalCount() {

		// TODO: Use stats table to get row count to avoid expensive full table scan query
		return parent::prepareTotalCount();
    }
}

