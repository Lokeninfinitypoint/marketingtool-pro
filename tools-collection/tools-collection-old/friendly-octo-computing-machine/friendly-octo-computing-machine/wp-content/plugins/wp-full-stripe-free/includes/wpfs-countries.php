<?php

/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2018.05.31.
 * Time: 09:29
 */
class MM_WPFS_Countries {

    public static function getCountryByName( $countryName) {
        if (isset($countryName)) {
            $availableCountries = self::getAvailableCountries();
            if (isset($availableCountries)) {
                $theCountry = null;
                foreach ($availableCountries as $country) {
                    if ($country['name'] === $countryName) {
                        $theCountry = $country;
                        break;
                    }
                }

                return $theCountry;
            }
        }

        return null;
    }

    /**
     * @return string[]
     */
    public static function getUnsupportedCheckoutCountryCodes() {
        $result = ['AS', 'CX', 'CC', 'CU', 'HM', 'IR', 'KP', 'MH', 'FM', 'NF', 'MP', 'PW', 'SD', 'SY', 'UM', 'VI'];

        return $result;
    }

    /**
     * @return array
     */
    public static function getAvailableCountryCodes() {
        return array_keys( self::getAvailableCountries() );
    }

    /**
     * @param $countryCodes
     * @return array
     */
    public static function getCountriesByCode( $countryCodes ) {
        $countries = self::getAvailableCountries();

        if ( count( $countryCodes ) === count( $countries ) ) {
            return $countries;
        }

        $result = [];
        foreach ( $countryCodes as $countryCode ) {
            $result[ $countryCode ] = $countries[ $countryCode ];
        }

        return $result;
    }

    /**
     * @return array ISO 3166-1 list of countries
     */
    public static function getAvailableCountries() {
        $countries = [
            'AF' => [
                'name' => 'Afghanistan',
                'alpha-2' => 'AF',
                'alpha-3' => 'AFG',
                'code' => '004',
                'iso-3166-2' => 'ISO 3166-2:AF',
                'usePostCode' => true
            ],
            'AX' => [
                'name' => 'Åland Islands',
                'alpha-2' => 'AX',
                'alpha-3' => 'ALA',
                'code' => '248',
                'iso-3166-2' => 'ISO 3166-2:AX',
                'usePostCode' => true
            ],
            'AL' => [
                'name' => 'Albania',
                'alpha-2' => 'AL',
                'alpha-3' => 'ALB',
                'code' => '008',
                'iso-3166-2' => 'ISO 3166-2:AL',
                'usePostCode' => true
            ],
            'DZ' => [
                'name' => 'Algeria',
                'alpha-2' => 'DZ',
                'alpha-3' => 'DZA',
                'code' => '012',
                'iso-3166-2' => 'ISO 3166-2:DZ',
                'usePostCode' => true
            ],
            'AS' => [
                'name' => 'American Samoa',
                'alpha-2' => 'AS',
                'alpha-3' => 'ASM',
                'code' => '016',
                'iso-3166-2' => 'ISO 3166-2:AS',
                'usePostCode' => true
            ],
            'AD' => [
                'name' => 'Andorra',
                'alpha-2' => 'AD',
                'alpha-3' => 'AND',
                'code' => '020',
                'iso-3166-2' => 'ISO 3166-2:AD',
                'usePostCode' => true
            ],
            'AO' => [
                'name' => 'Angola',
                'alpha-2' => 'AO',
                'alpha-3' => 'AGO',
                'code' => '024',
                'iso-3166-2' => 'ISO 3166-2:AO',
                'usePostCode' => false
            ],
            'AI' => [
                'name' => 'Anguilla',
                'alpha-2' => 'AI',
                'alpha-3' => 'AIA',
                'code' => '660',
                'iso-3166-2' => 'ISO 3166-2:AI',
                'usePostCode' => true
            ],
            'AQ' => [
                'name' => 'Antarctica',
                'alpha-2' => 'AQ',
                'alpha-3' => 'ATA',
                'code' => '010',
                'iso-3166-2' => 'ISO 3166-2:AQ',
                'usePostCode' => true
            ],
            'AG' => [
                'name' => 'Antigua and Barbuda',
                'alpha-2' => 'AG',
                'alpha-3' => 'ATG',
                'code' => '028',
                'iso-3166-2' => 'ISO 3166-2:AG',
                'usePostCode' => false
            ],
            'AR' => [
                'name' => 'Argentina',
                'alpha-2' => 'AR',
                'alpha-3' => 'ARG',
                'code' => '032',
                'iso-3166-2' => 'ISO 3166-2:AR',
                'usePostCode' => true
            ],
            'AM' => [
                'name' => 'Armenia',
                'alpha-2' => 'AM',
                'alpha-3' => 'ARM',
                'code' => '051',
                'iso-3166-2' => 'ISO 3166-2:AM',
                'usePostCode' => true
            ],
            'AW' => [
                'name' => 'Aruba',
                'alpha-2' => 'AW',
                'alpha-3' => 'ABW',
                'code' => '533',
                'iso-3166-2' => 'ISO 3166-2:AW',
                'usePostCode' => false
            ],
            'AU' => [
                'name' => 'Australia',
                'alpha-2' => 'AU',
                'alpha-3' => 'AUS',
                'code' => '036',
                'iso-3166-2' => 'ISO 3166-2:AU',
                'usePostCode' => true
            ],
            'AT' => [
                'name' => 'Austria',
                'alpha-2' => 'AT',
                'alpha-3' => 'AUT',
                'code' => '040',
                'iso-3166-2' => 'ISO 3166-2:AT',
                'usePostCode' => true
            ],
            'AZ' => [
                'name' => 'Azerbaijan',
                'alpha-2' => 'AZ',
                'alpha-3' => 'AZE',
                'code' => '031',
                'iso-3166-2' => 'ISO 3166-2:AZ',
                'usePostCode' => true
            ],
            'BS' => [
                'name' => 'Bahamas',
                'alpha-2' => 'BS',
                'alpha-3' => 'BHS',
                'code' => '044',
                'iso-3166-2' => 'ISO 3166-2:BS',
                'usePostCode' => false
            ],
            'BH' => [
                'name' => 'Bahrain',
                'alpha-2' => 'BH',
                'alpha-3' => 'BHR',
                'code' => '048',
                'iso-3166-2' => 'ISO 3166-2:BH',
                'usePostCode' => true
            ],
            'BD' => [
                'name' => 'Bangladesh',
                'alpha-2' => 'BD',
                'alpha-3' => 'BGD',
                'code' => '050',
                'iso-3166-2' => 'ISO 3166-2:BD',
                'usePostCode' => true
            ],
            'BB' => [
                'name' => 'Barbados',
                'alpha-2' => 'BB',
                'alpha-3' => 'BRB',
                'code' => '052',
                'iso-3166-2' => 'ISO 3166-2:BB',
                'usePostCode' => true
            ],
            'BY' => [
                'name' => 'Belarus',
                'alpha-2' => 'BY',
                'alpha-3' => 'BLR',
                'code' => '112',
                'iso-3166-2' => 'ISO 3166-2:BY',
                'usePostCode' => true
            ],
            'BE' => [
                'name' => 'Belgium',
                'alpha-2' => 'BE',
                'alpha-3' => 'BEL',
                'code' => '056',
                'iso-3166-2' => 'ISO 3166-2:BE',
                'usePostCode' => true
            ],
            'BZ' => [
                'name' => 'Belize',
                'alpha-2' => 'BZ',
                'alpha-3' => 'BLZ',
                'code' => '084',
                'iso-3166-2' => 'ISO 3166-2:BZ',
                'usePostCode' => false
            ],
            'BJ' => [
                'name' => 'Benin',
                'alpha-2' => 'BJ',
                'alpha-3' => 'BEN',
                'code' => '204',
                'iso-3166-2' => 'ISO 3166-2:BJ',
                'usePostCode' => false
            ],
            'BM' => [
                'name' => 'Bermuda',
                'alpha-2' => 'BM',
                'alpha-3' => 'BMU',
                'code' => '060',
                'iso-3166-2' => 'ISO 3166-2:BM',
                'usePostCode' => true
            ],
            'BT' => [
                'name' => 'Bhutan',
                'alpha-2' => 'BT',
                'alpha-3' => 'BTN',
                'code' => '064',
                'iso-3166-2' => 'ISO 3166-2:BT',
                'usePostCode' => true
            ],
            'BO' => [
                'name' => 'Bolivia',
                'alpha-2' => 'BO',
                'alpha-3' => 'BOL',
                'code' => '068',
                'iso-3166-2' => 'ISO 3166-2:BO',
                'usePostCode' => false
            ],
            'BQ' => [
                'name' => 'Bonaire, Sint Eustatius and Saba',
                'alpha-2' => 'BQ',
                'alpha-3' => 'BES',
                'code' => '535',
                'iso-3166-2' => 'ISO 3166-2:BQ',
                'usePostCode' => false
            ],
            'BA' => [
                'name' => 'Bosnia and Herzegovina',
                'alpha-2' => 'BA',
                'alpha-3' => 'BIH',
                'code' => '070',
                'iso-3166-2' => 'ISO 3166-2:BA',
                'usePostCode' => true
            ],
            'BW' => [
                'name' => 'Botswana',
                'alpha-2' => 'BW',
                'alpha-3' => 'BWA',
                'code' => '072',
                'iso-3166-2' => 'ISO 3166-2:BW',
                'usePostCode' => false
            ],
            'BV' => [
                'name' => 'Bouvet Island',
                'alpha-2' => 'BV',
                'alpha-3' => 'BVT',
                'code' => '074',
                'iso-3166-2' => 'ISO 3166-2:BV',
                'usePostCode' => true
            ],
            'BR' => [
                'name' => 'Brazil',
                'alpha-2' => 'BR',
                'alpha-3' => 'BRA',
                'code' => '076',
                'iso-3166-2' => 'ISO 3166-2:BR',
                'usePostCode' => true
            ],
            'IO' => [
                'name' => 'British Indian Ocean Territory',
                'alpha-2' => 'IO',
                'alpha-3' => 'IOT',
                'code' => '086',
                'iso-3166-2' => 'ISO 3166-2:IO',
                'usePostCode' => true
            ],
            'BN' => [
                'name' => 'Brunei',
                'alpha-2' => 'BN',
                'alpha-3' => 'BRN',
                'code' => '096',
                'iso-3166-2' => 'ISO 3166-2:BN',
                'usePostCode' => true
            ],
            'BG' => [
                'name' => 'Bulgaria',
                'alpha-2' => 'BG',
                'alpha-3' => 'BGR',
                'code' => '100',
                'iso-3166-2' => 'ISO 3166-2:BG',
                'usePostCode' => true
            ],
            'BF' => [
                'name' => 'Burkina Faso',
                'alpha-2' => 'BF',
                'alpha-3' => 'BFA',
                'code' => '854',
                'iso-3166-2' => 'ISO 3166-2:BF',
                'usePostCode' => false
            ],
            'BI' => [
                'name' => 'Burundi',
                'alpha-2' => 'BI',
                'alpha-3' => 'BDI',
                'code' => '108',
                'iso-3166-2' => 'ISO 3166-2:BI',
                'usePostCode' => false
            ],
            'CV' => [
                'name' => 'Cabo Verde',
                'alpha-2' => 'CV',
                'alpha-3' => 'CPV',
                'code' => '132',
                'iso-3166-2' => 'ISO 3166-2:CV',
                'usePostCode' => true
            ],
            'KH' => [
                'name' => 'Cambodia',
                'alpha-2' => 'KH',
                'alpha-3' => 'KHM',
                'code' => '116',
                'iso-3166-2' => 'ISO 3166-2:KH',
                'usePostCode' => true
            ],
            'CM' => [
                'name' => 'Cameroon',
                'alpha-2' => 'CM',
                'alpha-3' => 'CMR',
                'code' => '120',
                'iso-3166-2' => 'ISO 3166-2:CM',
                'usePostCode' => false
            ],
            'CA' => [
                'name' => 'Canada',
                'alpha-2' => 'CA',
                'alpha-3' => 'CAN',
                'code' => '124',
                'iso-3166-2' => 'ISO 3166-2:CA',
                'usePostCode' => true
            ],
            'KY' => [
                'name' => 'Cayman Islands',
                'alpha-2' => 'KY',
                'alpha-3' => 'CYM',
                'code' => '136',
                'iso-3166-2' => 'ISO 3166-2:KY',
                'usePostCode' => true
            ],
            'CF' => [
                'name' => 'Central African Republic',
                'alpha-2' => 'CF',
                'alpha-3' => 'CAF',
                'code' => '140',
                'iso-3166-2' => 'ISO 3166-2:CF',
                'usePostCode' => false
            ],
            'TD' => [
                'name' => 'Chad',
                'alpha-2' => 'TD',
                'alpha-3' => 'TCD',
                'code' => '148',
                'iso-3166-2' => 'ISO 3166-2:TD',
                'usePostCode' => true
            ],
            'CL' => [
                'name' => 'Chile',
                'alpha-2' => 'CL',
                'alpha-3' => 'CHL',
                'code' => '152',
                'iso-3166-2' => 'ISO 3166-2:CL',
                'usePostCode' => true
            ],
            'CN' => [
                'name' => 'China',
                'alpha-2' => 'CN',
                'alpha-3' => 'CHN',
                'code' => '156',
                'iso-3166-2' => 'ISO 3166-2:CN',
                'usePostCode' => true
            ],
            'CX' => [
                'name' => 'Christmas Island',
                'alpha-2' => 'CX',
                'alpha-3' => 'CXR',
                'code' => '162',
                'iso-3166-2' => 'ISO 3166-2:CX',
                'usePostCode' => true
            ],
            'CC' => [
                'name' => 'Cocos (Keeling) Islands',
                'alpha-2' => 'CC',
                'alpha-3' => 'CCK',
                'code' => '166',
                'iso-3166-2' => 'ISO 3166-2:CC',
                'usePostCode' => true
            ],
            'CO' => [
                'name' => 'Colombia',
                'alpha-2' => 'CO',
                'alpha-3' => 'COL',
                'code' => '170',
                'iso-3166-2' => 'ISO 3166-2:CO',
                'usePostCode' => true
            ],
            'KM' => [
                'name' => 'Comoros',
                'alpha-2' => 'KM',
                'alpha-3' => 'COM',
                'code' => '174',
                'iso-3166-2' => 'ISO 3166-2:KM',
                'usePostCode' => false
            ],
            'CG' => [
                'name' => 'Congo',
                'alpha-2' => 'CG',
                'alpha-3' => 'COG',
                'code' => '178',
                'iso-3166-2' => 'ISO 3166-2:CG',
                'usePostCode' => false
            ],
            'CD' => [
                'name' => 'Congo (Democratic Republic)',
                'alpha-2' => 'CD',
                'alpha-3' => 'COD',
                'code' => '180',
                'iso-3166-2' => 'ISO 3166-2:CD',
                'usePostCode' => false
            ],
            'CK' => [
                'name' => 'Cook Islands',
                'alpha-2' => 'CK',
                'alpha-3' => 'COK',
                'code' => '184',
                'iso-3166-2' => 'ISO 3166-2:CK',
                'usePostCode' => false
            ],
            'CR' => [
                'name' => 'Costa Rica',
                'alpha-2' => 'CR',
                'alpha-3' => 'CRI',
                'code' => '188',
                'iso-3166-2' => 'ISO 3166-2:CR',
                'usePostCode' => true
            ],
            'CI' => [
                'name' => 'Côte d\'Ivoire',
                'alpha-2' => 'CI',
                'alpha-3' => 'CIV',
                'code' => '384',
                'iso-3166-2' => 'ISO 3166-2:CI',
                'usePostCode' => false
            ],
            'HR' => [
                'name' => 'Croatia',
                'alpha-2' => 'HR',
                'alpha-3' => 'HRV',
                'code' => '191',
                'iso-3166-2' => 'ISO 3166-2:HR',
                'usePostCode' => true
            ],
            'CU' => [
                'name' => 'Cuba',
                'alpha-2' => 'CU',
                'alpha-3' => 'CUB',
                'code' => '192',
                'iso-3166-2' => 'ISO 3166-2:CU',
                'usePostCode' => true
            ],
            'CW' => [
                'name' => 'Curaçao',
                'alpha-2' => 'CW',
                'alpha-3' => 'CUW',
                'code' => '531',
                'iso-3166-2' => 'ISO 3166-2:CW',
                'usePostCode' => false
            ],
            'CY' => [
                'name' => 'Cyprus',
                'alpha-2' => 'CY',
                'alpha-3' => 'CYP',
                'code' => '196',
                'iso-3166-2' => 'ISO 3166-2:CY',
                'usePostCode' => true
            ],
            'CZ' => [
                'name' => 'Czech Republic',
                'alpha-2' => 'CZ',
                'alpha-3' => 'CZE',
                'code' => '203',
                'iso-3166-2' => 'ISO 3166-2:CZ',
                'usePostCode' => true
            ],
            'DK' => [
                'name' => 'Denmark',
                'alpha-2' => 'DK',
                'alpha-3' => 'DNK',
                'code' => '208',
                'iso-3166-2' => 'ISO 3166-2:DK',
                'usePostCode' => true
            ],
            'DJ' => [
                'name' => 'Djibouti',
                'alpha-2' => 'DJ',
                'alpha-3' => 'DJI',
                'code' => '262',
                'iso-3166-2' => 'ISO 3166-2:DJ',
                'usePostCode' => false
            ],
            'DM' => [
                'name' => 'Dominica',
                'alpha-2' => 'DM',
                'alpha-3' => 'DMA',
                'code' => '212',
                'iso-3166-2' => 'ISO 3166-2:DM',
                'usePostCode' => false
            ],
            'DO' => [
                'name' => 'Dominican Republic',
                'alpha-2' => 'DO',
                'alpha-3' => 'DOM',
                'code' => '214',
                'iso-3166-2' => 'ISO 3166-2:DO',
                'usePostCode' => true
            ],
            'EC' => [
                'name' => 'Ecuador',
                'alpha-2' => 'EC',
                'alpha-3' => 'ECU',
                'code' => '218',
                'iso-3166-2' => 'ISO 3166-2:EC',
                'usePostCode' => true
            ],
            'EG' => [
                'name' => 'Egypt',
                'alpha-2' => 'EG',
                'alpha-3' => 'EGY',
                'code' => '818',
                'iso-3166-2' => 'ISO 3166-2:EG',
                'usePostCode' => true
            ],
            'SV' => [
                'name' => 'El Salvador',
                'alpha-2' => 'SV',
                'alpha-3' => 'SLV',
                'code' => '222',
                'iso-3166-2' => 'ISO 3166-2:SV',
                'usePostCode' => true
            ],
            'GQ' => [
                'name' => 'Equatorial Guinea',
                'alpha-2' => 'GQ',
                'alpha-3' => 'GNQ',
                'code' => '226',
                'iso-3166-2' => 'ISO 3166-2:GQ',
                'usePostCode' => false
            ],
            'ER' => [
                'name' => 'Eritrea',
                'alpha-2' => 'ER',
                'alpha-3' => 'ERI',
                'code' => '232',
                'iso-3166-2' => 'ISO 3166-2:ER',
                'usePostCode' => false
            ],
            'EE' => [
                'name' => 'Estonia',
                'alpha-2' => 'EE',
                'alpha-3' => 'EST',
                'code' => '233',
                'iso-3166-2' => 'ISO 3166-2:EE',
                'usePostCode' => true
            ],
            'ET' => [
                'name' => 'Ethiopia',
                'alpha-2' => 'ET',
                'alpha-3' => 'ETH',
                'code' => '231',
                'iso-3166-2' => 'ISO 3166-2:ET',
                'usePostCode' => true
            ],
            'FK' => [
                'name' => 'Falkland Islands (Malvinas)',
                'alpha-2' => 'FK',
                'alpha-3' => 'FLK',
                'code' => '238',
                'iso-3166-2' => 'ISO 3166-2:FK',
                'usePostCode' => true
            ],
            'FO' => [
                'name' => 'Faroe Islands',
                'alpha-2' => 'FO',
                'alpha-3' => 'FRO',
                'code' => '234',
                'iso-3166-2' => 'ISO 3166-2:FO',
                'usePostCode' => true
            ],
            'FJ' => [
                'name' => 'Fiji',
                'alpha-2' => 'FJ',
                'alpha-3' => 'FJI',
                'code' => '242',
                'iso-3166-2' => 'ISO 3166-2:FJ',
                'usePostCode' => false
            ],
            'FI' => [
                'name' => 'Finland',
                'alpha-2' => 'FI',
                'alpha-3' => 'FIN',
                'code' => '246',
                'iso-3166-2' => 'ISO 3166-2:FI',
                'usePostCode' => true
            ],
            'FR' => [
                'name' => 'France',
                'alpha-2' => 'FR',
                'alpha-3' => 'FRA',
                'code' => '250',
                'iso-3166-2' => 'ISO 3166-2:FR',
                'usePostCode' => true
            ],
            'GF' => [
                'name' => 'French Guiana',
                'alpha-2' => 'GF',
                'alpha-3' => 'GUF',
                'code' => '254',
                'iso-3166-2' => 'ISO 3166-2:GF',
                'usePostCode' => true
            ],
            'PF' => [
                'name' => 'French Polynesia',
                'alpha-2' => 'PF',
                'alpha-3' => 'PYF',
                'code' => '258',
                'iso-3166-2' => 'ISO 3166-2:PF',
                'usePostCode' => true
            ],
            'TF' => [
                'name' => 'French Southern Territories',
                'alpha-2' => 'TF',
                'alpha-3' => 'ATF',
                'code' => '260',
                'iso-3166-2' => 'ISO 3166-2:TF',
                'usePostCode' => false
            ],
            'GA' => [
                'name' => 'Gabon',
                'alpha-2' => 'GA',
                'alpha-3' => 'GAB',
                'code' => '266',
                'iso-3166-2' => 'ISO 3166-2:GA',
                'usePostCode' => false
            ],
            'GM' => [
                'name' => 'Gambia',
                'alpha-2' => 'GM',
                'alpha-3' => 'GMB',
                'code' => '270',
                'iso-3166-2' => 'ISO 3166-2:GM',
                'usePostCode' => false
            ],
            'GE' => [
                'name' => 'Georgia',
                'alpha-2' => 'GE',
                'alpha-3' => 'GEO',
                'code' => '268',
                'iso-3166-2' => 'ISO 3166-2:GE',
                'usePostCode' => true
            ],
            'DE' => [
                'name' => 'Germany',
                'alpha-2' => 'DE',
                'alpha-3' => 'DEU',
                'code' => '276',
                'iso-3166-2' => 'ISO 3166-2:DE',
                'usePostCode' => true
            ],
            'GH' => [
                'name' => 'Ghana',
                'alpha-2' => 'GH',
                'alpha-3' => 'GHA',
                'code' => '288',
                'iso-3166-2' => 'ISO 3166-2:GH',
                'usePostCode' => false
            ],
            'GI' => [
                'name' => 'Gibraltar',
                'alpha-2' => 'GI',
                'alpha-3' => 'GIB',
                'code' => '292',
                'iso-3166-2' => 'ISO 3166-2:GI',
                'usePostCode' => true
            ],
            'GR' => [
                'name' => 'Greece',
                'alpha-2' => 'GR',
                'alpha-3' => 'GRC',
                'code' => '300',
                'iso-3166-2' => 'ISO 3166-2:GR',
                'usePostCode' => true
            ],
            'GL' => [
                'name' => 'Greenland',
                'alpha-2' => 'GL',
                'alpha-3' => 'GRL',
                'code' => '304',
                'iso-3166-2' => 'ISO 3166-2:GL',
                'usePostCode' => true
            ],
            'GD' => [
                'name' => 'Grenada',
                'alpha-2' => 'GD',
                'alpha-3' => 'GRD',
                'code' => '308',
                'iso-3166-2' => 'ISO 3166-2:GD',
                'usePostCode' => false
            ],
            'GP' => [
                'name' => 'Guadeloupe',
                'alpha-2' => 'GP',
                'alpha-3' => 'GLP',
                'code' => '312',
                'iso-3166-2' => 'ISO 3166-2:GP',
                'usePostCode' => true
            ],
            'GU' => [
                'name' => 'Guam',
                'alpha-2' => 'GU',
                'alpha-3' => 'GUM',
                'code' => '316',
                'iso-3166-2' => 'ISO 3166-2:GU',
                'usePostCode' => true
            ],
            'GT' => [
                'name' => 'Guatemala',
                'alpha-2' => 'GT',
                'alpha-3' => 'GTM',
                'code' => '320',
                'iso-3166-2' => 'ISO 3166-2:GT',
                'usePostCode' => true
            ],
            'GG' => [
                'name' => 'Guernsey',
                'alpha-2' => 'GG',
                'alpha-3' => 'GGY',
                'code' => '831',
                'iso-3166-2' => 'ISO 3166-2:GG',
                'usePostCode' => true
            ],
            'GN' => [
                'name' => 'Guinea',
                'alpha-2' => 'GN',
                'alpha-3' => 'GIN',
                'code' => '324',
                'iso-3166-2' => 'ISO 3166-2:GN',
                'usePostCode' => true
            ],
            'GW' => [
                'name' => 'Guinea-Bissau',
                'alpha-2' => 'GW',
                'alpha-3' => 'GNB',
                'code' => '624',
                'iso-3166-2' => 'ISO 3166-2:GW',
                'usePostCode' => true
            ],
            'GY' => [
                'name' => 'Guyana',
                'alpha-2' => 'GY',
                'alpha-3' => 'GUY',
                'code' => '328',
                'iso-3166-2' => 'ISO 3166-2:GY',
                'usePostCode' => false
            ],
            'HT' => [
                'name' => 'Haiti',
                'alpha-2' => 'HT',
                'alpha-3' => 'HTI',
                'code' => '332',
                'iso-3166-2' => 'ISO 3166-2:HT',
                'usePostCode' => true
            ],
            'HM' => [
                'name' => 'Heard Island and McDonald Islands',
                'alpha-2' => 'HM',
                'alpha-3' => 'HMD',
                'code' => '334',
                'iso-3166-2' => 'ISO 3166-2:HM',
                'usePostCode' => false
            ],
            'VA' => [
                'name' => 'Holy See',
                'alpha-2' => 'VA',
                'alpha-3' => 'VAT',
                'code' => '336',
                'iso-3166-2' => 'ISO 3166-2:VA',
                'usePostCode' => true
            ],
            'HN' => [
                'name' => 'Honduras',
                'alpha-2' => 'HN',
                'alpha-3' => 'HND',
                'code' => '340',
                'iso-3166-2' => 'ISO 3166-2:HN',
                'usePostCode' => true
            ],
            'HK' => [
                'name' => 'Hong Kong',
                'alpha-2' => 'HK',
                'alpha-3' => 'HKG',
                'code' => '344',
                'iso-3166-2' => 'ISO 3166-2:HK',
                'usePostCode' => false
            ],
            'HU' => [
                'name' => 'Hungary',
                'alpha-2' => 'HU',
                'alpha-3' => 'HUN',
                'code' => '348',
                'iso-3166-2' => 'ISO 3166-2:HU',
                'usePostCode' => true
            ],
            'IS' => [
                'name' => 'Iceland',
                'alpha-2' => 'IS',
                'alpha-3' => 'ISL',
                'code' => '352',
                'iso-3166-2' => 'ISO 3166-2:IS',
                'usePostCode' => true
            ],
            'IN' => [
                'name' => 'India',
                'alpha-2' => 'IN',
                'alpha-3' => 'IND',
                'code' => '356',
                'iso-3166-2' => 'ISO 3166-2:IN',
                'usePostCode' => true
            ],
            'ID' => [
                'name' => 'Indonesia',
                'alpha-2' => 'ID',
                'alpha-3' => 'IDN',
                'code' => '360',
                'iso-3166-2' => 'ISO 3166-2:ID',
                'usePostCode' => true
            ],
            'IR' => [
                'name' => 'Iran',
                'alpha-2' => 'IR',
                'alpha-3' => 'IRN',
                'code' => '364',
                'iso-3166-2' => 'ISO 3166-2:IR',
                'usePostCode' => true
            ],
            'IQ' => [
                'name' => 'Iraq',
                'alpha-2' => 'IQ',
                'alpha-3' => 'IRQ',
                'code' => '368',
                'iso-3166-2' => 'ISO 3166-2:IQ',
                'usePostCode' => true
            ],
            'IE' => [
                'name' => 'Ireland',
                'alpha-2' => 'IE',
                'alpha-3' => 'IRL',
                'code' => '372',
                'iso-3166-2' => 'ISO 3166-2:IE',
                'usePostCode' => false
            ],
            'IM' => [
                'name' => 'Isle of Man',
                'alpha-2' => 'IM',
                'alpha-3' => 'IMN',
                'code' => '833',
                'iso-3166-2' => 'ISO 3166-2:IM',
                'usePostCode' => true
            ],
            'IL' => [
                'name' => 'Israel',
                'alpha-2' => 'IL',
                'alpha-3' => 'ISR',
                'code' => '376',
                'iso-3166-2' => 'ISO 3166-2:IL',
                'usePostCode' => true
            ],
            'IT' => [
                'name' => 'Italy',
                'alpha-2' => 'IT',
                'alpha-3' => 'ITA',
                'code' => '380',
                'iso-3166-2' => 'ISO 3166-2:IT',
                'usePostCode' => true
            ],
            'JM' => [
                'name' => 'Jamaica',
                'alpha-2' => 'JM',
                'alpha-3' => 'JAM',
                'code' => '388',
                'iso-3166-2' => 'ISO 3166-2:JM',
                'usePostCode' => false
            ],
            'JP' => [
                'name' => 'Japan',
                'alpha-2' => 'JP',
                'alpha-3' => 'JPN',
                'code' => '392',
                'iso-3166-2' => 'ISO 3166-2:JP',
                'usePostCode' => true
            ],
            'JE' => [
                'name' => 'Jersey',
                'alpha-2' => 'JE',
                'alpha-3' => 'JEY',
                'code' => '832',
                'iso-3166-2' => 'ISO 3166-2:JE',
                'usePostCode' => true
            ],
            'JO' => [
                'name' => 'Jordan',
                'alpha-2' => 'JO',
                'alpha-3' => 'JOR',
                'code' => '400',
                'iso-3166-2' => 'ISO 3166-2:JO',
                'usePostCode' => true
            ],
            'KZ' => [
                'name' => 'Kazakhstan',
                'alpha-2' => 'KZ',
                'alpha-3' => 'KAZ',
                'code' => '398',
                'iso-3166-2' => 'ISO 3166-2:KZ',
                'usePostCode' => true
            ],
            'KE' => [
                'name' => 'Kenya',
                'alpha-2' => 'KE',
                'alpha-3' => 'KEN',
                'code' => '404',
                'iso-3166-2' => 'ISO 3166-2:KE',
                'usePostCode' => false
            ],
            'KI' => [
                'name' => 'Kiribati',
                'alpha-2' => 'KI',
                'alpha-3' => 'KIR',
                'code' => '296',
                'iso-3166-2' => 'ISO 3166-2:KI',
                'usePostCode' => false
            ],
            'KP' => [
                'name' => 'Korea (Democratic People\'s Republic of)',
                'alpha-2' => 'KP',
                'alpha-3' => 'PRK',
                'code' => '408',
                'iso-3166-2' => 'ISO 3166-2:KP',
                'usePostCode' => false
            ],
            'KR' => [
                'name' => 'Korea (Republic of)',
                'alpha-2' => 'KR',
                'alpha-3' => 'KOR',
                'code' => '410',
                'iso-3166-2' => 'ISO 3166-2:KR',
                'usePostCode' => true
            ],
            'KW' => [
                'name' => 'Kuwait',
                'alpha-2' => 'KW',
                'alpha-3' => 'KWT',
                'code' => '414',
                'iso-3166-2' => 'ISO 3166-2:KW',
                'usePostCode' => true
            ],
            'KG' => [
                'name' => 'Kyrgyzstan',
                'alpha-2' => 'KG',
                'alpha-3' => 'KGZ',
                'code' => '417',
                'iso-3166-2' => 'ISO 3166-2:KG',
                'usePostCode' => true
            ],
            'LA' => [
                'name' => 'Laos',
                'alpha-2' => 'LA',
                'alpha-3' => 'LAO',
                'code' => '418',
                'iso-3166-2' => 'ISO 3166-2:LA',
                'usePostCode' => true
            ],
            'LV' => [
                'name' => 'Latvia',
                'alpha-2' => 'LV',
                'alpha-3' => 'LVA',
                'code' => '428',
                'iso-3166-2' => 'ISO 3166-2:LV',
                'usePostCode' => true
            ],
            'LB' => [
                'name' => 'Lebanon',
                'alpha-2' => 'LB',
                'alpha-3' => 'LBN',
                'code' => '422',
                'iso-3166-2' => 'ISO 3166-2:LB',
                'usePostCode' => true
            ],
            'LS' => [
                'name' => 'Lesotho',
                'alpha-2' => 'LS',
                'alpha-3' => 'LSO',
                'code' => '426',
                'iso-3166-2' => 'ISO 3166-2:LS',
                'usePostCode' => true
            ],
            'LR' => [
                'name' => 'Liberia',
                'alpha-2' => 'LR',
                'alpha-3' => 'LBR',
                'code' => '430',
                'iso-3166-2' => 'ISO 3166-2:LR',
                'usePostCode' => true
            ],
            'LY' => [
                'name' => 'Libya',
                'alpha-2' => 'LY',
                'alpha-3' => 'LBY',
                'code' => '434',
                'iso-3166-2' => 'ISO 3166-2:LY',
                'usePostCode' => false
            ],
            'LI' => [
                'name' => 'Liechtenstein',
                'alpha-2' => 'LI',
                'alpha-3' => 'LIE',
                'code' => '438',
                'iso-3166-2' => 'ISO 3166-2:LI',
                'usePostCode' => true
            ],
            'LT' => [
                'name' => 'Lithuania',
                'alpha-2' => 'LT',
                'alpha-3' => 'LTU',
                'code' => '440',
                'iso-3166-2' => 'ISO 3166-2:LT',
                'usePostCode' => true
            ],
            'LU' => [
                'name' => 'Luxembourg',
                'alpha-2' => 'LU',
                'alpha-3' => 'LUX',
                'code' => '442',
                'iso-3166-2' => 'ISO 3166-2:LU',
                'usePostCode' => true
            ],
            'MO' => [
                'name' => 'Macao',
                'alpha-2' => 'MO',
                'alpha-3' => 'MAC',
                'code' => '446',
                'iso-3166-2' => 'ISO 3166-2:MO',
                'usePostCode' => false
            ],
            'MK' => [
                'name' => 'Macedonia',
                'alpha-2' => 'MK',
                'alpha-3' => 'MKD',
                'code' => '807',
                'iso-3166-2' => 'ISO 3166-2:MK',
                'usePostCode' => true
            ],
            'MG' => [
                'name' => 'Madagascar',
                'alpha-2' => 'MG',
                'alpha-3' => 'MDG',
                'code' => '450',
                'iso-3166-2' => 'ISO 3166-2:MG',
                'usePostCode' => true
            ],
            'MW' => [
                'name' => 'Malawi',
                'alpha-2' => 'MW',
                'alpha-3' => 'MWI',
                'code' => '454',
                'iso-3166-2' => 'ISO 3166-2:MW',
                'usePostCode' => false
            ],
            'MY' => [
                'name' => 'Malaysia',
                'alpha-2' => 'MY',
                'alpha-3' => 'MYS',
                'code' => '458',
                'iso-3166-2' => 'ISO 3166-2:MY',
                'usePostCode' => true
            ],
            'MV' => [
                'name' => 'Maldives',
                'alpha-2' => 'MV',
                'alpha-3' => 'MDV',
                'code' => '462',
                'iso-3166-2' => 'ISO 3166-2:MV',
                'usePostCode' => true
            ],
            'ML' => [
                'name' => 'Mali',
                'alpha-2' => 'ML',
                'alpha-3' => 'MLI',
                'code' => '466',
                'iso-3166-2' => 'ISO 3166-2:ML',
                'usePostCode' => false
            ],
            'MT' => [
                'name' => 'Malta',
                'alpha-2' => 'MT',
                'alpha-3' => 'MLT',
                'code' => '470',
                'iso-3166-2' => 'ISO 3166-2:MT',
                'usePostCode' => true
            ],
            'MH' => [
                'name' => 'Marshall Islands',
                'alpha-2' => 'MH',
                'alpha-3' => 'MHL',
                'code' => '584',
                'iso-3166-2' => 'ISO 3166-2:MH',
                'usePostCode' => true
            ],
            'MQ' => [
                'name' => 'Martinique',
                'alpha-2' => 'MQ',
                'alpha-3' => 'MTQ',
                'code' => '474',
                'iso-3166-2' => 'ISO 3166-2:MQ',
                'usePostCode' => true
            ],
            'MR' => [
                'name' => 'Mauritania',
                'alpha-2' => 'MR',
                'alpha-3' => 'MRT',
                'code' => '478',
                'iso-3166-2' => 'ISO 3166-2:MR',
                'usePostCode' => false
            ],
            'MU' => [
                'name' => 'Mauritius',
                'alpha-2' => 'MU',
                'alpha-3' => 'MUS',
                'code' => '480',
                'iso-3166-2' => 'ISO 3166-2:MU',
                'usePostCode' => false
            ],
            'YT' => [
                'name' => 'Mayotte',
                'alpha-2' => 'YT',
                'alpha-3' => 'MYT',
                'code' => '175',
                'iso-3166-2' => 'ISO 3166-2:YT',
                'usePostCode' => true
            ],
            'MX' => [
                'name' => 'Mexico',
                'alpha-2' => 'MX',
                'alpha-3' => 'MEX',
                'code' => '484',
                'iso-3166-2' => 'ISO 3166-2:MX',
                'usePostCode' => true
            ],
            'FM' => [
                'name' => 'Micronesia',
                'alpha-2' => 'FM',
                'alpha-3' => 'FSM',
                'code' => '583',
                'iso-3166-2' => 'ISO 3166-2:FM',
                'usePostCode' => true
            ],
            'MD' => [
                'name' => 'Moldova',
                'alpha-2' => 'MD',
                'alpha-3' => 'MDA',
                'code' => '498',
                'iso-3166-2' => 'ISO 3166-2:MD',
                'usePostCode' => true
            ],
            'MC' => [
                'name' => 'Monaco',
                'alpha-2' => 'MC',
                'alpha-3' => 'MCO',
                'code' => '492',
                'iso-3166-2' => 'ISO 3166-2:MC',
                'usePostCode' => true
            ],
            'MN' => [
                'name' => 'Mongolia',
                'alpha-2' => 'MN',
                'alpha-3' => 'MNG',
                'code' => '496',
                'iso-3166-2' => 'ISO 3166-2:MN',
                'usePostCode' => true
            ],
            'ME' => [
                'name' => 'Montenegro',
                'alpha-2' => 'ME',
                'alpha-3' => 'MNE',
                'code' => '499',
                'iso-3166-2' => 'ISO 3166-2:ME',
                'usePostCode' => true
            ],
            'MS' => [
                'name' => 'Montserrat',
                'alpha-2' => 'MS',
                'alpha-3' => 'MSR',
                'code' => '500',
                'iso-3166-2' => 'ISO 3166-2:MS',
                'usePostCode' => false
            ],
            'MA' => [
                'name' => 'Morocco',
                'alpha-2' => 'MA',
                'alpha-3' => 'MAR',
                'code' => '504',
                'iso-3166-2' => 'ISO 3166-2:MA',
                'usePostCode' => true
            ],
            'MZ' => [
                'name' => 'Mozambique',
                'alpha-2' => 'MZ',
                'alpha-3' => 'MOZ',
                'code' => '508',
                'iso-3166-2' => 'ISO 3166-2:MZ',
                'usePostCode' => true
            ],
            'MM' => [
                'name' => 'Myanmar',
                'alpha-2' => 'MM',
                'alpha-3' => 'MMR',
                'code' => '104',
                'iso-3166-2' => 'ISO 3166-2:MM',
                'usePostCode' => true
            ],
            'NA' => [
                'name' => 'Namibia',
                'alpha-2' => 'NA',
                'alpha-3' => 'NAM',
                'code' => '516',
                'iso-3166-2' => 'ISO 3166-2:NA',
                'usePostCode' => true
            ],
            'NR' => [
                'name' => 'Nauru',
                'alpha-2' => 'NR',
                'alpha-3' => 'NRU',
                'code' => '520',
                'iso-3166-2' => 'ISO 3166-2:NR',
                'usePostCode' => false
            ],
            'NP' => [
                'name' => 'Nepal',
                'alpha-2' => 'NP',
                'alpha-3' => 'NPL',
                'code' => '524',
                'iso-3166-2' => 'ISO 3166-2:NP',
                'usePostCode' => true
            ],
            'NL' => [
                'name' => 'Netherlands',
                'alpha-2' => 'NL',
                'alpha-3' => 'NLD',
                'code' => '528',
                'iso-3166-2' => 'ISO 3166-2:NL',
                'usePostCode' => true
            ],
            'NC' => [
                'name' => 'New Caledonia',
                'alpha-2' => 'NC',
                'alpha-3' => 'NCL',
                'code' => '540',
                'iso-3166-2' => 'ISO 3166-2:NC',
                'usePostCode' => true
            ],
            'NZ' => [
                'name' => 'New Zealand',
                'alpha-2' => 'NZ',
                'alpha-3' => 'NZL',
                'code' => '554',
                'iso-3166-2' => 'ISO 3166-2:NZ',
                'usePostCode' => true
            ],
            'NI' => [
                'name' => 'Nicaragua',
                'alpha-2' => 'NI',
                'alpha-3' => 'NIC',
                'code' => '558',
                'iso-3166-2' => 'ISO 3166-2:NI',
                'usePostCode' => true
            ],
            'NE' => [
                'name' => 'Niger',
                'alpha-2' => 'NE',
                'alpha-3' => 'NER',
                'code' => '562',
                'iso-3166-2' => 'ISO 3166-2:NE',
                'usePostCode' => true
            ],
            'NG' => [
                'name' => 'Nigeria',
                'alpha-2' => 'NG',
                'alpha-3' => 'NGA',
                'code' => '566',
                'iso-3166-2' => 'ISO 3166-2:NG',
                'usePostCode' => true
            ],
            'NU' => [
                'name' => 'Niue',
                'alpha-2' => 'NU',
                'alpha-3' => 'NIU',
                'code' => '570',
                'iso-3166-2' => 'ISO 3166-2:NU',
                'usePostCode' => false
            ],
            'NF' => [
                'name' => 'Norfolk Island',
                'alpha-2' => 'NF',
                'alpha-3' => 'NFK',
                'code' => '574',
                'iso-3166-2' => 'ISO 3166-2:NF',
                'usePostCode' => true
            ],
            'MP' => [
                'name' => 'Northern Mariana Islands',
                'alpha-2' => 'MP',
                'alpha-3' => 'MNP',
                'code' => '580',
                'iso-3166-2' => 'ISO 3166-2:MP',
                'usePostCode' => true
            ],
            'NO' => [
                'name' => 'Norway',
                'alpha-2' => 'NO',
                'alpha-3' => 'NOR',
                'code' => '578',
                'iso-3166-2' => 'ISO 3166-2:NO',
                'usePostCode' => true
            ],
            'OM' => [
                'name' => 'Oman',
                'alpha-2' => 'OM',
                'alpha-3' => 'OMN',
                'code' => '512',
                'iso-3166-2' => 'ISO 3166-2:OM',
                'usePostCode' => true
            ],
            'PK' => [
                'name' => 'Pakistan',
                'alpha-2' => 'PK',
                'alpha-3' => 'PAK',
                'code' => '586',
                'iso-3166-2' => 'ISO 3166-2:PK',
                'usePostCode' => true
            ],
            'PW' => [
                'name' => 'Palau',
                'alpha-2' => 'PW',
                'alpha-3' => 'PLW',
                'code' => '585',
                'iso-3166-2' => 'ISO 3166-2:PW',
                'usePostCode' => true
            ],
            'PS' => [
                'name' => 'Palestine',
                'alpha-2' => 'PS',
                'alpha-3' => 'PSE',
                'code' => '275',
                'iso-3166-2' => 'ISO 3166-2:PS',
                'usePostCode' => true
            ],
            'PA' => [
                'name' => 'Panama',
                'alpha-2' => 'PA',
                'alpha-3' => 'PAN',
                'code' => '591',
                'iso-3166-2' => 'ISO 3166-2:PA',
                'usePostCode' => false
            ],
            'PG' => [
                'name' => 'Papua New Guinea',
                'alpha-2' => 'PG',
                'alpha-3' => 'PNG',
                'code' => '598',
                'iso-3166-2' => 'ISO 3166-2:PG',
                'usePostCode' => true
            ],
            'PY' => [
                'name' => 'Paraguay',
                'alpha-2' => 'PY',
                'alpha-3' => 'PRY',
                'code' => '600',
                'iso-3166-2' => 'ISO 3166-2:PY',
                'usePostCode' => true
            ],
            'PE' => [
                'name' => 'Peru',
                'alpha-2' => 'PE',
                'alpha-3' => 'PER',
                'code' => '604',
                'iso-3166-2' => 'ISO 3166-2:PE',
                'usePostCode' => true
            ],
            'PH' => [
                'name' => 'Philippines',
                'alpha-2' => 'PH',
                'alpha-3' => 'PHL',
                'code' => '608',
                'iso-3166-2' => 'ISO 3166-2:PH',
                'usePostCode' => true
            ],
            'PN' => [
                'name' => 'Pitcairn',
                'alpha-2' => 'PN',
                'alpha-3' => 'PCN',
                'code' => '612',
                'iso-3166-2' => 'ISO 3166-2:PN',
                'usePostCode' => true
            ],
            'PL' => [
                'name' => 'Poland',
                'alpha-2' => 'PL',
                'alpha-3' => 'POL',
                'code' => '616',
                'iso-3166-2' => 'ISO 3166-2:PL',
                'usePostCode' => true
            ],
            'PT' => [
                'name' => 'Portugal',
                'alpha-2' => 'PT',
                'alpha-3' => 'PRT',
                'code' => '620',
                'iso-3166-2' => 'ISO 3166-2:PT',
                'usePostCode' => true
            ],
            'PR' => [
                'name' => 'Puerto Rico',
                'alpha-2' => 'PR',
                'alpha-3' => 'PRI',
                'code' => '630',
                'iso-3166-2' => 'ISO 3166-2:PR',
                'usePostCode' => true
            ],
            'QA' => [
                'name' => 'Qatar',
                'alpha-2' => 'QA',
                'alpha-3' => 'QAT',
                'code' => '634',
                'iso-3166-2' => 'ISO 3166-2:QA',
                'usePostCode' => false
            ],
            'RE' => [
                'name' => 'Réunion',
                'alpha-2' => 'RE',
                'alpha-3' => 'REU',
                'code' => '638',
                'iso-3166-2' => 'ISO 3166-2:RE',
                'usePostCode' => true
            ],
            'RO' => [
                'name' => 'Romania',
                'alpha-2' => 'RO',
                'alpha-3' => 'ROU',
                'code' => '642',
                'iso-3166-2' => 'ISO 3166-2:RO',
                'usePostCode' => true
            ],
            'RU' => [
                'name' => 'Russian Federation',
                'alpha-2' => 'RU',
                'alpha-3' => 'RUS',
                'code' => '643',
                'iso-3166-2' => 'ISO 3166-2:RU',
                'usePostCode' => true
            ],
            'RW' => [
                'name' => 'Rwanda',
                'alpha-2' => 'RW',
                'alpha-3' => 'RWA',
                'code' => '646',
                'iso-3166-2' => 'ISO 3166-2:RW',
                'usePostCode' => false
            ],
            'BL' => [
                'name' => 'Saint Barthélemy',
                'alpha-2' => 'BL',
                'alpha-3' => 'BLM',
                'code' => '652',
                'iso-3166-2' => 'ISO 3166-2:BL',
                'usePostCode' => true
            ],
            'SH' => [
                'name' => 'Saint Helena, Ascension and Tristan da Cunha',
                'alpha-2' => 'SH',
                'alpha-3' => 'SHN',
                'code' => '654',
                'iso-3166-2' => 'ISO 3166-2:SH',
                'usePostCode' => false
            ],
            'KN' => [
                'name' => 'Saint Kitts and Nevis',
                'alpha-2' => 'KN',
                'alpha-3' => 'KNA',
                'code' => '659',
                'iso-3166-2' => 'ISO 3166-2:KN',
                'usePostCode' => false
            ],
            'LC' => [
                'name' => 'Saint Lucia',
                'alpha-2' => 'LC',
                'alpha-3' => 'LCA',
                'code' => '662',
                'iso-3166-2' => 'ISO 3166-2:LC',
                'usePostCode' => false
            ],
            'MF' => [
                'name' => 'Saint Martin',
                'alpha-2' => 'MF',
                'alpha-3' => 'MAF',
                'code' => '663',
                'iso-3166-2' => 'ISO 3166-2:MF',
                'usePostCode' => true
            ],
            'PM' => [
                'name' => 'Saint Pierre and Miquelon',
                'alpha-2' => 'PM',
                'alpha-3' => 'SPM',
                'code' => '666',
                'iso-3166-2' => 'ISO 3166-2:PM',
                'usePostCode' => true
            ],
            'VC' => [
                'name' => 'Saint Vincent and the Grenadines',
                'alpha-2' => 'VC',
                'alpha-3' => 'VCT',
                'code' => '670',
                'iso-3166-2' => 'ISO 3166-2:VC',
                'usePostCode' => true
            ],
            'WS' => [
                'name' => 'Samoa',
                'alpha-2' => 'WS',
                'alpha-3' => 'WSM',
                'code' => '882',
                'iso-3166-2' => 'ISO 3166-2:WS',
                'usePostCode' => true
            ],
            'SM' => [
                'name' => 'San Marino',
                'alpha-2' => 'SM',
                'alpha-3' => 'SMR',
                'code' => '674',
                'iso-3166-2' => 'ISO 3166-2:SM',
                'usePostCode' => true
            ],
            'ST' => [
                'name' => 'Sao Tome and Principe',
                'alpha-2' => 'ST',
                'alpha-3' => 'STP',
                'code' => '678',
                'iso-3166-2' => 'ISO 3166-2:ST',
                'usePostCode' => false
            ],
            'SA' => [
                'name' => 'Saudi Arabia',
                'alpha-2' => 'SA',
                'alpha-3' => 'SAU',
                'code' => '682',
                'iso-3166-2' => 'ISO 3166-2:SA',
                'usePostCode' => true
            ],
            'SN' => [
                'name' => 'Senegal',
                'alpha-2' => 'SN',
                'alpha-3' => 'SEN',
                'code' => '686',
                'iso-3166-2' => 'ISO 3166-2:SN',
                'usePostCode' => true
            ],
            'RS' => [
                'name' => 'Serbia',
                'alpha-2' => 'RS',
                'alpha-3' => 'SRB',
                'code' => '688',
                'iso-3166-2' => 'ISO 3166-2:RS',
                'usePostCode' => true
            ],
            'SC' => [
                'name' => 'Seychelles',
                'alpha-2' => 'SC',
                'alpha-3' => 'SYC',
                'code' => '690',
                'iso-3166-2' => 'ISO 3166-2:SC',
                'usePostCode' => false
            ],
            'SL' => [
                'name' => 'Sierra Leone',
                'alpha-2' => 'SL',
                'alpha-3' => 'SLE',
                'code' => '694',
                'iso-3166-2' => 'ISO 3166-2:SL',
                'usePostCode' => false
            ],
            'SG' => [
                'name' => 'Singapore',
                'alpha-2' => 'SG',
                'alpha-3' => 'SGP',
                'code' => '702',
                'iso-3166-2' => 'ISO 3166-2:SG',
                'usePostCode' => true
            ],
            'SX' => [
                'name' => 'Sint Maarten',
                'alpha-2' => 'SX',
                'alpha-3' => 'SXM',
                'code' => '534',
                'iso-3166-2' => 'ISO 3166-2:SX',
                'usePostCode' => true
            ],
            'SK' => [
                'name' => 'Slovakia',
                'alpha-2' => 'SK',
                'alpha-3' => 'SVK',
                'code' => '703',
                'iso-3166-2' => 'ISO 3166-2:SK',
                'usePostCode' => true
            ],
            'SI' => [
                'name' => 'Slovenia',
                'alpha-2' => 'SI',
                'alpha-3' => 'SVN',
                'code' => '705',
                'iso-3166-2' => 'ISO 3166-2:SI',
                'usePostCode' => true
            ],
            'SB' => [
                'name' => 'Solomon Islands',
                'alpha-2' => 'SB',
                'alpha-3' => 'SLB',
                'code' => '090',
                'iso-3166-2' => 'ISO 3166-2:SB',
                'usePostCode' => false
            ],
            'SO' => [
                'name' => 'Somalia',
                'alpha-2' => 'SO',
                'alpha-3' => 'SOM',
                'code' => '706',
                'iso-3166-2' => 'ISO 3166-2:SO',
                'usePostCode' => false
            ],
            'ZA' => [
                'name' => 'South Africa',
                'alpha-2' => 'ZA',
                'alpha-3' => 'ZAF',
                'code' => '710',
                'iso-3166-2' => 'ISO 3166-2:ZA',
                'usePostCode' => false
            ],
            'GS' => [
                'name' => 'South Georgia and the South Sandwich Islands',
                'alpha-2' => 'GS',
                'alpha-3' => 'SGS',
                'code' => '239',
                'iso-3166-2' => 'ISO 3166-2:GS',
                'usePostCode' => true
            ],
            'SS' => [
                'name' => 'South Sudan',
                'alpha-2' => 'SS',
                'alpha-3' => 'SSD',
                'code' => '728',
                'iso-3166-2' => 'ISO 3166-2:SS',
                'usePostCode' => true
            ],
            'ES' => [
                'name' => 'Spain',
                'alpha-2' => 'ES',
                'alpha-3' => 'ESP',
                'code' => '724',
                'iso-3166-2' => 'ISO 3166-2:ES',
                'usePostCode' => true
            ],
            'LK' => [
                'name' => 'Sri Lanka',
                'alpha-2' => 'LK',
                'alpha-3' => 'LKA',
                'code' => '144',
                'iso-3166-2' => 'ISO 3166-2:LK',
                'usePostCode' => true
            ],
            'SD' => [
                'name' => 'Sudan',
                'alpha-2' => 'SD',
                'alpha-3' => 'SDN',
                'code' => '729',
                'iso-3166-2' => 'ISO 3166-2:SD',
                'usePostCode' => true
            ],
            'SR' => [
                'name' => 'Suriname',
                'alpha-2' => 'SR',
                'alpha-3' => 'SUR',
                'code' => '740',
                'iso-3166-2' => 'ISO 3166-2:SR',
                'usePostCode' => false
            ],
            'SJ' => [
                'name' => 'Svalbard and Jan Mayen',
                'alpha-2' => 'SJ',
                'alpha-3' => 'SJM',
                'code' => '744',
                'iso-3166-2' => 'ISO 3166-2:SJ',
                'usePostCode' => true
            ],
            'SZ' => [
                'name' => 'Swaziland',
                'alpha-2' => 'SZ',
                'alpha-3' => 'SWZ',
                'code' => '748',
                'iso-3166-2' => 'ISO 3166-2:SZ',
                'usePostCode' => true
            ],
            'SE' => [
                'name' => 'Sweden',
                'alpha-2' => 'SE',
                'alpha-3' => 'SWE',
                'code' => '752',
                'iso-3166-2' => 'ISO 3166-2:SE',
                'usePostCode' => true
            ],
            'CH' => [
                'name' => 'Switzerland',
                'alpha-2' => 'CH',
                'alpha-3' => 'CHE',
                'code' => '756',
                'iso-3166-2' => 'ISO 3166-2:CH',
                'usePostCode' => true
            ],
            'SY' => [
                'name' => 'Syria',
                'alpha-2' => 'SY',
                'alpha-3' => 'SYR',
                'code' => '760',
                'iso-3166-2' => 'ISO 3166-2:SY',
                'usePostCode' => false
            ],
            'TW' => [
                'name' => 'Taiwan',
                'alpha-2' => 'TW',
                'alpha-3' => 'TWN',
                'code' => '158',
                'iso-3166-2' => 'ISO 3166-2:TW',
                'usePostCode' => true
            ],
            'TJ' => [
                'name' => 'Tajikistan',
                'alpha-2' => 'TJ',
                'alpha-3' => 'TJK',
                'code' => '762',
                'iso-3166-2' => 'ISO 3166-2:TJ',
                'usePostCode' => true
            ],
            'TZ' => [
                'name' => 'Tanzania',
                'alpha-2' => 'TZ',
                'alpha-3' => 'TZA',
                'code' => '834',
                'iso-3166-2' => 'ISO 3166-2:TZ',
                'usePostCode' => false
            ],
            'TH' => [
                'name' => 'Thailand',
                'alpha-2' => 'TH',
                'alpha-3' => 'THA',
                'code' => '764',
                'iso-3166-2' => 'ISO 3166-2:TH',
                'usePostCode' => true
            ],
            'TL' => [
                'name' => 'Timor-Leste',
                'alpha-2' => 'TL',
                'alpha-3' => 'TLS',
                'code' => '626',
                'iso-3166-2' => 'ISO 3166-2:TL',
                'usePostCode' => false
            ],
            'TG' => [
                'name' => 'Togo',
                'alpha-2' => 'TG',
                'alpha-3' => 'TGO',
                'code' => '768',
                'iso-3166-2' => 'ISO 3166-2:TG',
                'usePostCode' => false
            ],
            'TK' => [
                'name' => 'Tokelau',
                'alpha-2' => 'TK',
                'alpha-3' => 'TKL',
                'code' => '772',
                'iso-3166-2' => 'ISO 3166-2:TK',
                'usePostCode' => false
            ],
            'TO' => [
                'name' => 'Tonga',
                'alpha-2' => 'TO',
                'alpha-3' => 'TON',
                'code' => '776',
                'iso-3166-2' => 'ISO 3166-2:TO',
                'usePostCode' => false
            ],
            'TT' => [
                'name' => 'Trinidad and Tobago',
                'alpha-2' => 'TT',
                'alpha-3' => 'TTO',
                'code' => '780',
                'iso-3166-2' => 'ISO 3166-2:TT',
                'usePostCode' => false
            ],
            'TN' => [
                'name' => 'Tunisia',
                'alpha-2' => 'TN',
                'alpha-3' => 'TUN',
                'code' => '788',
                'iso-3166-2' => 'ISO 3166-2:TN',
                'usePostCode' => true
            ],
            'TR' => [
                'name' => 'Turkey',
                'alpha-2' => 'TR',
                'alpha-3' => 'TUR',
                'code' => '792',
                'iso-3166-2' => 'ISO 3166-2:TR',
                'usePostCode' => true
            ],
            'TM' => [
                'name' => 'Turkmenistan',
                'alpha-2' => 'TM',
                'alpha-3' => 'TKM',
                'code' => '795',
                'iso-3166-2' => 'ISO 3166-2:TM',
                'usePostCode' => true
            ],
            'TC' => [
                'name' => 'Turks and Caicos Islands',
                'alpha-2' => 'TC',
                'alpha-3' => 'TCA',
                'code' => '796',
                'iso-3166-2' => 'ISO 3166-2:TC',
                'usePostCode' => true
            ],
            'TV' => [
                'name' => 'Tuvalu',
                'alpha-2' => 'TV',
                'alpha-3' => 'TUV',
                'code' => '798',
                'iso-3166-2' => 'ISO 3166-2:TV',
                'usePostCode' => false
            ],
            'UG' => [
                'name' => 'Uganda',
                'alpha-2' => 'UG',
                'alpha-3' => 'UGA',
                'code' => '800',
                'iso-3166-2' => 'ISO 3166-2:UG',
                'usePostCode' => false
            ],
            'UA' => [
                'name' => 'Ukraine',
                'alpha-2' => 'UA',
                'alpha-3' => 'UKR',
                'code' => '804',
                'iso-3166-2' => 'ISO 3166-2:UA',
                'usePostCode' => true
            ],
            'AE' => [
                'name' => 'United Arab Emirates',
                'alpha-2' => 'AE',
                'alpha-3' => 'ARE',
                'code' => '784',
                'iso-3166-2' => 'ISO 3166-2:AE',
                'usePostCode' => false
            ],
            'GB' => [
                'name' => 'United Kingdom',
                'alpha-2' => 'GB',
                'alpha-3' => 'GBR',
                'code' => '826',
                'iso-3166-2' => 'ISO 3166-2:GB',
                'usePostCode' => true
            ],
            'US' => [
                'name' => 'United States',
                'alpha-2' => 'US',
                'alpha-3' => 'USA',
                'code' => '840',
                'iso-3166-2' => 'ISO 3166-2:US',
                'usePostCode' => true
            ],
            'UM' => [
                'name' => 'United States Minor Outlying Islands',
                'alpha-2' => 'UM',
                'alpha-3' => 'UMI',
                'code' => '581',
                'iso-3166-2' => 'ISO 3166-2:UM',
                'usePostCode' => true
            ],
            'UY' => [
                'name' => 'Uruguay',
                'alpha-2' => 'UY',
                'alpha-3' => 'URY',
                'code' => '858',
                'iso-3166-2' => 'ISO 3166-2:UY',
                'usePostCode' => true
            ],
            'UZ' => [
                'name' => 'Uzbekistan',
                'alpha-2' => 'UZ',
                'alpha-3' => 'UZB',
                'code' => '860',
                'iso-3166-2' => 'ISO 3166-2:UZ',
                'usePostCode' => true
            ],
            'VU' => [
                'name' => 'Vanuatu',
                'alpha-2' => 'VU',
                'alpha-3' => 'VUT',
                'code' => '548',
                'iso-3166-2' => 'ISO 3166-2:VU',
                'usePostCode' => false
            ],
            'VE' => [
                'name' => 'Venezuela',
                'alpha-2' => 'VE',
                'alpha-3' => 'VEN',
                'code' => '862',
                'iso-3166-2' => 'ISO 3166-2:VE',
                'usePostCode' => true
            ],
            'VN' => [
                'name' => 'Viet Nam',
                'alpha-2' => 'VN',
                'alpha-3' => 'VNM',
                'code' => '704',
                'iso-3166-2' => 'ISO 3166-2:VN',
                'usePostCode' => true
            ],
            'VG' => [
                'name' => 'Virgin Islands (British)',
                'alpha-2' => 'VG',
                'alpha-3' => 'VGB',
                'code' => '092',
                'iso-3166-2' => 'ISO 3166-2:VG',
                'usePostCode' => true
            ],
            'VI' => [
                'name' => 'Virgin Islands (U.S.)',
                'alpha-2' => 'VI',
                'alpha-3' => 'VIR',
                'code' => '850',
                'iso-3166-2' => 'ISO 3166-2:VI',
                'usePostCode' => true
            ],
            'WF' => [
                'name' => 'Wallis and Futuna',
                'alpha-2' => 'WF',
                'alpha-3' => 'WLF',
                'code' => '876',
                'iso-3166-2' => 'ISO 3166-2:WF',
                'usePostCode' => true
            ],
            'EH' => [
                'name' => 'Western Sahara',
                'alpha-2' => 'EH',
                'alpha-3' => 'ESH',
                'code' => '732',
                'iso-3166-2' => 'ISO 3166-2:EH',
                'usePostCode' => true
            ],
            'YE' => [
                'name' => 'Yemen',
                'alpha-2' => 'YE',
                'alpha-3' => 'YEM',
                'code' => '887',
                'iso-3166-2' => 'ISO 3166-2:YE',
                'usePostCode' => false
            ],
            'ZM' => [
                'name' => 'Zambia',
                'alpha-2' => 'ZM',
                'alpha-3' => 'ZMB',
                'code' => '894',
                'iso-3166-2' => 'ISO 3166-2:ZM',
                'usePostCode' => true
            ],
            'ZW' => [
                'name' => 'Zimbabwe',
                'alpha-2' => 'ZW',
                'alpha-3' => 'ZWE',
                'code' => '716',
                'iso-3166-2' => 'ISO 3166-2:ZW',
                'usePostCode' => false
            ],
        ];

        return $countries;
    }

    public static function getCountryByCode( $countryCode ) {
        if (isset($countryCode)) {
            $availableCountries = self::getAvailableCountries();
            if (isset($availableCountries) && array_key_exists($countryCode, $availableCountries)) {
                return $availableCountries[$countryCode];
            }
        }

        return null;
    }

    /**
     * @param $countryCode
     *
     * @return null|string
     */
    public static function getCountryNameFor( $countryCode ) {
        if (isset($countryCode)) {
            $availableCountries = self::getAvailableCountries();
            if (isset($availableCountries) && array_key_exists($countryCode, $availableCountries)) {
                $countryName = $availableCountries[$countryCode]['name'];
            } else {
                $countryName = strtoupper($countryCode);
            }

            return $countryName;
        }

        return null;
    }

    /**
     * @return array
     */
    public static function getAvailableCheckoutCountryCodes() {
        $countryCodes = [
            'AC',
            'AD',
            'AE',
            'AF',
            'AG',
            'AI',
            'AL',
            'AM',
            'AO',
            'AQ',
            'AR',
            'AT',
            'AU',
            'AW',
            'AX',
            'AZ',
            'BA',
            'BB',
            'BD',
            'BE',
            'BF',
            'BG',
            'BH',
            'BI',
            'BJ',
            'BL',
            'BM',
            'BN',
            'BO',
            'BQ',
            'BR',
            'BS',
            'BT',
            'BV',
            'BW',
            'BY',
            'BZ',
            'CA',
            'CD',
            'CF',
            'CG',
            'CH',
            'CI',
            'CK',
            'CL',
            'CM',
            'CN',
            'CO',
            'CR',
            'CV',
            'CW',
            'CY',
            'CZ',
            'DE',
            'DJ',
            'DK',
            'DM',
            'DO',
            'DZ',
            'EC',
            'EE',
            'EG',
            'EH',
            'ER',
            'ES',
            'ET',
            'FI',
            'FJ',
            'FK',
            'FO',
            'FR',
            'GA',
            'GB',
            'GD',
            'GE',
            'GF',
            'GG',
            'GH',
            'GI',
            'GL',
            'GM',
            'GN',
            'GP',
            'GQ',
            'GR',
            'GS',
            'GT',
            'GU',
            'GW',
            'GY',
            'HK',
            'HN',
            'HR',
            'HT',
            'HU',
            'ID',
            'IE',
            'IL',
            'IM',
            'IN',
            'IO',
            'IQ',
            'IS',
            'IT',
            'JE',
            'JM',
            'JO',
            'JP',
            'KE',
            'KG',
            'KH',
            'KI',
            'KM',
            'KN',
            'KR',
            'KW',
            'KY',
            'KZ',
            'LA',
            'LB',
            'LC',
            'LI',
            'LK',
            'LR',
            'LS',
            'LT',
            'LU',
            'LV',
            'LY',
            'MA',
            'MC',
            'MD',
            'ME',
            'MF',
            'MG',
            'MK',
            'ML',
            'MM',
            'MN',
            'MO',
            'MQ',
            'MR',
            'MS',
            'MT',
            'MU',
            'MV',
            'MW',
            'MX',
            'MY',
            'MZ',
            'NA',
            'NC',
            'NE',
            'NG',
            'NI',
            'NL',
            'NO',
            'NP',
            'NR',
            'NU',
            'NZ',
            'OM',
            'PA',
            'PE',
            'PF',
            'PG',
            'PH',
            'PK',
            'PL',
            'PM',
            'PN',
            'PR',
            'PS',
            'PT',
            'PY',
            'QA',
            'RE',
            'RO',
            'RS',
            'RU',
            'RW',
            'SA',
            'SB',
            'SC',
            'SE',
            'SG',
            'SH',
            'SI',
            'SJ',
            'SK',
            'SL',
            'SM',
            'SN',
            'SO',
            'SR',
            'SS',
            'ST',
            'SV',
            'SX',
            'SZ',
            'TA',
            'TC',
            'TD',
            'TF',
            'TG',
            'TH',
            'TJ',
            'TK',
            'TL',
            'TM',
            'TN',
            'TO',
            'TR',
            'TT',
            'TV',
            'TW',
            'TZ',
            'UA',
            'UG',
            'US',
            'UY',
            'UZ',
            'VA',
            'VC',
            'VE',
            'VG',
            'VN',
            'VU',
            'WF',
            'WS',
            'XK',
            'YE',
            'YT',
            'ZA',
            'ZM',
            'ZW',
            'ZZ'
        ];

        return $countryCodes;
    }
}