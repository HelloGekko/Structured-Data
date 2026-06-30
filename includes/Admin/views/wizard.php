<?php
/**
 * Wizard meta box markup.
 *
 * @var \HelloGekko\StructuredData\SchemaDefinition $def Current definition.
 * @var \WP_Post                                    $post Current post.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

/** @var \HelloGekko\StructuredData\Schema\SchemaRegistry $registry */
$registry = $this->registry;
$grouped  = $registry->grouped();

// Saved state handed to the JS so it can rehydrate the dynamic rows.
$saved = [
	'type'       => $def->type(),
	'enabled'    => $def->enabled(),
	'conditions' => $def->conditions(),
	'properties' => $def->properties(),
	'faq'        => $def->faq(),
];
?>
<div id="hgsd-wizard" class="hgsd-wizard">

	<script type="application/json" id="hgsd-saved"><?php echo wp_json_encode( $saved ); ?></script>

	<ol class="hgsd-steps">
		<li class="is-active" data-step="1"><span>1</span> <?php esc_html_e( 'Schema Type', 'hg-structured-data' ); ?></li>
		<li data-step="2"><span>2</span> <?php esc_html_e( 'Display Conditions', 'hg-structured-data' ); ?></li>
		<li data-step="3"><span>3</span> <?php esc_html_e( 'Modify Schema Output', 'hg-structured-data' ); ?></li>
	</ol>

	<?php /* Step 1: Schema type ------------------------------------------------ */ ?>
	<section class="hgsd-step" data-step="1">
		<h3><?php esc_html_e( 'Select a schema type', 'hg-structured-data' ); ?></h3>
		<p class="description"><?php esc_html_e( 'Choose the schema.org type you want to output.', 'hg-structured-data' ); ?></p>

		<div class="hgsd-type-grid">
			<?php foreach ( $grouped as $group => $types ) : ?>
				<div class="hgsd-type-group">
					<h4><?php echo esc_html( $group ); ?></h4>
					<?php foreach ( $types as $key => $type ) : ?>
						<label class="hgsd-type-option">
							<input type="radio" name="hgsd[type]" value="<?php echo esc_attr( $key ); ?>" <?php checked( $saved['type'], $key ); ?> />
							<span><?php echo esc_html( $type->label() ); ?></span>
						</label>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<?php /* Step 2: Display conditions --------------------------------------- */ ?>
	<section class="hgsd-step" data-step="2" hidden>
		<h3><?php esc_html_e( 'Where should this schema be displayed?', 'hg-structured-data' ); ?></h3>

		<p class="hgsd-logic">
			<label>
				<?php esc_html_e( 'Match', 'hg-structured-data' ); ?>
				<select name="hgsd[conditions][logic]">
					<option value="any" <?php selected( $saved['conditions']['logic'], 'any' ); ?>><?php esc_html_e( 'any of the include rules', 'hg-structured-data' ); ?></option>
					<option value="all" <?php selected( $saved['conditions']['logic'], 'all' ); ?>><?php esc_html_e( 'all of the include rules', 'hg-structured-data' ); ?></option>
				</select>
			</label>
		</p>

		<h4><?php esc_html_e( 'Include', 'hg-structured-data' ); ?></h4>
		<div class="hgsd-rules" data-group="include"></div>
		<button type="button" class="button hgsd-add-rule" data-group="include"><?php esc_html_e( 'Add Condition', 'hg-structured-data' ); ?></button>

		<h4><?php esc_html_e( 'Exclude', 'hg-structured-data' ); ?></h4>
		<div class="hgsd-rules" data-group="exclude"></div>
		<button type="button" class="button hgsd-add-rule" data-group="exclude"><?php esc_html_e( 'Add Exclusion', 'hg-structured-data' ); ?></button>
	</section>

	<?php /* Step 3: Modify schema output ------------------------------------- */ ?>
	<section class="hgsd-step" data-step="3" hidden>
		<h3><?php esc_html_e( 'Modify Schema Output', 'hg-structured-data' ); ?></h3>
		<?php if ( defined( 'HGSD_SCHEMA_VERSION' ) ) : ?>
			<p class="hgsd-version">
				<?php
				printf(
					/* translators: %s: schema.org version number. */
					esc_html__( 'Properties based on schema.org v%s', 'hg-structured-data' ),
					esc_html( HGSD_SCHEMA_VERSION )
				);
				?>
			</p>
		<?php endif; ?>

		<?php /* Generic property mapping (hidden for FAQ). */ ?>
		<div class="hgsd-properties-wrap">
			<p class="description"><?php esc_html_e( 'Add properties and map each one to a WordPress field, ACF field or custom text.', 'hg-structured-data' ); ?></p>
			<div class="hgsd-properties"></div>
			<button type="button" class="button button-secondary hgsd-add-property"><?php esc_html_e( 'Add Property', 'hg-structured-data' ); ?></button>
		</div>

		<?php /* FAQ-specific UI. */ ?>
		<div class="hgsd-faq-wrap" hidden>
			<p>
				<label>
					<strong><?php esc_html_e( 'Choose Method', 'hg-structured-data' ); ?></strong><br />
					<select name="hgsd[faq][method]" class="hgsd-faq-method">
						<option value="manual" <?php selected( $saved['faq']['method'], 'manual' ); ?>><?php esc_html_e( 'Manual', 'hg-structured-data' ); ?></option>
						<option value="automatic" <?php selected( $saved['faq']['method'], 'automatic' ); ?>><?php esc_html_e( 'Automatic (ACF repeater)', 'hg-structured-data' ); ?></option>
					</select>
				</label>
			</p>

			<div class="hgsd-faq-automatic" hidden>
				<p class="hgsd-acf-missing" hidden><em><?php esc_html_e( 'ACF Pro with a repeater field is required for automatic FAQ.', 'hg-structured-data' ); ?></em></p>
				<p>
					<label><?php esc_html_e( 'Repeater field', 'hg-structured-data' ); ?><br />
						<select name="hgsd[faq][acf_repeater]" class="hgsd-faq-repeater" data-selected="<?php echo esc_attr( $saved['faq']['acf_repeater'] ); ?>"></select>
					</label>
				</p>
				<p>
					<label><?php esc_html_e( 'Question sub-field', 'hg-structured-data' ); ?><br />
						<select name="hgsd[faq][question_subfield]" class="hgsd-faq-question" data-selected="<?php echo esc_attr( $saved['faq']['question_subfield'] ); ?>"></select>
					</label>
				</p>
				<p>
					<label><?php esc_html_e( 'Answer sub-field', 'hg-structured-data' ); ?><br />
						<select name="hgsd[faq][answer_subfield]" class="hgsd-faq-answer" data-selected="<?php echo esc_attr( $saved['faq']['answer_subfield'] ); ?>"></select>
					</label>
				</p>
			</div>

			<div class="hgsd-faq-manual">
				<div class="hgsd-faq-items"></div>
				<button type="button" class="button hgsd-add-faq"><?php esc_html_e( 'Add Question', 'hg-structured-data' ); ?></button>
			</div>
		</div>
	</section>

	<div class="hgsd-nav">
		<button type="button" class="button hgsd-prev" hidden><?php esc_html_e( '← Back', 'hg-structured-data' ); ?></button>
		<button type="button" class="button button-primary hgsd-next"><?php esc_html_e( 'Next →', 'hg-structured-data' ); ?></button>
		<span class="hgsd-final-hint" hidden><?php esc_html_e( 'Press “Update” to save.', 'hg-structured-data' ); ?></span>
	</div>
</div>
