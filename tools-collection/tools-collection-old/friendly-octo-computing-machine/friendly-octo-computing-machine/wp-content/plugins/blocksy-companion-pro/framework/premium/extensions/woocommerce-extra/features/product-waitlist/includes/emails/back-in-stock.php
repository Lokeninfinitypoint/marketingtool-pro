<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class BackInStockEmail extends WaitlistEmail {
	public function __construct() {
		$this->id = 'wc_ct_back_in_stock';
		$this->title = esc_html__('Waitlist - Back in Stock Notification', 'blocksy-companion');
		$this->description = esc_html__('This email is sent when a product is back in stock', 'blocksy-companion');
		$this->subject = esc_html__('A product you are waiting for is back in stock', 'blocksy-companion');
		$this->heading = esc_html__('Good news! The product you have been waiting for is now back in stock!', 'blocksy-companion');

		$this->template_base = path_join(
			BLOCKSY_PATH,
			'framework/premium/extensions/woocommerce-extra/features/product-waitlist/templates/'
		);

		$this->template_html  = 'emails/waitlist-back-in-stock.php';
		$this->template_plain = 'emails/plain/waitlist-back-in-stock.php';

		$this->customer_email = true;

		add_action('blocksy_waitlist_send_back_in_stock_notification', [$this, 'trigger']);

		parent::__construct();
	}

    public function trigger($waitlists) {
		wc_get_logger()->debug(
			'BackInStockEmail:before_send',
			[
				'source' => 'blocksy_waitlist_emails',
				'waitlists' => $waitlists
			]
		);

		foreach ($waitlists as $waitlist) {
			$product_id = $waitlist->variation_id ? $waitlist->variation_id : $waitlist->product_id;

			$this->object = wc_get_product($product_id);
			$this->recipient = $waitlist->user_email;

			if (
				! $this->is_enabled()
				||
				! $this->get_recipient()
				||
				! $this->object
			) {
				continue;
			}

			$success = $this->send(
				$this->get_recipient(),
				$this->get_subject(),
				$this->get_content(),
				$this->get_headers(),
				$this->get_attachments()
			);

			wc_get_logger()->debug(
				'BackInStockEmail:after_send',
				[
					'source' => 'blocksy_waitlist_emails',
					'product_id' => $product_id,
					'type' => $this->object->get_type(),
					'user_email' => $this->recipient,
					'waitlist' => $waitlist,
					'success' => $success,
				]
			);

			if ($success) {
				ProductWaitlistDb::unsubscribe_by_token($waitlist->unsubscribe_token);
			} else {
				ProductWaitlistDb::update_waitlist_data(
					$this->object,
					$this->recipient,
					[
						'state_updated' => new \DateTime(),
						'state' => 'failed'
					]
				);
			}
		}
	}

	public function get_content_html() {
		$object = WaitlistEmail::get_dummy_or_product_data($this->object);

		ob_start();

		wc_get_template($this->template_html, [
			'email'=> $this,
			'email_heading' => $this->get_heading(),
			'product' => $object,
			'user_name' => $this->get_user_name($this->recipient),
			'sent_to_admin' => false,
			'plain_text' => false,
		]);

		return ob_get_clean();
	}

	public function get_content_plain() {
		$object = WaitlistEmail::get_dummy_or_product_data($this->object);

		ob_start();

		wc_get_template($this->template_plain, [
			'email' => $this,
			'email_heading' => $this->get_heading(),
			'product' => $object,
			'user_name' => $this->get_user_name($this->recipient),
			'sent_to_admin' => false,
			'plain_text' => false,
		]);

		return ob_get_clean();
	}
}
