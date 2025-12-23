<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class FiltersUtils {
	static public function get_query_params() {
		$url = blocksy_current_url();

		return [
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reading GET parameters for filter functionality, not form processing
			'params' => $_GET,
			'url' => $url
		];
	}

	static public function get_link_url($param, $value, $args = []) {
		$args = wp_parse_args($args, [
			'is_multiple' => true,
			'to_add' => []
		]);

		$value = urldecode($value);

		$query_string = array_merge([
			$param => $value,
		], $args['to_add']);

		$params = FiltersUtils::get_query_params();

		$url = $params['url'];
		$params = $params['params'];

		if (isset($params[$param])) {
			$url = remove_query_arg(
				array_merge([
					$param
				], array_keys($args['to_add'])),
				$url
			);

			$all_attrs = explode(',', $params[$param]);

			if ($args['is_multiple']) {
				if (in_array($value, $all_attrs)) {
					$all_attrs = array_diff($all_attrs, [$value]);
				} else {
					array_push($all_attrs, $value);
				}
			} else {
				$all_attrs = array_diff([$value], $all_attrs);
			}

			if (! empty($all_attrs)) {
				$query_string = array_merge([
					$param => implode(',', $all_attrs)
				], $args['to_add']);
			} else {
				$query_string = [];
			}
		}

		$url = add_query_arg($query_string, $url);

		// if url contains page in url, remove it
		//
		// Need to understand why is that.
		$url = preg_replace('/\/page\/[0-9]+/', '', $url);

		return $url;
	}
}

