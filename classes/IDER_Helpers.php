<?php


class IDER_Helpers
{

    // TODO: partitions file by day
    static function logRotate($text, $filename, $ext = 'log')
    {
        $text = "[" . strftime("%Y-%m-%d %H:%M:%S") . "] " . $text . "\n";

        // add basepath
        $filename = IDER_PLUGIN_DIR . 'logs/' . $filename;

        // add the point
        $ext = '.' . $ext;

        if (!file_exists($filename . $ext)) {
            touch($filename . $ext);
            chmod($filename . $ext, 0666);
        }

        // 5 mb
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
}