<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Update Address | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
?>
<div class="box-crud-wrap">
	<div class="box-crud-wrap-main">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-address', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Address Details</div>
			</div>
			<div class="box-content-wrap">
				<div class="cmt-location box-content row max-cols-100">
					<div class="colf colf12x8">
						<div class="row max-cols-100">
							<div class="colf colf12x5">
								<?= $form->field( $model, 'title' ) ?>
							</div>
							<div class="colf colf12x2"></div>
							<div class="colf colf12x5">
								<?= $form->field( $model, 'line1' )->textInput()->label( 'Address 1' ) ?>
							</div>
						</div>
						<div class="row max-cols-100">
							<div class="colf colf12x5">
								<?= $form->field( $model, 'line2' )->textInput()->label( 'Address 2' ) ?>
							</div>
							<div class="colf colf12x2"></div>
							<div class="colf colf12x5">
								<?= $form->field( $model, 'line3' )->textInput()->label( 'Address 3' ) ?>
							</div>
						</div>
						<div class="row max-cols-100">
							<div class="cmt-location-countries colf colf12x5" cmt-app="core" cmt-controller="province" cmt-action="optionsList" action="location/province-options" cmt-keep cmt-custom>
								<?= $form->field( $model, 'countryId' )->dropDownList( $countriesMap, [ 'class' => 'cmt-location-country cmt-select cmt-change' ] ) ?>
							</div>
							<div class="colf colf12x2"> </div>
							<div class="cmt-location-provinces colf colf12x5" cmt-app="core" cmt-controller="region" cmt-action="optionsList" action="location/region-options" cmt-keep cmt-custom>
								<?= $form->field( $model, 'provinceId' )->dropDownList( $provincesMap, [ 'class' => 'cmt-location-province cmt-select cmt-change' ] ) ?>
							</div>
						</div>
						<div class="row max-cols-100">
							<div class="cmt-location-regions colf colf12x5">
								<?= $form->field( $model, 'regionId' )->dropDownList( $regionsMap, [ 'class' => 'cmt-location-region cmt-select cmt-change' ] ) ?>
							</div>
							<div class="colf colf12x2"> </div>
							<div class="cmt-location-city-fill colf colf12x5 auto-fill auto-fill-basic">
								<div class="auto-fill-source" cmt-app="core" cmt-controller="city" cmt-action="autoSearch" action="location/city-search" cmt-keep cmt-custom>
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
</div>
