<?php 


if (!function_exists('getScripts')) {

function getScripts(): string
{
    // Define an array of script paths
    $scripts = config('site.scripts', []);


    return collect($scripts)
        ->map(function ($script) {

            if (is_string($script)) {
                $script = [
                    'src' => $script,
                    'attribute' => 'defer',
                ];
            }

            $src = asset($script['src']);
            $attribute = !empty($script['attribute'])
                ? ' ' . e($script['attribute'])
                : '';

            return '<script src="' . $src . '"' . $attribute . '></script>';
        })
        ->implode("\n");
}
}

if (!function_exists('getStyles')) {
/**
 * Generate <link> tags for CSS files defined in config.
 *
 * @param  string  $direction  The text direction ('ltr' or 'rtl').
 * @return string
 */
function getStyles(): string
{
    $direction = app()->getLocale() == 'ar' ? 'rtl' : 'ltr';
    $stylesConfig = config('site.styles', []);

    $defaultStyles = $stylesConfig['default'] ?? [];

    $directionalStyles = $stylesConfig['directions'][$direction] ?? [];

    $styles = array_merge($defaultStyles, $directionalStyles);

    return collect($styles)
        ->map(fn($style) => '<link rel="stylesheet" href="' . asset($style) . '">')
        ->implode("\n");
}
}