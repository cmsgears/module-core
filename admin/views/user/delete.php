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

    	<?= $form->field( $model, 'user_email' )->textInput( [ 'disabled'=>'true' ] ) ?>
    	<?= $form->field( $model, 'user_username' )->textInput( [ 'disabled'=>'true' ] ) ?>
    	<?= $form->field( $model, 'user_status' )->dropDownList( $status, [ 'disabled'=>'true' ] ) ?>
		<?= $form->field( $model, 'user_role' )->dropDownList( $roles, [ 'disabled'=>'true' ] )  ?>
		<?= $form->field( $model, 'user_gender' )->dropDownList( $genders, [ 'disabled'=>'true' ] )  ?>
		<?= $form->field( $model, 'user_firstname' )->textInput( [ 'disabled'=>'true' ] ) ?>
		<?= $form->field( $model, 'user_lastname' )->textInput( [ 'disabled'=>'true' ] ) ?>
		<?= $form->field( $model, 'user_mobile' )->textInput( [ 'disabled'=>'true' ] ) ?>
		<?= $form->field( $model, 'user_newsletter' )->checkbox( [ 'disabled'=>'true' ] ) ?>

		<?=Html::a( "Cancel", [ '/cmgcore/user/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Delete" />

		<?php ActiveForm::end(); ?>
	</div>
</section>
<script type="text/javascript">
	initSidebar( "sidebar-identity", -1 );
</script>