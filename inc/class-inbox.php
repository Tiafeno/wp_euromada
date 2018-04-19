<?php
/**
 * Author: Tiafeno
 * Date: 11/04/2018
 * Time: 13:40
 */

class inbox {
	private $inbox_id;
	public $inbox = [];

	public function __construct( $id = null ) {
		if ( ! is_int( $id ) ) {
			throw new Exception( 'Une variable de type entier est requise', E_WARNING );
		}
		$this->inbox_id = &$id;
	}

	public function send_message() {
		$send_to = $this->get_current_sender();
	}

	private function get_current_sender() {

	}

	/**
	 * @param $name
	 *
	 * @return mixed
	 */
	public function __get( $name ) {
		return $name;
	}

	/**
	 * @param $property
	 * @param $value
	 *
	 * @return mixed
	 */
	public function __set( $property, $value ) {
		if ( ! method_exists( $this, $property ) ) {
			return false;
		}
		if ( $property != 'inbox_id' || ! is_int( $value ) ) {
			return;
		}
		$this->$property = (int) $value;
		$this->get_messages( $value );
	}

	/**
	 * @param $inbox_id {int}
	 */
	public function get_messages( $inbox_id ) {
		/**
		 * Query message id (meta value "message")
		 */
		$queryMeta = new WP_Query( [
			'post_type'  => 'message',
			'meta_value' => "{$inbox_id}",
			'orderby'    => 'date',
			'order'      => 'ASC'
		] );
		if ( $queryMeta->have_posts() ) {
			while ( $queryMeta->have_posts() ): $queryMeta->the_post();
				$msg              = new message;
				$msg->msg_id      = $queryMeta->post->ID;
				$msg->msg_title   = $queryMeta->post->post_title;
				$msg->msg_content = $queryMeta->post->post_content;
				$msg->msg_sender  = $queryMeta->post->post_author;

				$msg->msg_to = $msg->get_receiver();

				/**
				 * Get message status if read or not
				 */
				$msg->msg_read  = $msg->get_read_status();
				$msg->send_date = $queryMeta->post->post_date;

				/**
				 * Stock all message in "inbox" property
				 */
				array_push( $this->inbox, $msg );
				unset( $msg );
			endwhile;
		}
		wp_reset_postdata();
	}
}

class message {
	public $msg_id; // >>>>> Post "inbox" id
	public $msg_title; // >>>>>> e.g inbox_452198
	public $msg_content; // >>>>>> Corps du message
	public $msg_sender; // >>>>>> Id d'utilisateur pour role "client"
	public $msg_to; // >>>>> Réception, id d'utilisateur à pour role "boutique", Object WP_User
	public $msg_read; // >>>> Bool (1|0)
	public $send_date; // >>>> Date d'envoie

	/**
	 * @param {void}
	 *
	 * @return {Array|null}
	 */
	public function get_receiver() {
		if ( empty( $this->msg_id ) ) {
			return false;
		}

		/*
		 * get_post_meta
		 * (mixed) Will be an array if $single is false.
		 * Will be value of meta data field if $single is true.
		 *
		 * If $single is true, an empty string is returned.
		 * If $single is false, an empty array is returned.
		 */

		$receiver = get_post_meta( $this->msg_id, '__receiver', true );
		if ( empty( $receiver ) ) {
			return null;
		}
		$receiver_id = (int) $receiver;
		$User        = get_user_by( 'id', $receiver_id );

		return $User; // >>>>>>>>> WP_User
	}

	/**
	 * @return bool
	 */
	public function get_read_status() {
		if ( empty( $this->msg_id ) ) {
			return false;
		}
		$read = get_post_meta( $this->msg_id, '__read', true );
		if ( empty( $read ) ) {
			return false;
		}

		return (int) $read == 0 ? false : true;
	}
}

final class __inbox_init__ {
	public function __construct() {
		$inbox           = new inbox;
		$inbox->id_inbox = 12;
	}
}

new __inbox_init__();