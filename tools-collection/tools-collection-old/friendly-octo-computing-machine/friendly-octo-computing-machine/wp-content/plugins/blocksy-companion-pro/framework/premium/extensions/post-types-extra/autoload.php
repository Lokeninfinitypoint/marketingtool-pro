<?php

$autoload = [
	'Storage' => 'includes/storage.php',
	'Filtering' => 'features/filtering/feature.php',
	'TaxonomiesCustomization' => 'features/taxonomies-customization/feature.php',

	'DynamicData' => 'features/dynamic-data/feature.php',

	'CustomField' => 'features/dynamic-data/includes/custom-field.php',
	'CustomFieldsManager' => 'features/dynamic-data/includes/custom-fields-manager.php',

	'DynamicData\\ACFProvider' => 'features/dynamic-data/providers/acf.php',
	'DynamicData\\ACPTProvider' => 'features/dynamic-data/providers/acpt.php',
	'DynamicData\\CustomWPFieldProvider' => 'features/dynamic-data/providers/custom.php',
	'DynamicData\\JetEngineProvider' => 'features/dynamic-data/providers/jetengine.php',
	'DynamicData\\PodsProvider' => 'features/dynamic-data/providers/pods.php',
	'DynamicData\\MetaBoxProvider' => 'features/dynamic-data/providers/metabox.php',
	'DynamicData\\ToolsetProvider' => 'features/dynamic-data/providers/toolset.php',

	'DynamicData\\BaseProvider' => 'features/dynamic-data/includes/base-provider.php',

	'DynamicDataBlock' => 'features/dynamic-data/block.php',

	'ReadTime' => 'features/read-time/feature.php',
	'ReadProgress' => 'features/read-time/read-progress.php',
	'EstimatedReadTime' => 'features/read-time/estimated-read-time.php',
	'RelatedSlideshow' => 'features/related-slideshow/feature.php',
];

