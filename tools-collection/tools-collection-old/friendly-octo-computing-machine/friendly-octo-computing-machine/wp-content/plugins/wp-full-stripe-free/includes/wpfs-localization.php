<?php

/**
 * Created by PhpStorm.
 * User: codex
 * Date: 2020.05.15.
 * Time: 17:15
 */
class MM_WPFS_Localization {

	public static function translateLabel( $label, $domain = 'wp-full-stripe-free' ) {
		if ( empty( $label ) ) {
			return '';
		}

		//TODO: we need to register the strings in the translation plugins and after use their methods to translate those.
		return esc_attr( ( $label ) );
	}

	public static function formatIntervalLabel( $interval, $intervalCount ) {
		// This is an internal value, no need to localize it
		// todo: Instead of returning it, throw an exception
		$intervalLabel = 'No interval';

		if ( $interval === "year" ) {
			if($intervalCount == 1) {
				$intervalLabel = __( 'year', 'wp-full-stripe-free' );
			} else {
				$intervalLabel = sprintf( _n( '%d year', '%d years', $intervalCount, 'wp-full-stripe-free' ), number_format_i18n( $intervalCount ) );
			}
		} elseif ( $interval === "month" ) {
			if($intervalCount== 1){
				$intervalLabel = __( 'month', 'wp-full-stripe-free' );
			}else {
				$intervalLabel = sprintf( _n( '%d month', '%d months', $intervalCount, 'wp-full-stripe-free' ), number_format_i18n( $intervalCount ) );
			}
		} elseif ( $interval === "week" ) {
			if ( $intervalCount == 1 ) {
				$intervalLabel = __( 'week', 'wp-full-stripe-free' );
			} else {
				$intervalLabel = sprintf( _n( '%d week', '%d weeks', $intervalCount, 'wp-full-stripe-free' ), number_format_i18n( $intervalCount ) );
			}
		} elseif ( $interval === "day" ) {
			if ( $intervalCount == 1 ) {
				$intervalLabel = __( 'day', 'wp-full-stripe-free' );
			} else {
				$intervalLabel = sprintf( _n( '%d day', '%d days', $intervalCount, 'wp-full-stripe-free' ), number_format_i18n( $intervalCount ) );
			}
		}

		return $intervalLabel;
	}

	/**
	 * @param $interval
	 * @param $intervalCount
	 * @param $formattedAmount
	 *
	 * @return string
	 * @throws Exception
	 */
	public static function getPriceAndIntervalLabel( $interval, $intervalCount, $formattedAmount ) {
		switch ( $interval ) {
			case 'day':
				if ( $intervalCount == 1 ) {
					$formatStr = __('%1$s / day', 'wp-full-stripe-free');
				} else {
					/* translators: Recurring pricing descriptor.
					 * p1: formatted recurring amount with currency symbol
					 * p2: interval count
					 */
					$formatStr = _n(
						'%1$s / %2$d day',
						'%1$s / %2$d days',
						$intervalCount, 'wp-full-stripe-free'
					);
				}
				break;

			case 'week':
				if ( $intervalCount == 1 ) {
					$formatStr = __( '%1$s / week', 'wp-full-stripe-free' );
				} else {
					/* translators: Recurring pricing descriptor.
					 * p1: formatted recurring amount with currency symbol
					 * p2: interval count
					 */
					$formatStr = _n(
						'%1$s / %2$d week',
						'%1$s / %2$d weeks',
						$intervalCount, 'wp-full-stripe-free'
					);
				}
				break;

			case 'month':
				if ( $intervalCount == 1 ) {
					$formatStr = __( '%1$s / month', 'wp-full-stripe-free' );
				} else {

					/* translators: Recurring pricing descriptor.
					 * p1: formatted recurring amount with currency symbol
					 * p2: interval count
					 */
					$formatStr = _n(
						'%1$s / %2$d month',
						'%1$s / %2$d months',
						$intervalCount, 'wp-full-stripe-free'
					);

				}
				break;

			case 'year':
				if ( $intervalCount == 1 ) {
					$formatStr = __( '%1$s / year', 'wp-full-stripe-free' );
				} else {
					/* translators: Recurring pricing descriptor.
					 * p1: formatted recurring amount with currency symbol
					 * p2: interval count
					 */
					$formatStr = _n(
						'%1$s / %2$d year',
						'%1$s / %2$d years',
						$intervalCount, 'wp-full-stripe-free'
					);
				}
				break;

			default:
				throw new Exception( sprintf( '%s.%s(): Unknown plan interval \'%s\'.', __CLASS__, __FUNCTION__, $interval ) );
				break;
		}

		if ( $intervalCount == 1 ) {
			$priceLabel = sprintf( $formatStr, $formattedAmount );
		} else {
			$priceLabel = sprintf( $formatStr, $formattedAmount, $intervalCount );
		}

		return $priceLabel;
	}

    public static function getDonationFrequencyLabel( $donationFrequency ) {
        $res = $donationFrequency;

        switch ( $donationFrequency ) {
            case MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_ONE_TIME:
                /* translators: Label for the one-time donation frequency.
                 */
                $res = __( 'One-time',  'wp-full-stripe-free' );
                break;

            case MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_DAILY:
                /* translators: Label for the daily donation frequency.
                 */
                $res = __( 'Daily',  'wp-full-stripe-free' );
                break;

            case MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_WEEKLY:
                /* translators: Label for the weekly donation frequency.
                 */
                $res = __( 'Weekly',  'wp-full-stripe-free' );
                break;

            case MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_MONTHLY:
                /* translators: Label for the monthly donation frequency.
                 */
                $res = __( 'Monthly',  'wp-full-stripe-free' );
                break;

            case MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_ANNUAL:
                /* translators: Label for the annual donation frequency.
                 */
                $res = __( 'Annual',  'wp-full-stripe-free' );
                break;

            default:
                throw new Exception( sprintf( '%s.%s(): Unknown donation frequency \'%s\'.', __CLASS__, __FUNCTION__, $donationFrequency ) );
                break;
        }

        return $res;
    }
}