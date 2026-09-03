<?php
/**
 * The repeater Customizer control class. Only ever require()'d from
 * inside a customize_register callback (see inc/customizer.php) — by
 * that point WordPress core has already loaded WP_Customize_Control, so
 * this file never needs its own class_exists() guard, unlike anything
 * required unconditionally from functions.php.
 */

if (!defined('ABSPATH')) {
    exit;
}

class Avin_Customize_Repeater_Control extends WP_Customize_Control
{
    public $type = 'avin_repeater';

    /** @var array<int, array{key: string, type: string, label: string, placeholder?: string}> */
    public $fields = [];

    public $row_label = 'Item';

    public function enqueue()
    {
        wp_enqueue_media();
        wp_enqueue_style('avin-customizer-repeater', AVIN_URI . '/assets/css/customizer-repeater.css', [], avin_asset_version('/assets/css/customizer-repeater.css'));
        wp_enqueue_script('avin-customizer-repeater', AVIN_URI . '/assets/js/customizer-repeater.js', ['jquery', 'customize-controls'], avin_asset_version('/assets/js/customizer-repeater.js'), true);
    }

    public function render_content()
    {
        $rows = json_decode($this->value(), true);
        if (!is_array($rows)) {
            $rows = [];
        }
        ?>
		<label
			class="avin-repeater-control"
			data-avin-repeater
			data-setting="<?php echo esc_attr($this->id); ?>"
			data-fields="<?php echo esc_attr(wp_json_encode($this->fields)); ?>"
			data-value="<?php echo esc_attr(wp_json_encode($rows)); ?>"
			data-row-label="<?php echo esc_attr($this->row_label); ?>"
		>
			<?php if ($this->label) : ?>
				<span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
			<?php endif; ?>
			<?php if ($this->description) : ?>
				<span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
			<?php endif; ?>
			<div class="avin-repeater-rows" data-avin-repeater-rows></div>
			<button type="button" class="button avin-repeater-add" data-avin-repeater-add>
				<?php echo esc_html(sprintf(__('+ Add %s', 'avin'), $this->row_label)); ?>
			</button>
		</label>
		<?php
    }
}
