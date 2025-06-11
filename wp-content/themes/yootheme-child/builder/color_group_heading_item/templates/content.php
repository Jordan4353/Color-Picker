<?php
// Item Props
$props = $node->props;
if (!empty($props['heading_title'])) {
    echo esc_html($props['heading_title']) . ' ';
}
?>
