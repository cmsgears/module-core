<?php
// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\widgets\aform\AjaxForm;
?>

<div class="box-form box-form-regular">
	<span class="cmti cmti-edit btn-edit"></span>
	<div class="wrap-info">
		<?php
			if( isset( $fieldsMap ) && count( $fieldsMap ) > 0 ) {

				foreach ( $fieldsMap as $field ) {
		?>
				<label><?= $field->label ?></label>
				<label><?= $field->getFieldValue() ?></label>
		<?php
				}
			}
			else {
		?>
		<p>No settings found.</p>
		<?php } ?>
	</div>
	<div class="row row-large wrap-form frm-split-40-60">
		<?php if( isset( $fieldsMap ) && count( $fieldsMap ) > 0 ) { ?>

		<?= AjaxForm::widget([
			'options' => [ 'class' => 'cmt-form', 'id' => "frm-setting-$type", 'cmt-keep' => 1 ],
			'slug' => "config-$type", 'type' => CoreGlobal::TYPE_SYSTEM,
			'showLabel' => true, 'model' => $model,
			'ajaxUrl' => "core/settings/update?type=$type",
			'cmtApp' => 'site', 'cmtController' => 'settings', 'cmtAction' => 'update',
			'modelName' => "setting$type"
		])?>

		<?php } ?>
	</div>
</div>