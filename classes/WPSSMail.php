<?php
/**
 * @author              Angelo Rocha
 * @author              Angelo Rocha <contato@angelorocha.com.br>
 * @link                https://angelorocha.com.br
 * @copyleft            2020
 * @license             GNU GPL 3 (https://www.gnu.org/licenses/gpl-3.0.html)
 * @package WordPress
 * @subpackage superstar
 * @since 1.0.0
 */

class WPSSMail {
	public $mail_from;
	public $mail_to;
	public $mail_subject;
	public $mail_message;
	public $mail_attach;


	public function wpss_sendmail() {
		wp_mail(
			$this->mail_to,
			$this->mail_subject,
			$this->mail_message,
			self::wpss_mail_headers(),
			$this->mail_attach
		);
	}

	/**
	 * Generate mail headers
	 * @return string
	 */
	public function wpss_mail_headers() {
		$mail_headers = "Content-Type: text/html; charset=UTF-8\n\r";
		$mail_headers .= "From: $this->mail_from\n\r";
		$mail_headers .= "Reply-To: $this->mail_from\n\r";
		$mail_headers .= "Return-Path: $this->mail_from\n\r";

		return $mail_headers;
	}
}