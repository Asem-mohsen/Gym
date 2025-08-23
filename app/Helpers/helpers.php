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

if (!function_exists('getDocumentExtension')) {
/**
 * Get the file extension from a document's media file.
 *
 * @param  mixed  $document  The document object or media object
 * @return string
 */
    function getDocumentExtension($document): string
    {
        if (method_exists($document, 'file_name')) {
            $filename = $document->file_name;
        } 
        elseif (method_exists($document, 'getFirstMedia')) {
            $media = $document->getFirstMedia('document');
            if (!$media) {
                return 'unknown';
            }
            $filename = $media->file_name;
        }
        elseif (is_string($document)) {
            $filename = $document;
        }
        else {
            return 'unknown';
        }

        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        
        $extensionMap = [
            'pdf' => 'pdf',
            'doc' => 'doc',
            'docx' => 'doc',
            'xls' => 'xls',
            'xlsx' => 'xlsx',
            'ppt' => 'ppt',
            'pptx' => 'pptx',
            'txt' => 'txt',
            'zip' => 'zip',
            'rar' => 'rar',
            'jpg' => 'jpg',
            'jpeg' => 'jpg',
            'png' => 'png',
            'gif' => 'gif',
            'svg' => 'svg',
            'mp4' => 'mp4',
            'avi' => 'avi',
            'mov' => 'mov',
            'mp3' => 'mp3',
            'wav' => 'wav',
        ];

        $extension = strtolower($extension);
        
        return $extensionMap[$extension] ?? 'file';
    }
}