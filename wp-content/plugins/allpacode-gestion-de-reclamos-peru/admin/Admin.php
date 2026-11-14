<?php
/**
 * Admin menus and screens.
 *
 * @package AllpaCodeGestionReclamos
 */

namespace AllpaCodeGestionReclamos\Admin;

use AllpaCodeGestionReclamos\Capability;
use AllpaCodeGestionReclamos\EmailService;
use AllpaCodeGestionReclamos\Repository;
use AllpaCodeGestionReclamos\Settings;
use AllpaCodeGestionReclamos\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Admin {
	public function hooks(): void {
		add_action( 'admin_menu', array( $this, 'menu' ) );
		add_action( 'admin_init', array( $this, 'settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
		add_action( 'admin_post_allpacode_gr_update_claim', array( $this, 'update_claim' ) );
		add_action( 'admin_post_allpacode_gr_export', array( new Exporter(), 'export' ) );
		add_action( 'admin_post_allpacode_gr_delete_all_claims', array( $this, 'delete_all_claims' ) );
	}

	public function menu(): void {
		add_menu_page( 'Gestión de Reclamos', 'Gestión de Reclamos', Capability::MANAGE, 'allpacode-gr', array( $this, 'claims' ), 'dashicons-feedback', 26 );
		add_submenu_page( 'allpacode-gr', 'Reclamos', 'Reclamos', Capability::MANAGE, 'allpacode-gr', array( $this, 'claims' ) );
		add_submenu_page( 'allpacode-gr', 'Configuración', 'Configuración', Capability::MANAGE, 'allpacode-gr-settings', array( $this, 'settings_page' ) );
		add_submenu_page( 'allpacode-gr', 'Correos', 'Correos', Capability::MANAGE, 'allpacode-gr-emails', array( $this, 'emails_page' ) );
		add_submenu_page( 'allpacode-gr', 'Exportaciones', 'Exportaciones', Capability::MANAGE, 'allpacode-gr-exports', array( $this, 'exports_page' ) );
		add_submenu_page( 'allpacode-gr', 'Apariencia', 'Apariencia', Capability::MANAGE, 'allpacode-gr-appearance', array( $this, 'appearance_page' ) );
		add_submenu_page( 'allpacode-gr', 'Registros', 'Registros', Capability::MANAGE, 'allpacode-gr-logs', array( $this, 'logs_page' ) );
		add_submenu_page( 'allpacode-gr', 'Ayuda', 'Ayuda', Capability::MANAGE, 'allpacode-gr-help', array( $this, 'help_page' ) );
	}

	public function settings(): void {
		register_setting( 'allpacode_gr_settings', Settings::OPTION, array( Settings::class, 'sanitize' ) );
	}

	public function assets( string $hook ): void {
		if ( false === strpos( $hook, 'allpacode-gr' ) ) {
			return;
		}

		wp_enqueue_media();
		wp_enqueue_editor();
		wp_enqueue_style( 'allpacode-gr-admin', ALLPACODE_GR_URL . 'assets/css/admin.css', array(), ALLPACODE_GR_VERSION );
		wp_enqueue_script( 'allpacode-gr-admin', ALLPACODE_GR_URL . 'assets/js/admin.js', array(), ALLPACODE_GR_VERSION, true );
	}

	public function claims(): void {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- Read-only detail navigation parameter.
		$claim_id = isset( $_GET['claim_id'] ) ? absint( wp_unslash( $_GET['claim_id'] ) ) : 0;
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		if ( $claim_id ) {
			$this->claim_detail( $claim_id );
			return;
		}

		$table = new ClaimsListTable();
		$table->prepare_items();
		$stats = ( new Repository() )->stats();
		include ALLPACODE_GR_PATH . 'templates/admin-claims.php';
	}

	private function claim_detail( int $id ): void {
		$repository = new Repository();
		$claim      = $repository->find( $id );
		$logs       = $repository->logs( $id );
		include ALLPACODE_GR_PATH . 'templates/admin-claim-detail.php';
	}

	public function update_claim(): void {
		if ( ! current_user_can( Capability::MANAGE ) ) {
			wp_die( esc_html__( 'No autorizado.', 'allpacode-gestion-de-reclamos-peru' ) );
		}

		check_admin_referer( 'allpacode_gr_update_claim' );

		$id       = absint( wp_unslash( $_POST['claim_id'] ?? 0 ) );
		$status   = sanitize_key( wp_unslash( $_POST['status'] ?? 'pending' ) );
		$response = wp_kses_post( wp_unslash( $_POST['admin_response'] ?? '' ) );
		$notes    = sanitize_textarea_field( wp_unslash( $_POST['internal_notes'] ?? '' ) );

		$repository = new Repository();
		$before     = $repository->find( $id );

		$data = array(
			'status'          => isset( Utils::statuses()[ $status ] ) ? $status : 'pending',
			'admin_response'  => $response,
			'internal_notes'  => $notes,
		);

		if ( $response && 'answered' === $status ) {
			$data['responded_at'] = current_time( 'mysql' );
		}

		$repository->update_claim( $id, $data, 'case_reviewed' );

		if ( $before ) {
			$changes = $this->describe_changes( $before, $data );
			if ( $changes ) {
				foreach ( $changes as $change ) {
					$repository->log( $id, $change['action'], $change['message'], $change['context'] );
				}
			} else {
				$repository->log( $id, 'no_changes', __( 'Se guardó la ficha sin cambios en estado, respuesta o notas internas.', 'allpacode-gestion-de-reclamos-peru' ) );
			}
		}

		$claim = $repository->find( $id );
		$send_response = sanitize_text_field( wp_unslash( $_POST['send_response'] ?? '' ) );
		if ( $claim && $response && ! empty( $send_response ) ) {
			( new EmailService() )->send_response( $claim );
			$repository->log(
				$id,
				'response_sent',
				sprintf(
					/* translators: %s: claim status. */
					__( 'Se envió la respuesta al consumidor con estado: %s.', 'allpacode-gestion-de-reclamos-peru' ),
					Utils::statuses()[ $claim['status'] ] ?? $claim['status']
				)
			);
		}

		wp_safe_redirect( admin_url( 'admin.php?page=allpacode-gr&claim_id=' . $id . '&updated=1' ) );
		exit;
	}

	public function delete_all_claims(): void {
		if ( ! current_user_can( Capability::MANAGE ) ) {
			wp_die( esc_html__( 'No autorizado.', 'allpacode-gestion-de-reclamos-peru' ) );
		}

		check_admin_referer( 'allpacode_gr_delete_all_claims' );

		$confirmation = sanitize_text_field( wp_unslash( $_POST['confirmation'] ?? '' ) );
		if ( 'BORRAR TODO' !== $confirmation ) {
			wp_safe_redirect( admin_url( 'admin.php?page=allpacode-gr-settings&delete_error=confirmation' ) );
			exit;
		}

		$deleted = ( new Repository() )->delete_all_claims();
		$this->delete_receipt_pdfs();

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'           => 'allpacode-gr-settings',
					'deleted_claims' => (int) $deleted['claims'],
				),
				admin_url( 'admin.php' )
			)
		);
		exit;
	}

	private function delete_receipt_pdfs(): void {
		$upload = wp_upload_dir();
		$dir    = trailingslashit( $upload['basedir'] ) . 'allpacode-gestion-de-reclamos-peru';

		if ( ! is_dir( $dir ) || ! is_readable( $dir ) ) {
			return;
		}

		foreach ( glob( trailingslashit( $dir ) . '*.pdf' ) ?: array() as $file ) {
			if ( is_file( $file ) && is_writable( $file ) ) {
				wp_delete_file( $file );
			}
		}
	}

	private function describe_changes( array $before, array $after ): array {
		$changes = array();

		if ( (string) $before['status'] !== (string) $after['status'] ) {
			$changes[] = array(
				'action'  => 'status_changed',
				'message' => sprintf(
					/* translators: 1: old status, 2: new status. */
					__( 'El estado cambió de "%1$s" a "%2$s".', 'allpacode-gestion-de-reclamos-peru' ),
					Utils::statuses()[ $before['status'] ] ?? $before['status'],
					Utils::statuses()[ $after['status'] ] ?? $after['status']
				),
				'context' => array(
					'anterior' => $before['status'],
					'nuevo'    => $after['status'],
				),
			);
		}

		if ( trim( (string) $before['admin_response'] ) !== trim( (string) $after['admin_response'] ) ) {
			$changes[] = array(
				'action'  => 'response_changed',
				'message' => __( 'Se actualizó la respuesta dirigida al consumidor.', 'allpacode-gestion-de-reclamos-peru' ),
				'context' => array(
					'tenia_respuesta' => '' !== trim( (string) $before['admin_response'] ),
					'tiene_respuesta' => '' !== trim( (string) $after['admin_response'] ),
				),
			);
		}

		if ( trim( (string) $before['internal_notes'] ) !== trim( (string) $after['internal_notes'] ) ) {
			$changes[] = array(
				'action'  => 'notes_changed',
				'message' => __( 'Se actualizaron las notas internas del caso.', 'allpacode-gestion-de-reclamos-peru' ),
				'context' => array(
					'tenia_notas' => '' !== trim( (string) $before['internal_notes'] ),
					'tiene_notas' => '' !== trim( (string) $after['internal_notes'] ),
				),
			);
		}

		return $changes;
	}

	public function settings_page(): void {
		include ALLPACODE_GR_PATH . 'templates/admin-settings.php';
	}

	public function emails_page(): void {
		include ALLPACODE_GR_PATH . 'templates/admin-emails.php';
	}

	public function exports_page(): void {
		include ALLPACODE_GR_PATH . 'templates/admin-exports.php';
	}

	public function appearance_page(): void {
		include ALLPACODE_GR_PATH . 'templates/admin-appearance.php';
	}

	public function logs_page(): void {
		$logs = ( new Repository() )->logs( 0, 200 );
		include ALLPACODE_GR_PATH . 'templates/admin-logs.php';
	}

	public function help_page(): void {
		include ALLPACODE_GR_PATH . 'templates/admin-help.php';
	}
}
