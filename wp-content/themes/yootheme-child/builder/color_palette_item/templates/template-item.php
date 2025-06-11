<?php

// Item Props specific to this instance of the child element
$props = $node->props;
// Parent Element Props (passed via $element when rendering child)
$parent_props = $params['element']; // These are props of the main 'color_palette' element

$el = $this->el('div', [
    'class' => [
        'yoo-color-palette-item',
        // Add a class to indicate light/dark context from parent if needed for item-specific styles
        // e.g., $parent_props['theme'] === 'dark' ? 'uk-light' : ''
    ],
    'id' => 'color-item-' . $node->id, // Unique ID for JS targeting
]);

$hex_value = !empty($props['hex_value']) ? $props['hex_value'] : null;
$swatch_style = $hex_value ? "background-color: {$hex_value};" : 'background-color: transparent; border: 1px dashed #ccc;';


// Prepare RGB and HSB values
$rgb_string = '';
$hsb_string = '';

if ($hex_value) {
    // Check parent settings to display these values
    if (!empty($parent_props['show_rgb']) && function_exists('hexToRgb')) {
        $rgb = hexToRgb($hex_value);
        $rgb_string = "RGB: {$rgb['r']}, {$rgb['g']}, {$rgb['b']}";
    }
    if (!empty($parent_props['show_hsb']) && function_exists('hexToHsb')) {
        $hsb = hexToHsb($hex_value);
        $hsb_string = "HSB: {$hsb['h']}°, {$hsb['s']}%, {$hsb['b']}%";
    }
}

?>
<?= $el($props) // Render the opening tag for the item ?>
    <div class="uk-card uk-card-default uk-card-hover uk-card-body uk-text-center uk-position-relative">
        <div class="yoo-color-swatch uk-height-small uk-margin-bottom" style="<?= $swatch_style ?>" data-hex="<?= $hex_value ? esc_attr($hex_value) : '' ?>"></div>

        <?php if (!empty($props['title'])) : ?>
            <div class="uk-margin-small-top uk-text-meta uk-text-bold"><?= esc_html($props['title']) ?></div>
        <?php endif; ?>

        <?php if (!empty($parent_props['show_hex']) && $hex_value) : ?>
            <div class="uk-text-meta uk-text-truncate" title="Click to copy HEX: <?= esc_attr($hex_value) ?>" data-hex="<?= esc_attr($hex_value) ?>">
                <?= esc_html($hex_value) ?>
            </div>
        <?php endif; ?>

        <?php if ($rgb_string) : ?>
            <div class="uk-text-meta uk-text-truncate" title="<?= esc_attr($rgb_string) ?>"><?= esc_html($rgb_string) ?></div>
        <?php endif; ?>

        <?php if ($hsb_string) : ?>
            <div class="uk-text-meta uk-text-truncate" title="<?= esc_attr($hsb_string) ?>"><?= esc_html($hsb_string) ?></div>
        <?php endif; ?>
    </div>

    <?php // Ensure the script uses the correct $node->id for uniqueness ?>
    <script>
    UIkit.util.ready(function () {
        const itemElement = document.getElementById('color-item-<?= $node->id ?>');
        if (itemElement) {
            const copyElements = itemElement.querySelectorAll('[data-hex]');
            copyElements.forEach(el => {
                el.style.cursor = 'pointer';
                UIkit.util.on(el, 'click', function (e) {
                    e.preventDefault();
                    e.stopPropagation(); // Prevent event bubbling if items are nested or similar
                    const hexToCopy = this.dataset.hex;
                    if (hexToCopy) {
                        navigator.clipboard.writeText(hexToCopy).then(function () {
                            UIkit.notification({
                                message: '<span uk-icon=\'icon: check\'></span> Copied ' + UIkit.util.escape(hexToCopy) + '!',
                                status: 'success',
                                pos: 'bottom-center',
                                timeout: 2000
                            });
                        }).catch(function (err) {
                            UIkit.notification({
                                message: '<span uk-icon=\'icon: warning\'></span> Failed to copy!',
                                status: 'danger',
                                pos: 'bottom-center',
                                timeout: 2000
                            });
                            console.error('Failed to copy text: ', err);
                        });
                    }
                });
            });
        }
    });
    </script>
<?= $el->end() // Render the closing tag for the item ?>
