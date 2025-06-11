<?php

// Element Props
$props = $node->props;

// Layout and Theme classes
$layout_class = 'uk-grid-small uk-child-width-1-2 uk-child-width-1-3@s uk-child-width-1-4@m'; // Default: grid
$wrapper_attributes = ['uk-grid' => true]; // Default for grid

if ($props['layout'] === 'list') {
    $layout_class = 'uk-list uk-list-striped'; // Classes for list view
    $wrapper_attributes = []; // No uk-grid for list
} elseif ($props['layout'] === 'carousel') {
    $layout_class = 'uk-slider-items uk-child-width-1-2 uk-child-width-1-3@s uk-child-width-1-4@m';
    // Carousel requires a wrapping structure
    $wrapper_attributes = [
        'uk-slider' => 'finite: false', // Example slider options
    ];
}

$theme_class = $props['theme'] === 'dark' ? 'uk-light uk-background-secondary' : 'uk-background-default'; // Adjust as needed

$el = $this->el('div', [
    'class' => [
        'yoo-color-palette',
        $theme_class,
        // 'uk-panel', // Replaced by theme class for background
        'uk-padding-small', // Add some padding
    ],
    'uk-margin' => true,
]);

?>
<?= $el($props, $attrs) ?>

    <?php if (!empty($props['color_group_headings'])) : ?>
        <h3 class="uk-text-center uk-margin-medium-bottom"><?= $props['color_group_headings'] ?></h3>
    <?php endif; ?>

    <?php if (isset($children) && count($children)) : ?>
        <?php if ($props['layout'] === 'carousel') : ?>
            <div <?= $this->attrs($wrapper_attributes) ?>>
                <div class="<?= $layout_class ?>">
                    <?php foreach ($children as $child) : ?>
                        <div><?= $builder->render($child, ['element' => $props]) ?></div>
                    <?php endforeach; ?>
                </div>
                <ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>
                <a class="uk-position-center-left uk-position-small uk-hidden-hover" href uk-slidenav-previous uk-slider-item="previous"></a>
                <a class="uk-position-center-right uk-position-small uk-hidden-hover" href uk-slidenav-next uk-slider-item="next"></a>
            </div>
        <?php else : ?>
            <div class="<?= $layout_class ?>" <?= $this->attrs($wrapper_attributes) ?>>
                <?php foreach ($children as $child) : ?>
                    <?php // For list layout, each item might need a <li> wrapper if not handled by UIkit list classes directly on child items ?>
                    <?= $builder->render($child, ['element' => $props]) ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php else : ?>
        <p class="uk-text-center">No colors defined yet.</p>
    <?php endif; ?>

<?= $el->end() ?>
