<?php
if ($argc < 2) { fwrite(STDERR,"no file\n"); exit(1); }
$f = $argv[1];
$h = @file_get_contents($f);
if ($h === false) { fwrite(STDERR, "read fail: $f\n"); exit(1); }

if (preg_match("/<title>(.*?)<\/title>/is", $h, $m)) {
  $t = trim($m[1]);
} else {
  $t = basename($f, ".html");
}

require_once dirname(__DIR__) . "/wp-load.php";  // <-- correct path

$id = wp_insert_post([
  "post_title"   => $t,
  "post_content" => $h,
  "post_status"  => "publish",
  "post_type"    => "page",
]);

if (is_wp_error($id)) { fwrite(STDERR, $id->get_error_message()."\n"); exit(1); }

add_post_meta($id, "_imported_file", basename($f), true);
echo "CREATED:$id\n";
