<?php
/**
 * Author: Tiafeno
 * Date: 11/04/2018
 * Time: 13:40
 */

class inbox {
	public $inbox = [];
	private $inbox_id = null;

	public function __construct( $id = null ) {
		if ( ! is_int( $id ) ) {
			throw new Exception( 'Une variable de type entier est requise', E_WARNING );
		}
		$this->inbox_id = &$id;
	}

	public function getInboxId() {
		return $this->inbox_id;
	}

	public function send_message() {
		$send_to = $this->get_current_sender();

		/** Récuperation des informations HTTP */
		$content = Services::getValue( "msg_content" );
		/** Enregistre le message */
		$args       = [
			'post_author'  => $this->getmyId(),
			'post_title'   => esc_html( $title ),
			'post_content' => apply_filters( 'the_content', $content ),
			'post_status'  => 'publish', /* https://codex.wordpress.org/Post_Status */
			'post_parent'  => '',
			'post_type'    => "__fc_messages",
		];
		$message_id = wp_insert_post( $args );

	}

	/**
	 * @func message_existes
	 * @desc Vérifier si la boite de reception pour cette message existe déjà
	 *
	 * @param null $contributor
	 *
	 * @return bool
	 */
	private function message_exists( $contributor_id = null ) {
		$exist          = false;
		$queryParentMsg = new WP_Query( array(
			'post_type'   => "__fc_messages",
			"post_parent" => 0 // Only parent post
		) );
		if ( $queryParentMsg->have_posts() ) {
			while ( $queryParentMsg->have_posts() ): $queryParentMsg->the_post();
				$rcv          = get_post_meta( $queryParentMsg->post->ID, '__contributor', true ); // return json
				$contributors = json_decode( $rcv );
				$inArray      = array_search( $contributor_id, $contributors, true );
				$exist        = $inArray == false ? false : true;
				if ( $exist ) {
					break;
				}
			endwhile;
		}
		wp_reset_postdata();

		return $exist;
	}

	private function get_current_sender() {
		$_contributor = get_post_meta( $this->inbox_id, '__contributor', true ); // @return json
		$contributors = json_decode( $_contributor );
		$myId         = $this->getmyId();
		if ( $myId ) {
			throw new Exception( "Vous n'etes pas connecter sur le platform." );
		}
		$to = null;
		foreach ( $contributors as $key => $contributor ) :
			if ( (int) $contributor != $myId ) {
				$to = (int) $contributor;
				break;
			}
		endforeach;

		return $to;

	}

	private function getmyId() {
		$Usr = wp_get_current_user();

		return $Usr->ID;
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
	public function get_messages( $inbox_id = null ) {
		if ( is_null( $inbox_id ) && $this->inbox_id !== null ) {
			$inbox_id = $this->inbox_id;
		}
		if ( is_null( $inbox_id ) )
			return false;
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
				$msg->msg_inbox   = $inbox_id;
				$msg->msg_id      = $queryMeta->post->ID;
				$msg->msg_title   = $queryMeta->post->post_title;
				$msg->msg_content = $queryMeta->post->post_content;
				$msg->msg_contributor = $msg->get_contributor();

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

		return true;
	}
}

class message {
	public $msg_inbox; // >>>>> Post "inbox" id
	public $msg_id;
	public $msg_title; // >>>>>> e.g inbox_452198
	public $msg_content; // >>>>>> Corps du message
	public $msg_contributor; // >>>>>> les participants (2, Client and shop)
	public $msg_read; // >>>> Bool (1|0)
	public $send_date; // >>>> Date d'envoie

	/**
	 * @param {void}
	 *
	 * @return {Array|null}
	 */
	public function get_contributor() {
		$contributor = Array();
		if ( empty( $this->msg_inbox ) ) {
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

		$receiver = get_post_meta( $this->msg_inbox, '__contributor', true ); // return json
		if ( empty( $receiver ) ) {
			return null;
		}
		$receiver_ids = json_decode( $receiver );
		foreach ( $receiver_ids as $receiver_id ) {
			$contributor[] = get_user_by( 'id', $receiver_id );
		}

		return $contributor; // >>>>>>>>> array of WP_User
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
		$id    = Services::getValue( 'id_inbox' );
		$inbox = new inbox( (int) $id );
		echo $inbox->getInboxId();
	}
}

new __inbox_init__();