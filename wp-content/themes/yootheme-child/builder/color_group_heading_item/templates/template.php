<?php
// Item Props
$props = $node->props;
if (!empty($props['heading_title'])) {
    echo '<h4 class="uk-text-bold uk-margin-medium-top">' . esc_html($props['heading_title']) . '</h4>';
}
?>
