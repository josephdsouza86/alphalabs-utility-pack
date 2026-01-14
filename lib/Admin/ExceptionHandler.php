<?php
/**
 * Exception Handler for WordPress errors
 *
 * @category   AlphaLabsUtilityPack
 * @package    AlphaLabsUtilityPack\Admin
 * @author     Alpha Labs <info@alphalabs.net>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       https://alphalabs.net
 */

namespace AlphaLabsUtilityPack\Admin;

/**
 * Exception Handler
 *
 * Sends error notifications to both WordPress admin email
 * and admin@alphalabs.net for centralized error tracking.
 *
 * @category   AlphaLabsUtilityPack
 * @package    AlphaLabsUtilityPack\Admin
 * @author     Alpha Labs <info@alphalabs.net>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       https://alphalabs.net
 */
class ExceptionHandler {


	/**
	 * Alpha Labs monitoring email
	 *
	 * @var string
	 */
	private const ALPHA_LABS_EMAIL = 'admin@alphalabs.net';

	/**
	 * Initialize the exception handler
	 *
	 * Hooks into WordPress shutdown to catch fatal errors
	 */
	public function __construct() {
		// Register shutdown function to catch fatal errors.
		register_shutdown_function( array( $this, 'handleShutdown' ) );

		// Hook into WordPress recovery mode.
		add_action( 'wp_php_error_message', array( $this, 'sendErrorNotification' ), 10, 1 );
	}

	/**
	 * Handle fatal errors on shutdown
	 *
	 * @return void
	 */
	public function handleShutdown() {
		$error = error_get_last();

		if ( $error === null ) {
			return;
		}

		$fatal_errors = array( E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR );

		if ( in_array( $error['type'], $fatal_errors, true ) ) {
			$this->_sendFatalErrorNotification( $error );
		}
	}

	/**
	 * Send error notification via WordPress recovery mode hook
	 *
	 * @param array $error Error details from WordPress.
	 *
	 * @return void
	 */
	public function sendErrorNotification( $error ) {
		if ( empty( $error ) ) {
			return;
		}

		$this->_sendFatalErrorNotification( $error );
	}

	/**
	 * Send fatal error notification emails
	 *
	 * @param array $error Error details from error_get_last().
	 *
	 * @return void
	 */
	private function _sendFatalErrorNotification( array $error ) {
		$site_name   = get_bloginfo( 'name' );
		$site_url    = get_site_url();
		$admin_email = get_option( 'admin_email' );

		$error_message = isset( $error['message'] ) ? $error['message'] : 'Unknown error';

		$subject = sprintf(
			'[ALU][%s] Fatal Error: %s',
			$site_name,
			$error_message
		);

		$message = $this->_formatFatalErrorEmail( $error, $site_name, $site_url );

		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: WordPress <' . $admin_email . '>',
		);

		// Send to WordPress admin.
		wp_mail( $admin_email, $subject, $message, $headers );

		// Send to Alpha Labs admin.
		if ( $admin_email !== self::ALPHA_LABS_EMAIL ) {
			wp_mail( self::ALPHA_LABS_EMAIL, $subject, $message, $headers );
		}

		// Log to WordPress debug log if enabled.
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
			error_log( '[Alpha Labs] Fatal Error: ' . $error_message );
		}
	}

	/**
	 * Format fatal error as HTML email
	 *
	 * @param array  $error     Error details.
	 * @param string $site_name Site name.
	 * @param string $site_url  Site URL.
	 *
	 * @return string HTML formatted email body.
	 */
	private function _formatFatalErrorEmail( array $error, $site_name, $site_url ) {
		$timestamp = current_time( 'mysql' );
		$type      = $this->_getErrorTypeName(
			isset( $error['type'] ) ? $error['type'] : E_ERROR
		);
		$message   = isset( $error['message'] ) ? esc_html( $error['message'] ) : 'Unknown';
		$file      = isset( $error['file'] ) ? esc_html( $error['file'] ) : 'Unknown';
		$line      = isset( $error['line'] ) ? $error['line'] : 0;

		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ?
			esc_html( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : 'N/A';
		$user_agent  = isset( $_SERVER['HTTP_USER_AGENT'] ) ?
			esc_html( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : 'N/A';
		$remote_addr = isset( $_SERVER['REMOTE_ADDR'] ) ?
			esc_html( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'N/A';

		$html  = '<!DOCTYPE html><html><head><style>';
		$html .= 'body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }';
		$html .= '.container { max-width: 800px; margin: 0 auto; padding: 20px; }';
		$html .= 'h2 { color: #d32f2f; border-bottom: 2px solid #d32f2f; padding-bottom: 10px; }';
		$html .= '.section { margin-bottom: 20px; background: #f5f5f5;';
		$html .= ' padding: 15px; border-radius: 5px; }';
		$html .= '.label { font-weight: bold; color: #555; }';
		$html .= '.value { margin-left: 10px; }';
		$html .= '</style></head><body><div class="container">';
		$html .= '<h2>🚨 Fatal Error Detected</h2>';
		$html .= '<div class="section">';
		$html .= '<p><span class="label">Site:</span>';
		$html .= ' <span class="value">' . esc_html( $site_name );
		$html .= ' (' . esc_url( $site_url ) . ')</span></p>';
		$html .= '<p><span class="label">Timestamp:</span>';
		$html .= ' <span class="value">' . esc_html( $timestamp ) . '</span></p>';
		$html .= '</div><div class="section">';
		$html .= '<p><span class="label">Error Type:</span>';
		$html .= ' <span class="value">' . esc_html( $type ) . '</span></p>';
		$html .= '<p><span class="label">Message:</span>';
		$html .= ' <span class="value">' . $message . '</span></p>';
		$html .= '<p><span class="label">File:</span>';
		$html .= ' <span class="value">' . $file . '</span></p>';
		$html .= '<p><span class="label">Line:</span>';
		$html .= ' <span class="value">' . absint( $line ) . '</span></p>';
		$html .= '</div><div class="section">';
		$html .= '<p><span class="label">Request URI:</span>';
		$html .= ' <span class="value">' . $request_uri . '</span></p>';
		$html .= '<p><span class="label">User Agent:</span>';
		$html .= ' <span class="value">' . $user_agent . '</span></p>';
		$html .= '<p><span class="label">Remote IP:</span>';
		$html .= ' <span class="value">' . $remote_addr . '</span></p>';
		$html .= '</div></div></body></html>';

		return $html;
	}

	/**
	 * Get human-readable error type name
	 *
	 * @param int $type PHP error type constant.
	 *
	 * @return string Error type name.
	 */
	private function _getErrorTypeName( $type ) {
		$error_types = array(
			E_ERROR             => 'E_ERROR',
			E_WARNING           => 'E_WARNING',
			E_PARSE             => 'E_PARSE',
			E_NOTICE            => 'E_NOTICE',
			E_CORE_ERROR        => 'E_CORE_ERROR',
			E_CORE_WARNING      => 'E_CORE_WARNING',
			E_COMPILE_ERROR     => 'E_COMPILE_ERROR',
			E_COMPILE_WARNING   => 'E_COMPILE_WARNING',
			E_USER_ERROR        => 'E_USER_ERROR',
			E_USER_WARNING      => 'E_USER_WARNING',
			E_USER_NOTICE       => 'E_USER_NOTICE',
			E_STRICT            => 'E_STRICT',
			E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
			E_DEPRECATED        => 'E_DEPRECATED',
			E_USER_DEPRECATED   => 'E_USER_DEPRECATED',
		);

		return isset( $error_types[ $type ] ) ? $error_types[ $type ] : 'UNKNOWN';
	}
}
