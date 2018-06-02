<?php
// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\widgets\aform\AjaxFormWidget;
?>

<div class="box-form box-form-basic box-form-regular">
	<span class="box-form-trigger cmti cmti-edit"></span>
	<div class="box-form-info-wrap row row-large">
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
	<div class="box-form-content-wrap row row-large form frm-split-40-60">
		<?php if( isset( $fieldsMap ) && count( $fieldsMap ) > 0 ) { ?>

		<?= AjaxFormWidget::widget([
			'options' => [ 'class' => 'cmt-form', 'id' => "frm-setting-$type", 'cmt-keep' => 1 ],
			'slug' => "config-$type", 'type' => CoreGlobal::TYPE_SYSTEM,
			'label' => true, 'form' => $form, 'formName' => "setting$type",
			'ajaxUrl' => "core/settings/update?type=$type",
			'cmtApp' => 'site', 'cmtController' => 'settings', 'cmtAction' => 'update'
		])?>

		<?php } ?>
	</div>
</div>
