<?php

namespace Blocksy\Extensions\PostTypesExtra;

class ReadTime {
	public function __construct() {
		new ReadProgress();
		new EstimatedReadTime();
	}
}

