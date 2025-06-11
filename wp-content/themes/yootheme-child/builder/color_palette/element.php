<?php

// Helper function to convert HEX to RGB
function hexToRgb($hex, $alpha = false) {
    $hex      = str_replace('#', '', $hex);
    $length   = strlen($hex);
    $rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
    $rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
    $rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
    if ( $alpha ) {
        $rgb['a'] = $alpha;
    }
    return $rgb;
}

// Helper function to convert HEX to HSB (HSV)
function hexToHsb($hex) {
    $rgb = hexToRgb($hex);
    $r = $rgb['r'] / 255;
    $g = $rgb['g'] / 255;
    $b = $rgb['b'] / 255;

    $max = max($r, $g, $b);
    $min = min($r, $g, $b);
    $delta = $max - $min;

    $h = 0;
    $s = 0;
    $v = $max; // Brightness

    if ($delta == 0) {
        $h = 0; // undefined, maybe NAN or 0
        $s = 0;
    } else {
        $s = $delta / $max;
        if ($r == $max) {
            $h = ($g - $b) / $delta;
        } elseif ($g == $max) {
            $h = 2 + ($b - $r) / $delta;
        } else {
            $h = 4 + ($r - $g) / $delta;
        }
        $h *= 60;
        if ($h < 0) {
            $h += 360;
        }
    }
    return ['h' => round($h), 's' => round($s * 100), 'b' => round($v * 100)];
}

return [
    'transforms' => [
        'render' => function ($node, array $params) {
            // No specific transforms for now, but keep the structure
        }
    ],
    'updates' => [
        // No updates for now
    ],
    // Make functions available in templates
    'fields' => [
        'hexToRgb' => [
            'type' => 'text', // Dummy type, not shown in UI
            'eval' => 'hexToRgb($value)', // How to call it
        ],
        'hexToHsb' => [
            'type' => 'text', // Dummy type
            'eval' => 'hexToHsb($value)',
        ],
    ],
    // Expose helper functions to the template context
    // This part is conceptual for YOOtheme Pro. Functions defined globally in element.php are typically available.
    // For direct calls like $this->app->myHelpers->hexToRgb() you might need to register them with the service container,
    // but global functions are simpler for this case.
];
?>
