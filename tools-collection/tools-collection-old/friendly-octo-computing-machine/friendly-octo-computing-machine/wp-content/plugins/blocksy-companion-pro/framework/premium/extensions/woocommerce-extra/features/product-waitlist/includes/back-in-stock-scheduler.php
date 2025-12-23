<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class BackInStockEmailScheduler {
    private $scheduled_action_name = 'blocksy_waitlist_send_back_in_stock_callback';

    public function __construct() {
        add_action(
			$this->scheduled_action_name,
			[$this, 'run_chunk_callback'],
			10,
			2
		);
    }

    public function initiate($waitlists_to_process) {

		if (empty($waitlists_to_process)) {
			return;
		}

		$product = wc_get_product($waitlists_to_process[0]->product_id);

		if (isset($waitlists_to_process[0]->variation_id)) {
			$product = wc_get_product($waitlists_to_process[0]->variation_id);
		}

		$emails_per_chunk = apply_filters('blocksy_waitlist_scheduled_email_chunk', 50);
		$waitlist_ids = array_map(function ($waitlist) {
            return $waitlist->subscription_id;
        }, $waitlists_to_process);

		ProductWaitlistDb::bulk_update_waitlist_data(
			$waitlist_ids,
			[
				'state_updated' => current_time('mysql', 1),
				'state' => 'pending'
			]
		);
        
		$waitlist_chunks = array_chunk($waitlist_ids, $emails_per_chunk);

		$queue = WC()->get_instance_of(\WC_Queue::class);

        wc_get_logger()->debug(
            'BackInStockEmailScheduler:enqueue_waitlist_chunks',
            [
                'source' => 'blocksy_waitlist_emails',
                'product_id' => $product->get_id(),
				'waitlist_chunks' => $waitlist_chunks
            ]
        );

		foreach ($waitlist_chunks as $chunk) {
			$queue->schedule_single(
				WC()->call_function('time') + 1,
				$this->scheduled_action_name,
				[
					'product_id' => $product->get_id(),
					'waitlists' => $chunk
				],
				'blocksy_waitlist_emails'
			);
		}
	}

    public function run_chunk_callback($product_id, $waitlists) {

		wc_get_logger()->debug(
            'BackInStockEmailScheduler:run_chunk_callback',
            [
                'source' => 'blocksy_waitlist_emails',
                'product_id' => $product_id,
				'waitlist_chunks' => $waitlists
            ]
        );

		$product = wc_get_product($product_id);
		$waitlists_to_process = ProductWaitlistDb::get_waitlists_from_db($product, '', '', true, $waitlists);

		$mailer = WC()->mailer();
		$back_in_stock_email = $mailer->emails['BackInStockEmail'];

		if ($back_in_stock_email->is_enabled()) {
			$back_in_stock_email->trigger($waitlists_to_process);
		}
	}
}