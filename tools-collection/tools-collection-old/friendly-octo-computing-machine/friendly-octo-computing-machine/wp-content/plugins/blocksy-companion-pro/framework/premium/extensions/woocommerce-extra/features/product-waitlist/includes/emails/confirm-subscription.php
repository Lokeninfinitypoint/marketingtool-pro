<?php

namespace Blocksy\Extensions\WoocommerceExtra;

class ConfirmSubscriptionEmail extends WaitlistEmail {
	public function __construct() {
		$this->id = 'wc_ct_confirm_subscription';
		$this->title = esc_html__('Waitlist - Confirm Subscription', 'blocksy-companion');
		$this->description = esc_html__('This email is sent when a user subscribes to a product stock alert and should confirm their subscription', 'blocksy-companion');
		$this->subject = esc_html__('Confirm waitlist subscription', 'blocksy-companion');
		$this->heading = esc_html__('Get notified when {product_title} is back in stock', 'blocksy-companion');

		$this->template_base = path_join(
			BLOCKSY_PATH,
			'framework/premium/extensions/woocommerce-extra/features/product-waitlist/templates/'
		);

		$this->template_html  = 'emails/waitlist-confirm-subscription.php';
		$this->template_plain = 'emails/plain/waitlist-confirm-subscription.php';

		$this->customer_email = true;

		parent::__construct();
	}

	public function init_form_fields() {
		parent::init_form_fields();
	}

	public function trigger($user_email, $product) {
		$this->object = $product;
		$this->recipient = $user_email;

		if (
			! $this->is_enabled()
			||
			! $this->get_recipient()
			||
			! $this->object
		) {
			return;
		}

		$success = $this->send(
			$this->get_recipient(),
			$this->get_subject(),
			$this->get_content(),
			$this->get_headers(),
			$this->get_attachments()
		);

		wc_get_logger()->debug(
			'ConfirmSubscriptionEmail:after_send',
			[
				'source' => 'blocksy_waitlist_emails',
				'product_id' => $product->get_id(),
				'type' => $product->get_type(),
				'user_email' => $this->recipient,
				'success' => $success,
			]
		);
	}

    public function get_content_html() {
		$object = WaitlistEmail::get_dummy_or_product_data($this->object);

		ob_start();

		wc_get_template($this->template_html, [
			'email' => $this,
			'email_heading' => $this->get_heading(),
			'product' => $object,
			'user_name' => $this->get_user_name($this->recipient),
			'confirm_url' => $this->get_confirm_subscription_link($object, $this->recipient),
			'unsubscribe_link' => self::get_unsubscribe_link($object, $this->recipient),
			'sent_to_admin' => false,
			'plain_text'=> false,
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
			'confirm_url' => $this->get_confirm_subscription_link($object, $this->recipient),
			'unsubscribe_link' => SubscriptionConfirmedEmail::get_unsubscribe_link($this->object, $this->recipient),
			'sent_to_admin' => false,
			'plain_text' => false
		]);

		return ob_get_clean();
	}
}
