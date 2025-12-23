<?php
namespace WPDeveloper\BetterDocs\Admin;

class ExportDefaults {
    public static function get_default_args(): array {
        return [
            'content'    => 'all',
            'author'     => false,
            'category'   => false,
            'start_date' => false,
            'end_date'   => false,
            'status'     => 'publish',
            'offset'     => 0,
            'limit'      => -1,
            'meta_query' => [],
            'query_args' => [],
        ];
    }
}
