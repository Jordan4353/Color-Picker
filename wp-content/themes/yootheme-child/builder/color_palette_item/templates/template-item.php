<?php

// Item Props
$props = $node->props;
// Parent Element Props (passed via $element)
$parent_props = $params['element'];

$el = $this->el('div', [
    'class' => [
        'yoo-color-palette-item',
    ],
    'id' => 'color-item-' . $node->id,
]);

$swatch_style = $props['hex_value'] ? "background-color: {$props['hex_value']};" : '';
$hex_value = $props['hex_value'] ?? '';

// Prepare RGB and HSB values
$rgb_string = '';
$hsb_string = '';

if ($hex_value) {
    if ($parent_props['show_rgb']) {
        $rgb = hexToRgb($hex_value); // Assumes hexToRgb is available
        $rgb_string = "RGB: {$rgb['r']}, {$rgb['g']}, {$rgb['b']}";
    }
    if ($parent_props['show_hsb']) {
        $hsb = hexToHsb($hex_value); // Assumes hexToHsb is available
        $hsb_string = "HSB: {$hsb['h']}°, {$hsb['s']}%, {$hsb['b']}%";
    }
}

?>
<?= $el($props) ?>
    <div class="uk-card uk-card-default uk-card-hover uk-card-body uk-text-center">
        <div class="yoo-color-swatch uk-height-small" style="<?= $swatch_style ?>" data-hex="<?= $hex_value ?>"></div>
        <?php if (!empty($props['name'])) : ?>
            <div class="uk-margin-small-top uk-text-meta"><?= $props['name'] ?></div>
        <?php endif; ?>

        <?php if ($parent_props['show_hex'] && $hex_value) : ?>
            <div class="uk-text-meta uk-text-truncate" title="Click to copy HEX: <?= $hex_value ?>" data-hex="<?= $hex_value ?>">
                <?= $hex_value ?>
            </div>
        <?php endif; ?>

        <?php if ($rgb_string) : ?>
            <div class="uk-text-meta uk-text-truncate" title="<?= $rgb_string ?>"><?= $rgb_string ?></div>
        <?php endif; ?>

        <?php if ($hsb_string) : ?>
            <div class="uk-text-meta uk-text-truncate" title="<?= $hsb_string ?>"><?= $hsb_string ?></div>
        <?php endif; ?>
    </div>

    <script>
    UIkit.util.ready(function () {
        const itemElement = document.getElementById('color-item-<?= $node->id ?>');
        if (itemElement) {
            const copyElements = itemElement.querySelectorAll('[data-hex]');
            copyElements.forEach(el => {
                el.style.cursor = 'pointer';
                UIkit.util.on(el, 'click', function (e) {
                    e.preventDefault();
                    const hexToCopy = this.dataset.hex;
                    if (hexToCopy) {
                        navigator.clipboard.writeText(hexToCopy).then(function () {
                            UIkit.notification({
                                message: '<span uk-icon=\'icon: check\'></span> Copied ' + hexToCopy + '!',
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
<?= $el->end() ?>
