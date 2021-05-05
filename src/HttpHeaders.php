<?php

namespace P4\MasterTheme;

/**
 * Class HttpHeaders
 */
class HttpHeaders {
	/**
	 * Headers constructor.
	 */
	public function __construct() {
		add_action( 'wp_headers', [ $this, 'send_content_security_policy_header' ], 10, 1 );
	}

	/**
	 * Send Content Security Policy (CSP) HTTP headers.
	 *
	 * @param string[] $headers Associative array of headers to be sent.
	 */
	public function send_content_security_policy_header( $headers ): array {
		$default_directives = [
			'default-src'     => [
				'*',
				'\'self\'',
				'data:',
				'\'unsafe-inline\'',
				'\'unsafe-eval\'',
			],
			'frame-ancestors' => [ '\'self\'' ],
		];

		/**
		 * Hook usage:
		 * add_filter( 'planet4_csp_directives', function ( $directives ) {
		 *     $directives['img-src'] = [ '*', 'blob:', 'data:' ];
		 *     $directives['frame-ancestors'][] = 'https://www.example.org';
		 *     return $directives;
		 * } );
		 */
		$directives = apply_filters( 'planet4_csp_directives', $default_directives );

		$csp_header = implode('; ', array_map(function ( $key, $values ) {
				return $key . ' ' . implode( ' ', array_filter( $values ) );
			},
			array_keys( $directives ),
			$directives
		));
		$csp_header = preg_replace( "/\r|\n/", '', $csp_header );
		$headers['Content-Security-Policy'] = $csp_header;

		// In addition, send the "X-Frame-Options" header when no other trusted frame ancestors were added through the filter.
		if ( ! empty( $directives['frame-ancestors'] )
			&& $default_directives['frame-ancestors'] === $directives['frame-ancestors']
		) {
			$headers['X-Frame-Options'] = 'SAMEORIGIN';
		}

		return $headers;
	}
}
