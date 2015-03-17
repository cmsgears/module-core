<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Delete User";
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Delete User</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-user-delete', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'email' )->textInput( [ 'disabled'=>'true' ] ) ?>
    	<?= $form->field( $model, 'username' )->textInput( [ 'disabled'=>'true' ] ) ?>
    	<?= $form->field( $model, 'status' )->dropDownList( $status, [ 'disabled'=>'true' ] ) ?>
		<?= $form->field( $model, 'role' )->dropDownList( $roles, [ 'disabled'=>'true' ] )  ?>
		<?= $form->field( $model, 'gender' )->dropDownList( $genders, [ 'disabled'=>'true' ] )  ?>
		<?= $form->field( $model, 'firstName' )->textInput( [ 'disabled'=>'true' ] ) ?>
		<?= $form->field( $model, 'lastName' )->textInput( [ 'disabled'=>'true' ] ) ?>
		<?= $form->field( $model, 'phone' )->textInput( [ 'disabled'=>'true' ] ) ?>
		<?= $form->field( $model, 'newsletter' )->checkbox( [ 'disabled'=>'true' ] ) ?>

		<?=Html::a( "Cancel", [ '/cmgcore/user/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Delete" />

		<?php ActiveForm::end(); ?>
	</div>
</section>
<script type="text/javascript">
	initSidebar( "sidebar-identity", 3 );
</script>