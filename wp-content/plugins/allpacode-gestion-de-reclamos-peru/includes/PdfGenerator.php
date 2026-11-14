<?php
/**
 * Lightweight PDF receipt generator.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class PdfGenerator {
	public function generate( array $claim ): string {
		$upload = wp_upload_dir();
		$dir    = trailingslashit( $upload['basedir'] ) . 'allpacode-gestion-de-reclamos-peru';

		if ( ! wp_mkdir_p( $dir ) ) {
			return '';
		}

		$file = trailingslashit( $dir ) . sanitize_file_name( $claim['code'] ) . '.pdf';
		$pdf  = $this->pdf( $claim );

		require_once ABSPATH . 'wp-admin/includes/file.php';
		WP_Filesystem();
		global $wp_filesystem;

		if ( $wp_filesystem ) {
			$wp_filesystem->put_contents( $file, $pdf, FS_CHMOD_FILE );
		}

		return $file;
	}

	public function url_for( array $claim ): string {
		$upload = wp_upload_dir();
		return trailingslashit( $upload['baseurl'] ) . 'allpacode-gestion-de-reclamos-peru/' . rawurlencode( sanitize_file_name( $claim['code'] ) . '.pdf' );
	}

	private function pdf( array $claim ): string {
		$site_name = get_bloginfo( 'name' );
		$logo      = $this->local_jpeg_logo();
		$brand     = $site_name;
		$content   = "0.96 0.97 0.98 rg 0 0 595 842 re f\n";
		$content  .= "1 1 1 rg 42 42 511 758 re f\n";
		$content  .= "0.90 0.92 0.94 RG 42 42 511 758 re S\n";

		if ( $logo ) {
			$content .= sprintf( "q %.2F 0 0 %.2F 62 742 cm /Logo Do Q\n", $logo['draw_width'], $logo['draw_height'] );
		} else {
			$content .= "0.07 0.09 0.13 rg BT /F2 18 Tf 62 762 Td (" . $this->pdf_text( $brand ) . ") Tj ET\n";
		}

		$content  .= "0.07 0.09 0.13 rg BT /F2 22 Tf 62 715 Td (" . $this->pdf_text( 'Constancia de Gestión de Reclamos' ) . ") Tj ET\n";
		$content  .= "0.25 0.29 0.35 rg BT /F1 11 Tf 62 694 Td (" . $this->pdf_text( 'Código: ' . $claim['code'] . '    Fecha: ' . $claim['created_at'] ) . ") Tj ET\n";
		$content  .= "0.93 0.94 0.96 RG 62 675 m 533 675 l S\n";

		$y = 648;
		$content .= $this->section( 'Empresa', $y );
		$y       -= 22;
		$content .= $this->line( 'Razón social', $claim['business_name'], $y );
		$y       -= 16;
		$content .= $this->line( 'RUC', $claim['business_ruc'], $y );
		$y       -= 16;
		$content .= $this->wrapped_line( 'Dirección fiscal', $claim['business_address'], $y, 95, 74 );
		$y       -= 54;

		$content .= $this->section( 'Consumidor', $y );
		$y       -= 22;
		$content .= $this->line( 'Nombre', $claim['consumer_name'], $y );
		$y       -= 16;
		$content .= $this->line( 'Documento', $claim['document_type'] . ' ' . $claim['document_number'], $y );
		$y       -= 16;
		$content .= $this->line( 'Contacto', $claim['email'] . ' / ' . $claim['phone'], $y );
		$y       -= 16;
		if ( ! empty( $claim['ubigeo_code'] ) ) {
			$content .= $this->line( 'Ubicación', $claim['department_name'] . ' / ' . $claim['province_name'] . ' / ' . $claim['district_name'] . ' (' . $claim['ubigeo_code'] . ')', $y );
			$y       -= 16;
		}
		$content .= $this->wrapped_line( 'Dirección', $claim['address'], $y, 72, 90 );
		$y       -= 48;

		$content .= $this->section( 'Detalle del caso', $y );
		$y       -= 22;
		$content .= $this->line( 'Clase de caso', Utils::types()[ $claim['type'] ] ?? ucfirst( $claim['type'] ), $y );
		$y       -= 16;
		$content .= $this->line( 'Producto o servicio', $claim['product_service'], $y );
		$y       -= 16;
		$content .= $this->line( 'Monto reclamado', (string) $claim['claimed_amount'], $y );
		$y       -= 22;
		$content .= $this->wrapped_line( 'Descripción', $claim['description'], $y, 85, 90 );
		$y       -= 74;
		$content .= $this->wrapped_line( 'Pedido del consumidor', $claim['consumer_request'], $y, 126, 90 );

		$content .= "0.93 0.94 0.96 RG 62 92 m 533 92 l S\n";
		$content .= "0.42 0.45 0.50 rg BT /F1 9 Tf 62 72 Td (" . $this->pdf_text( $site_name ) . ") Tj ET\n";

		$xobject = $logo ? ' /XObject << /Logo 7 0 R >>' : '';
		$objects = array(
			"1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n",
			"2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n",
			"3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R /F2 5 0 R >>{$xobject} >> /Contents 6 0 R >>\nendobj\n",
			"4 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>\nendobj\n",
			"5 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>\nendobj\n",
			"6 0 obj\n<< /Length " . strlen( $content ) . " >>\nstream\n{$content}endstream\nendobj\n",
		);

		if ( $logo ) {
			$objects[] = "7 0 obj\n<< /Type /XObject /Subtype /Image /Width {$logo['width']} /Height {$logo['height']} /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length " . strlen( $logo['bytes'] ) . " >>\nstream\n{$logo['bytes']}\nendstream\nendobj\n";
		}

		$pdf     = "%PDF-1.4\n";
		$offsets = array( 0 );
		foreach ( $objects as $object ) {
			$offsets[] = strlen( $pdf );
			$pdf     .= $object;
		}

		$xref = strlen( $pdf );
		$pdf .= "xref\n0 " . ( count( $objects ) + 1 ) . "\n0000000000 65535 f \n";
		for ( $i = 1; $i <= count( $objects ); $i++ ) {
			$pdf .= sprintf( "%010d 00000 n \n", $offsets[ $i ] );
		}
		$pdf .= "trailer\n<< /Size " . ( count( $objects ) + 1 ) . " /Root 1 0 R >>\nstartxref\n{$xref}\n%%EOF";

		return $pdf;
	}

	private function section( string $title, int $y ): string {
		return "0.07 0.09 0.13 rg BT /F2 13 Tf 62 {$y} Td (" . $this->pdf_text( $title ) . ") Tj ET\n";
	}

	private function line( string $label, string $value, int $y ): string {
		return "0.42 0.45 0.50 rg BT /F1 9 Tf 62 {$y} Td (" . $this->pdf_text( $label ) . ") Tj ET\n"
			. "0.07 0.09 0.13 rg BT /F1 10 Tf 165 {$y} Td (" . $this->pdf_text( $value ) . ") Tj ET\n";
	}

	private function wrapped_line( string $label, string $value, int $y, int $label_width, int $max_chars ): string {
		$output = "0.42 0.45 0.50 rg BT /F1 9 Tf 62 {$y} Td (" . $this->pdf_text( $label ) . ") Tj ET\n";
		$x      = 62 + $label_width;
		$chunks = $this->wrap_text( $this->plain_text( $value ), $max_chars );
		foreach ( array_slice( $chunks, 0, 4 ) as $index => $chunk ) {
			$line_y  = $y - ( $index * 14 );
			$output .= "0.07 0.09 0.13 rg BT /F1 10 Tf {$x} {$line_y} Td (" . $this->pdf_text( trim( $chunk ) ) . ") Tj ET\n";
		}

		return $output;
	}

	private function local_jpeg_logo(): ?array {
		$url = Settings::get( 'logo_url', '' );
		if ( ! $url ) {
			return null;
		}

		$uploads = wp_upload_dir();
		if ( empty( $uploads['baseurl'] ) || 0 !== strpos( $url, $uploads['baseurl'] ) ) {
			return null;
		}

		$path = str_replace( $uploads['baseurl'], $uploads['basedir'], $url );
		if ( ! is_readable( $path ) || ! preg_match( '/\.jpe?g$/i', $path ) ) {
			return null;
		}

		$size = getimagesize( $path );
		if ( ! $size ) {
			return null;
		}

		$bytes = file_get_contents( $path );
		if ( false === $bytes ) {
			return null;
		}

		$max_width    = 126;
		$max_height   = 42;
		$ratio        = min( $max_width / $size[0], $max_height / $size[1] );
		$draw_width   = $size[0] * $ratio;
		$draw_height  = $size[1] * $ratio;

		return array(
			'bytes'       => $bytes,
			'width'       => (int) $size[0],
			'height'      => (int) $size[1],
			'draw_width'  => $draw_width,
			'draw_height' => $draw_height,
		);
	}

	private function escape_pdf_text( string $text ): string {
		return str_replace( array( '\\', '(', ')' ), array( '\\\\', '\\(', '\\)' ), $text );
	}

	private function pdf_text( string $text ): string {
		$text = $this->plain_text( $text );

		if ( function_exists( 'iconv' ) ) {
			$converted = iconv( 'UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $text );
			if ( false !== $converted ) {
				return $this->escape_pdf_text( $converted );
			}
		}

		if ( function_exists( 'mb_convert_encoding' ) ) {
			return $this->escape_pdf_text( mb_convert_encoding( $text, 'Windows-1252', 'UTF-8' ) );
		}

		return $this->escape_pdf_text( preg_replace( '/[^\x20-\x7E]/', ' ', $text ) ?: '' );
	}

	private function plain_text( string $text ): string {
		$text = html_entity_decode( wp_strip_all_tags( $text ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
		$text = preg_replace( '/\s+/u', ' ', $text );

		return trim( $text ?: '' );
	}

	private function wrap_text( string $text, int $max_chars ): array {
		if ( '' === $text ) {
			return array( '' );
		}

		if ( function_exists( 'mb_strlen' ) && function_exists( 'mb_substr' ) ) {
			$chunks = array();
			$length = mb_strlen( $text, 'UTF-8' );

			for ( $offset = 0; $offset < $length; $offset += $max_chars ) {
				$chunks[] = mb_substr( $text, $offset, $max_chars, 'UTF-8' );
			}

			return $chunks;
		}

		return str_split( $text, $max_chars );
	}
}
