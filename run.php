<?php

$source_size = 0;
$target_size = 0;

$source_cnt = 0;
$target_cnt = 0;

$php_source_size = 0;
$php_target_size = 0;

$php_source_cnt = 0;
$php_target_cnt = 0;

xcopy('./source', './target', true);

echo "----------------------------------------" . "\n\n";

echo 'source_size: ' . number_format($source_size) . "\n";
echo 'target_size: ' . number_format($target_size) . "\n";

echo 'source_cnt: ' . $source_cnt . "\n";
echo 'target_cnt: ' . $target_cnt . "\n";

echo "\n" . "----------------------------------------" . "\n\n";

echo 'php_source_size: ' . number_format($php_source_size) . "\n";
echo 'php_target_size: ' . number_format($php_target_size) . "\n";

echo 'php_source_cnt: ' . $php_source_cnt . "\n";
echo 'php_target_cnt: ' . $php_target_cnt . "\n";

echo "\n" . "----------------------------------------" . "\n\n";

function xcopy($src, $dest, $compress = false) {
    global $source_size, $target_size, $source_cnt, $target_cnt;
    global $php_source_size, $php_target_size, $php_source_cnt, $php_target_cnt;

    foreach (scandir($src) as $file) {
        if (!is_readable($src . '/' . $file)) {
            continue;
        }

        if (($file == '.') || ($file == '..') || ($file == '.DS_Store')) {
            continue;
        }

        if (is_dir($src .'/' . $file)) {
            if (!file_exists($dest . '/' . $file)) {
                mkdir($dest . '/' . $file);
            }

            xcopy($src . '/' . $file, $dest . '/' . $file, $compress);
        } else {
            echo $src . '/' . $file . "\n => \n" . $dest . '/' . $file."\n\n";
            if (substr($file, -4) == '.php') {
                if ($compress == true) {
                    $content = php_strip_whitespace($src . '/' . $file);
                    file_put_contents($dest . '/' . $file, $content);
                } else {
                    copy($src . '/' . $file, $dest . '/' . $file);
                }

                $php_source_size += filesize($src . '/' . $file);
                $php_target_size += filesize($dest . '/' . $file);

                $php_source_cnt++;
                $php_target_cnt++;
            } else {
                copy($src . '/' . $file, $dest . '/' . $file);
            }

            $source_size += filesize($src . '/' . $file);
            $target_size += filesize($dest . '/' . $file);

            $source_cnt++;
            $target_cnt++;
        }
    }
}
