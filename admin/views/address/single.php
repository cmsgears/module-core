<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Update Address | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
?>
<div class="box-crud-wrap row">
	<div class="box-crud-wrap-main colf colf3x2">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-address', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Address Details</div>
			</div>
			<div class="box-content-wrap">
				<div class="box-content row max-cols-100 frm-address">
					<div class="colf colf12x8">
						<div class="row max-cols-100">
							<div class="colf colf12x5">
								<?= $form->field( $model, 'title' ) ?>
							</div>
							<div class="colf colf12x2"> </div>
							<div class="colf colf12x5"></div>
						</div>
						<div class="row max-cols-100">
							<div class="colf colf12x5">
								<?= $form->field( $model, 'line1' )->textInput()->label( 'Address 1' ) ?>
							</div>
							<div class="colf colf12x2"> </div>
							<div class="colf colf12x5">
								<?= $form->field( $model, 'line2' )->textInput()->label( 'Address 2' ) ?>
							</div>
						</div>
						<div class="row max-cols-100">
							<div class="colf colf12x5 wrap-country" cmt-app="location" cmt-controller="province" cmt-action="optionsList" action="province/options-list" cmt-keep cmt-custom>
								<?= $form->field( $model, 'countryId' )->dropDownList( $countriesMap, [ 'class' => 'cmt-select cmt-change address-country' ] ) ?>
							</div>
							<div class="colf colf12x2"> </div>
							<div class="colf colf12x5 wrap-province" cmt-app="location" cmt-controller="region" cmt-action="optionsList" action="region/options-list" cmt-keep cmt-custom>
								<?= $form->field( $model, 'provinceId' )->dropDownList( $provincesMap, [ 'class' => 'cmt-select cmt-change address-province' ] ) ?>
							</div>
						</div>
						<div class="row max-cols-100">
							<div class="colf colf12x5 wrap-region">
								<?= $form->field( $model, 'regionId' )->dropDownList( $regionsMap, [ 'class' => 'cmt-select address-region' ] ) ?>
							</div>
							<div class="colf colf12x2"> </div>
							<div class="colf colf12x5 auto-fill auto-fill-basic">
								<div class="auto-fill-source" cmt-app="location" cmt-controller="city" cmt-action="autoSearch" action="city/auto-search" cmt-keep cmt-custom>
									<?= Yii::$app->formDesigner->getAutoFill( $form, $model, 'cityName', [ 'class' => 'cmt-key-up auto-fill-text', 'placeholder' => 'Search City', 'autocomplete' => 'off' ], 'cmti cmti-search' ) ?>
								</div>
								<div class="auto-fill-target">
									<?= $form->field( $model, 'cityId' )->hiddenInput( [ 'class' => 'target' ] )->label( false ) ?>
								</div>
							</div>
						</div>
						<div class="row max-cols-100">
							<div class="colf colf12x5">
								<?= $form->field( $model, 'zip' )->textInput( [ 'class' => 'address-zip' ] ) ?>
							</div>
							<div class="colf colf12x2"> </div>
							<div class="colf colf12x5">
								<?= $form->field( $model, 'phone' ) ?>
							</div>
						</div>
						<div class="row max-cols-100">
							<div class="colf colf12x5">
								<?= $form->field( $model, 'fax' ) ?>
							</div>
							<div class="colf colf12x2"> </div>
							<div class="colf colf12x5">
								<?= $form->field( $model, 'landmark' ) ?>
							</div>
						</div>
					</div>
					<div class="colf colf12"></div>
					<div class="colf colf12x3 clearfix">
						<?= $form->field( $model, 'longitude' )->textInput( [ 'class' => 'longitude' ] ) ?>
						<?= $form->field( $model, 'latitude' )->textInput( [ 'class' => 'latitude' ] ) ?>
						<?= $form->field( $model, 'zoomLevel' )->textInput( [ 'class' => 'zoom' ] ) ?>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="align align-right">
			<?= Html::a( 'View All', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="frm-element-medium" type="submit" value="Update" />
		</div>
		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
	<div class="box-crud-wrap-sidebar colf colf3">

	</div>
</div>
