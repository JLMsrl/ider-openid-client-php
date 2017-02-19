<?php

namespace IDERConnect;


/**
 * IDER Helpers Class
 */
class IDERHelpers
{

    static function logRotate($text, $filename, $ext = 'log')
    {
        $text = "[" .strftime("%Y-%m-%d %H:%M:%S") . "] " . $text . "\n";

        // add basepath
        $filename = $filename;

        // add the point
        $ext = '.' . $ext;

        if (!file_exists($filename . $ext)) {
            touch($filename . $ext);
            chmod($filename . $ext, 0666);
        }

        // 2 mb
        if (filesize($filename . $ext) > 5 * 1024 * 1024) {

            // search for available filename
            $n = 1;
            while (file_exists($filename . '.' . $n . $ext)) {
                $n++;
            }

            rename($filename . $ext, $filename . '.' . $n . $ext);

            touch($filename . $ext);
            chmod($filename . $ext, 0666);
        }


        if (!is_writable($filename . $ext)) {
            error_log("Cannot open log file ($filename$ext)");
        }

        if (!$handle = fopen($filename . $ext, 'a')) {
            echo "Cannot open file ($filename$ext)";
        }

        if (fwrite($handle, $text) === FALSE) {
            echo "Cannot write to file ($filename$ext)";
        }

        fclose($handle);
    }

    /**
     * A wrapper around base64_decode which decodes Base64URL-encoded data,
     * which is not the same alphabet as base64.
     */
    static function base64url_decode($base64url)
    {
        return base64_decode(static::b64url2b64($base64url));
    }

    /**
     * Per RFC4648, "base64 encoding with URL-safe and filename-safe
     * alphabet".  This just replaces characters 62 and 63.  None of the
     * reference implementations seem to restore the padding if necessary,
     * but we'll do it anyway.
     *
     */
    static function b64url2b64($base64url)
    {
        // "Shouldn't" be necessary, but why not
        $padding = strlen($base64url) % 4;
        if ($padding > 0) {
            $base64url .= str_repeat("=", 4 - $padding);
        }
        return strtr($base64url, '-_', '+/');
    }


}