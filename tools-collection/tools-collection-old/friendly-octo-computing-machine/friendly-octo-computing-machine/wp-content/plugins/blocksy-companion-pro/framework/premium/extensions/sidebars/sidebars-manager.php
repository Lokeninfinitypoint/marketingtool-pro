<?php

class BlocksySidebarsManager {
	private $key;

	public function __construct($key = 'blocksy_dynamic_sidebars_storage') {
		$this->key = $key;
	}

	public function get_all() {
		return get_option($this->key, []);
	}

	public function get_one($id) {
		$all = $this->get_all();

		if (! isset($all[$id])) {
			return null;
		}

		return $all[$id];
	}

	public function maybe_get_sidebar_that_matches() {
		$all = $this->get_all();

		foreach ($all as $id => $sidebar) {
			if (! isset($sidebar['conditions'])) {
				continue;
			}

			$conditions_manager = new \Blocksy\ConditionsManager();

			if ($conditions_manager->condition_matches($sidebar['conditions'])) {
				return 'ct-dynamic-sidebar-' . $id;
			}
		}

		return null;
	}

	public function set($atts, $id = 'blocksy_random') {
		$all = $this->get_all();

		if (! isset($atts['id'])) {
			if ($id == 'blocksy_random') {
				$id = blocksy_rand_md5();
			}

			$atts['id'] = $id;
		}

		$all[$atts['id']] = $atts;

		update_option($this->key, $all);
	}

    public function set_all($all) {
		update_option($this->key, $all);
    }

	public function delete($id) {
		$all = $this->get_all();
		$to_return = [];

		if (isset($all[$id])) {
			$to_return = $all[$id];
			unset($all[$id]);

			update_option($this->key, $all);
		}

		return $to_return;
	}

	public function reset($reset_to = array()) {
		update_option($this->key, $reset_to);
	}
}

