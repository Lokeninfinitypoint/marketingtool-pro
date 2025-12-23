<?php

class MM_WPFS_Languages {
    /**
	 * Creates an array of locales/languages supported by Stripe Checkout.
	 *
	 * @return array list of locales/languages
	 */
    public static function getCheckoutLanguages() {
        return [
            [
                'value' => 'bg',
                'name' => __('Bulgarian', 'wp-full-stripe-free')
            ],
            [
                'value' => 'cs',
                'name' => __('Czech', 'wp-full-stripe-free')
            ],
            [
                'value' => 'da',
                'name' => __('Danish', 'wp-full-stripe-free')
            ],
            [
                'value' => 'de',
                'name' => __('German', 'wp-full-stripe-free')
            ],
            [
                'value' => 'el',
                'name' => __('Greek', 'wp-full-stripe-free')
            ],
            [
                'value' => 'en',
                'name' => __('English', 'wp-full-stripe-free')
            ],
            [
                'value' => 'en-GB',
                'name' => __('English (United Kingdom)', 'wp-full-stripe-free')
            ],
            [
                'value' => 'es',
                'name' => __('Spanish (Spain)', 'wp-full-stripe-free')
            ],
            [
                'value' => 'es-419',
                'name' => __('Spanish (Latin America)', 'wp-full-stripe-free')
            ],
            [
                'value' => 'et',
                'name' => __('Estonian', 'wp-full-stripe-free')
            ],
            [
                'value' => 'fi',
                'name' => __('Finnish', 'wp-full-stripe-free')
            ],
            [
                'value' => 'fil',
                'name' => __('Filipino', 'wp-full-stripe-free')
            ],
            [
                'value' => 'fr',
                'name' => __('French (France)', 'wp-full-stripe-free')
            ],
            [
                'value' => 'fr-CA',
                'name' => __('French (Canada)', 'wp-full-stripe-free')
            ],
            [
                'value' => 'hr',
                'name' => __('Croatian', 'wp-full-stripe-free')
            ],
            [
                'value' => 'hu',
                'name' => __('Hungarian', 'wp-full-stripe-free')
            ],
            [
                'value' => 'id',
                'name' => __('Indonesian', 'wp-full-stripe-free')
            ],
            [
                'value' => 'it',
                'name' => __('Italian', 'wp-full-stripe-free')
            ],
            [
                'value' => 'ja',
                'name' => __('Japanese', 'wp-full-stripe-free')
            ],
            [
                'value' => 'ko',
                'name' => __('Korean', 'wp-full-stripe-free')
            ],
            [
                'value' => 'lv',
                'name' => __('Lithuanian', 'wp-full-stripe-free')
            ],
            [
                'value' => 'lt',
                'name' => __('Latvian', 'wp-full-stripe-free')
            ],
            [
                'value' => 'ms',
                'name' => __('Malay', 'wp-full-stripe-free')
            ],
            [
                'value' => 'mt',
                'name' => __('Maltese', 'wp-full-stripe-free')
            ],
            [
                'value' => 'nb',
                'name' => __('Norwegian Bokmål', 'wp-full-stripe-free')
            ],
            [
                'value' => 'nl',
                'name' => __('Dutch', 'wp-full-stripe-free')
            ],
            [
                'value' => 'pl',
                'name' => __('Polish', 'wp-full-stripe-free')
            ],
            [
                'value' => 'pt',
                'name' => __('Portuguese', 'wp-full-stripe-free')
            ],
            [
                'value' => 'pt-BR',
                'name' => __('Portuguese (Brazil)', 'wp-full-stripe-free')
            ],
            [
                'value' => 'ro',
                'name' => __('Romanian', 'wp-full-stripe-free')
            ],
            [
                'value' => 'ru',
                'name' => __('Russian', 'wp-full-stripe-free')
            ],
            [
                'value' => 'sk',
                'name' => __('Slovak', 'wp-full-stripe-free')
            ],
            [
                'value' => 'sl',
                'name' => __('Slovenian', 'wp-full-stripe-free')
            ],
            [
                'value' => 'sv',
                'name' => __('Swedish', 'wp-full-stripe-free')
            ],
            [
                'value' => 'th',
                'name' => __('Thai', 'wp-full-stripe-free')
            ],
            [
                'value' => 'tr',
                'name' => __('Turkish', 'wp-full-stripe-free')
            ],
            [
                'value' => 'vi',
                'name' => __('Vietnamese', 'wp-full-stripe-free')
            ],
            [
                'value' => 'zh',
                'name' => __('Simplified Chinese', 'wp-full-stripe-free')
            ],
            [
                'value' => 'zh-HK',
                'name' => __('Chinese Traditional (Hong Kong)', 'wp-full-stripe-free')
            ],
            [
                'value' => 'zh-TW',
                'name' => __('Chinese Traditional (Taiwan)', 'wp-full-stripe-free')
            ]
        ];
    }

    public static function getCheckoutLanguageCodes() {
        $languages = MM_WPFS_Languages::getCheckoutLanguages();
        $languageCodes = [];

        foreach ($languages as $language) {
            array_push($languageCodes, $language['value']);
        }

        return $languageCodes;
    }

    /**
     * Creates an array of locales/languages supported by Stripe Elements.
     *
     * @return array list of locales/languages
     */
    public static function getStripeElementsLanguages() {
        return [
            [
                'value' => 'ar',
                'name' => __('Arabic', 'wp-full-stripe-free')
            ],
            [
                'value' => 'bg',
                'name' => __('Bulgarian', 'wp-full-stripe-free')
            ],
            [
                'value' => 'cs',
                'name' => __('Czech', 'wp-full-stripe-free')
            ],
            [
                'value' => 'da',
                'name' => __('Danish', 'wp-full-stripe-free')
            ],
            [
                'value' => 'de',
                'name' => __('German', 'wp-full-stripe-free')
            ],
            [
                'value' => 'el',
                'name' => __('Greek', 'wp-full-stripe-free')
            ],
            [
                'value' => 'en',
                'name' => __('English', 'wp-full-stripe-free')
            ],
            [
                'value' => 'en-GB',
                'name' => __('English (United Kingdom)', 'wp-full-stripe-free')
            ],
            [
                'value' => 'es',
                'name' => __('Spanish (Spain)', 'wp-full-stripe-free')
            ],
            [
                'value' => 'es-419',
                'name' => __('Spanish (Latin America)', 'wp-full-stripe-free')
            ],
            [
                'value' => 'et',
                'name' => __('Estonian', 'wp-full-stripe-free')
            ],
            [
                'value' => 'fi',
                'name' => __('Finnish', 'wp-full-stripe-free')
            ],
            [
                'value' => 'fil',
                'name' => __('Filipino', 'wp-full-stripe-free')
            ],
            [
                'value' => 'fr',
                'name' => __('French (France)', 'wp-full-stripe-free')
            ],
            [
                'value' => 'fr-CA',
                'name' => __('French (Canada)', 'wp-full-stripe-free')
            ],
            [
                'value' => 'he',
                'name' => __('Hebrew', 'wp-full-stripe-free')
            ],
            [
                'value' => 'hr',
                'name' => __('Croatian', 'wp-full-stripe-free')
            ],
            [
                'value' => 'hu',
                'name' => __('Hungarian', 'wp-full-stripe-free')
            ],
            [
                'value' => 'id',
                'name' => __('Indonesian', 'wp-full-stripe-free')
            ],
            [
                'value' => 'it',
                'name' => __('Italian', 'wp-full-stripe-free')
            ],
            [
                'value' => 'ja',
                'name' => __('Japanese', 'wp-full-stripe-free')
            ],
            [
                'value' => 'ko',
                'name' => __('Korean', 'wp-full-stripe-free')
            ],
            [
                'value' => 'lv',
                'name' => __('Lithuanian', 'wp-full-stripe-free')
            ],
            [
                'value' => 'lt',
                'name' => __('Latvian', 'wp-full-stripe-free')
            ],
            [
                'value' => 'ms',
                'name' => __('Malay', 'wp-full-stripe-free')
            ],
            [
                'value' => 'mt',
                'name' => __('Maltese', 'wp-full-stripe-free')
            ],
            [
                'value' => 'nb',
                'name' => __('Norwegian Bokmål', 'wp-full-stripe-free')
            ],
            [
                'value' => 'nl',
                'name' => __('Dutch', 'wp-full-stripe-free')
            ],
            [
                'value' => 'pl',
                'name' => __('Polish', 'wp-full-stripe-free')
            ],
            [
                'value' => 'pt',
                'name' => __('Portuguese', 'wp-full-stripe-free')
            ],
            [
                'value' => 'pt-BR',
                'name' => __('Portuguese (Brazil)', 'wp-full-stripe-free')
            ],
            [
                'value' => 'ro',
                'name' => __('Romanian', 'wp-full-stripe-free')
            ],
            [
                'value' => 'ru',
                'name' => __('Russian', 'wp-full-stripe-free')
            ],
            [
                'value' => 'sk',
                'name' => __('Slovak', 'wp-full-stripe-free')
            ],
            [
                'value' => 'sl',
                'name' => __('Slovenian', 'wp-full-stripe-free')
            ],
            [
                'value' => 'sv',
                'name' => __('Swedish', 'wp-full-stripe-free')
            ],
            [
                'value' => 'th',
                'name' => __('Thai', 'wp-full-stripe-free')
            ],
            [
                'value' => 'tr',
                'name' => __('Turkish', 'wp-full-stripe-free')
            ],
            [
                'value' => 'vi',
                'name' => __('Vietnamese', 'wp-full-stripe-free')
            ],
            [
                'value' => 'zh',
                'name' => __('Simplified Chinese', 'wp-full-stripe-free')
            ],
            [
                'value' => 'zh-HK',
                'name' => __('Chinese Traditional (Hong Kong)', 'wp-full-stripe-free')
            ],
            [
                'value' => 'zh-TW',
                'name' => __('Chinese Traditional (Taiwan)', 'wp-full-stripe-free')
            ]
        ];
    }

    public static function getStripeElementsLanguageCodes() {
        $languages = self::getStripeElementsLanguages();
        $languageCodes = [];

        foreach ($languages as $language) {
            array_push($languageCodes, $language['value']);
        }

        return $languageCodes;
    }
}
