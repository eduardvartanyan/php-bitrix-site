<?php

namespace Vartek\ScreenKeyboard;

class Helpers
{
    public static function isAdminSection(): bool
    {
        return (defined('ADMIN_SECTION') && ADMIN_SECTION === true);
    }

    public static function localPath(string $path = ''): string
    {
        return $_SERVER['DOCUMENT_ROOT'] . '/local/' . ltrim($path, '/');
    }

    public static function assetPath(string $file = ''): string
    {
        return '/local/js/vartek.screenkeyboard/' . ltrim($file, '/');
    }

    public static function dump($var, bool $die = false): void
    {
        echo "<pre style='font-size:13px; background:#f6f6f6; padding:10px; border:1px solid #ddd'>";
        print_r($var);
        echo "</pre>";

        if ($die) die();
    }

    public static function insertBeforeBody(string $content, string $block): string
    {
        if (stripos($content, "</body>") !== false) {
            return str_ireplace("</body>", $block . "\n</body>", $content);
        }

        return $content . $block;
    }

    public static function json($data): string
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
