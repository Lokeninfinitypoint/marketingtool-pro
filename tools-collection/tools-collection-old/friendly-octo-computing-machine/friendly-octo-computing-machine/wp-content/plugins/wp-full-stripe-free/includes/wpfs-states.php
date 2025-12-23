<?php

class MM_WPFS_States {

    public static function getStateByCode( $stateCode) {
        if (isset($stateCode)) {
            $availableStates = self::getAvailableStates();
            if (isset($availableStates) && array_key_exists($stateCode, $availableStates)) {
                return $availableStates[$stateCode];
            }
        }

        return null;
    }

    public static function getStateCodeByName( $stateName ) {
        $result = null;

        foreach ( self::getAvailableStates() as $code => $state ) {
            if ( $state['name'] == $stateName ) {
                $result = $code;
                break;
            }
        }

        return $result;
    }

    /**
     * @param $stateCode
     *
     * @return null|string
     */
    public static function getStateNameFor( $stateCode ) {
        if (isset($stateCode)) {
            $availableStates = self::getAvailableStates();
            if (isset($availableStates) && array_key_exists($stateCode, $availableStates)) {
                $countryName = $availableStates[$stateCode]['name'];
            } else {
                $countryName = strtoupper($stateCode);
            }

            return $countryName;
        }

        return null;
    }

    /**
     * @return array ISO 3166-2 list of subdivision codes, only in the United States
     * The codes are without country prefix. For example, “NY” for New York, United States.
     */
    public static function getAvailableStates() {
        $states = [
            'AL' => [
                'code'      => 'AL',
                'name'      => 'Alabama',
                'category'  => 'state'
            ],
            'AK' => [
                'code'      => 'AK',
                'name'      => 'Alaska',
                'category'  => 'state'
            ],
            'AZ' => [
                'code'      => 'AZ',
                'name'      => 'Arizona',
                'category'  => 'state'
            ],
            'AR' => [
                'code'      => 'AR',
                'name'      => 'Arkansas',
                'category'  => 'state'
            ],
            'CA' => [
                'code'      => 'CA',
                'name'      => 'California',
                'category'  => 'state'
            ],
            'CO' => [
                'code'      => 'CO',
                'name'      => 'Colorado',
                'category'  => 'state'
            ],
            'CT' => [
                'code'      => 'CT',
                'name'      => 'Connecticut',
                'category'  => 'state'
            ],
            'DC' => [
                'code'      => 'DC',
                'name'      => 'Washinton DC',
                'category'  => 'district'
            ],
            'DE' => [
                'code'      => 'DE',
                'name'      => 'Delaware',
                'category'  => 'state'
            ],
            'FL' => [
                'code'      => 'FL',
                'name'      => 'Florida',
                'category'  => 'state'
            ],
            'GA' => [
                'code'      => 'GA',
                'name'      => 'Georgia',
                'category'  => 'state'
            ],
            'HI' => [
                'code'      => 'HI',
                'name'      => 'Hawai',
                'category'  => 'state'
            ],
            'ID' => [
                'code'      => 'ID',
                'name'      => 'Idaho',
                'category'  => 'state'
            ],
            'IL' => [
                'code'      => 'IL',
                'name'      => 'Illinois',
                'category'  => 'state'
            ],
            'IN' => [
                'code'      => 'IN',
                'name'      => 'Indiana',
                'category'  => 'state'
            ],
            'IA' => [
                'code'      => 'IA',
                'name'      => 'Iowa',
                'category'  => 'state'
            ],
            'KS' => [
                'code'      => 'KS',
                'name'      => 'Kansas',
                'category'  => 'state'
            ],
            'KY' => [
                'code'      => 'KY',
                'name'      => 'Kentucky',
                'category'  => 'state'
            ],
            'LA' => [
                'code'      => 'LA',
                'name'      => 'Louisiana',
                'category'  => 'state'
            ],
            'ME' => [
                'code'      => 'ME',
                'name'      => 'Maine',
                'category'  => 'state'
            ],
            'MD' => [
                'code'      => 'MD',
                'name'      => 'Maryland',
                'category'  => 'state'
            ],
            'MA' => [
                'code'      => 'MA',
                'name'      => 'Massachusetts',
                'category'  => 'state'
            ],
            'MI' => [
                'code'      => 'MI',
                'name'      => 'Michigan',
                'category'  => 'state'
            ],
            'MN' => [
                'code'      => 'MN',
                'name'      => 'Minnesota',
                'category'  => 'state'
            ],
            'MS' => [
                'code'      => 'MS',
                'name'      => 'Mississippi',
                'category'  => 'state'
            ],
            'MO' => [
                'code'      => 'MO',
                'name'      => 'Missouri',
                'category'  => 'state'
            ],
            'MT' => [
                'code'      => 'MT',
                'name'      => 'Montana',
                'category'  => 'state'
            ],
            'NE' => [
                'code'      => 'NE',
                'name'      => 'Nebraska',
                'category'  => 'state'
            ],
            'NV' => [
                'code'      => 'NV',
                'name'      => 'Nevada',
                'category'  => 'state'
            ],
            'NH' => [
                'code'      => 'NH',
                'name'      => 'New Hampshire',
                'category'  => 'state'
            ],
            'NJ' => [
                'code'      => 'NJ',
                'name'      => 'New Jersey',
                'category'  => 'state'
            ],
            'NM' => [
                'code'      => 'NM',
                'name'      => 'New Mexico',
                'category'  => 'state'
            ],
            'NY' => [
                'code'      => 'NY',
                'name'      => 'New York',
                'category'  => 'state'
            ],
            'NC' => [
                'code'      => 'NC',
                'name'      => 'North Carolina',
                'category'  => 'state'
            ],
            'ND' => [
                'code'      => 'ND',
                'name'      => 'North Dakota',
                'category'  => 'state'
            ],
            'OH' => [
                'code'      => 'OH',
                'name'      => 'Ohio',
                'category'  => 'state'
            ],
            'OK' => [
                'code'      => 'OK',
                'name'      => 'Oklahoma',
                'category'  => 'state'
            ],
            'OR' => [
                'code'      => 'OR',
                'name'      => 'Oregon',
                'category'  => 'state'
            ],
            'PA' => [
                'code'      => 'PA',
                'name'      => 'Pennsylvania',
                'category'  => 'state'
            ],
            'RI' => [
                'code'      => 'RI',
                'name'      => 'Rhode Island',
                'category'  => 'state'
            ],
            'SC' => [
                'code'      => 'SC',
                'name'      => 'South Carolina',
                'category'  => 'state'
            ],
            'SD' => [
                'code'      => 'SD',
                'name'      => 'South Dakota',
                'category'  => 'state'
            ],
            'TN' => [
                'code'      => 'TN',
                'name'      => 'Tennessee',
                'category'  => 'state'
            ],
            'TX' => [
                'code'      => 'TX',
                'name'      => 'Texas',
                'category'  => 'state'
            ],
            'UT' => [
                'code'      => 'UT',
                'name'      => 'Utah',
                'category'  => 'state'
            ],
            'VT' => [
                'code'      => 'VT',
                'name'      => 'Vermont',
                'category'  => 'state'
            ],
            'VA' => [
                'code'      => 'VA',
                'name'      => 'Virginia',
                'category'  => 'state'
            ],
            'WA' => [
                'code'      => 'WA',
                'name'      => 'Washington',
                'category'  => 'state'
            ],
            'WV' => [
                'code'      => 'WV',
                'name'      => 'West Virginia',
                'category'  => 'state'
            ],
            'WI' => [
                'code'      => 'WI',
                'name'      => 'Wisconsin',
                'category'  => 'state'
            ],
            'WY' => [
                'code'      => 'WY',
                'name'      => 'Wyoming',
                'category'  => 'state'
            ],
        ];

        return $states;
    }
}
