<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package woodmart
 */

$woodmart_loop         = woodmart_loop_prop( 'woodmart_loop' );
$is_large_image        = 'large_image' === woodmart_get_opt( 'single_post_design' );
$post_format           = get_post_format();
$thumb_classes         = '';
$gallery_slider        = apply_filters( 'woodmart_gallery_slider', true );
$gallery               = array();
$gallery_inner_classes = '';

$classes = array(
	'post-single-page',
);

if ( is_singular( 'post' ) && $is_large_image ) {
	$classes[] = 'post-single-large-image';
}

if ( ! get_the_title() ) {
	$classes[] = 'post-no-title';
}

if ( 'gallery' === $post_format && $gallery_slider ) {
	$gallery = get_post_gallery( false, false );

	if ( ! empty( $gallery['src'] ) ) {
		$thumb_classes         .= ' wd-carousel-container wd-post-gallery';
		$gallery_inner_classes .= ' color-scheme-light';
	}
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
	<header class="wd-single-post-header">
		<?php if ( ( has_post_thumbnail() || ! empty( $gallery['src'] ) ) && ! post_password_required() && ! is_attachment() && woodmart_loop_prop( 'parts_media' ) ) : ?>
			<div class="wd-post-image<?php echo esc_attr( $thumb_classes ); ?>">
				<?php if ( 'gallery' === $post_format && $gallery_slider && ! empty( $gallery['src'] ) ) : ?>
					<?php
					woodmart_enqueue_js_library( 'swiper' );
					woodmart_enqueue_js_script( 'swiper-carousel' );
					woodmart_enqueue_inline_style( 'swiper' );
					woodmart_enqueue_inline_style( 'blog-mod-gallery' );
					?>
					<div class="wd-carousel-inner<?php echo esc_attr( $gallery_inner_classes ); ?>">
						<div class="wd-carousel wd-grid"<?php echo woodmart_get_carousel_attributes( array( 'autoheight' => 'yes' ) ); //phpcs:ignore ?>>
							<div class="wd-carousel-wrap">
								<?php
								foreach ( $gallery['src'] as $src ) {
									if ( preg_match( '/data:image/is', $src ) ) {
										continue;
									}
									?>
										<div class="wd-carousel-item">
											<?php echo wp_kses_post( apply_filters( 'woodmart_image', '<img src="' . esc_url( $src ) . '" />' ) ); ?>
										</div>
									<?php
								}
								?>
							</div>
						</div>
						<?php woodmart_get_carousel_nav_template( ' wd-post-arrows wd-pos-sep wd-custom-style' ); ?>
					</div>
				<?php elseif ( ! $is_large_image ) : ?>
					<?php the_post_thumbnail(); ?>
				<?php endif ?>
			</div>
		<?php endif; ?>
	</header>
	
	<div class="tw-w-full tw-max-w-5xl 2xl:tw-max-w-[1350px] 3xl:tw-max-w-[1450px] tw-mx-auto tw-px-[5%] tw-pt-8 tw-pb-2">
		<nav class="custom-blog-breadcrumb tw-text-[#777] tw-text-sm tw-font-medium">
			<a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/blog/' ) ); ?>" class="tw-text-[#777] hover:tw-text-[#0CC] tw-transition-colors tw-text-base lg:tw-text-lg 3xl:tw-text-xl">Blog</a>
			<span class="tw-mx-2 tw-text-lg 3xl:tw-text-xl tw-text-[#0CC] tw-font-bold">&gt;</span>
			<span class="tw-text-[#009191] tw-font-semibold tw-text-base lg:tw-text-lg 3xl:tw-text-xl"><?php the_title(); ?></span>
		</nav>
		<hr class="tw-border-t tw-border-gray-400 tw-mt-4 tw-mb-0">
	</div>

	<?php if ( ! $is_large_image ) : ?>
		<?php if ( woodmart_loop_prop( 'parts_title' ) ) : ?>
			<div class="tw-w-full tw-flex tw-flex-row tw-items-center tw-justify-center tw-bg-white tw-max-w-5xl xl:tw-max-w-7xl 3xl:tw-max-w-[1450px] tw-mx-auto tw-px-[5%] tw-py-8 xs:tw-py-12 lg:tw-py-14">
				<h1 class="xxs:tw-text-2xl xs:tw-text-3xl sm:tw-text-4xl xl:tw-text-[42px] 3xl:tw-text-5xl tw-font-bold tw-flex tw-items-center tw-justify-center lg:tw-justify-start tw-gap-3 tw-text-[#0CC] tw-mb-0 tw-text-center">
					<span class="tw-z-20">
						<svg xmlns="http://www.w3.org/2000/svg" width="23" height="30" viewBox="0 0 36 47" fill="none">
							<path d="M35.1321 23.0795C35.1321 19.6432 29.4392 18.8226 27.1312 18.1046C23.7462 17.0788 23.3872 17.3353 22.6179 13.3861C21.9511 9.94982 23.0795 0 17.5404 0C11.6936 0 13.6425 13.1809 12.0013 15.7966C9.64209 19.5406 0 17.3865 0 23.0795C0 28.3621 8.25732 26.4645 10.873 29.3879C13.3861 32.2087 11.8475 46.1077 17.5404 46.1077C22.7205 46.1077 21.7973 37.3375 22.5666 34.0551C23.1821 31.2855 23.7462 29.8495 26.208 28.4134C28.516 27.0799 35.1321 26.9773 35.1321 23.0282V23.0795Z" fill="#007070"/>
						</svg>
					</span> 
					<?php the_title(); ?>
					<span>
						<svg xmlns="http://www.w3.org/2000/svg" width="23" height="30" viewBox="0 0 36 47" fill="none">
							<path d="M35.1321 23.0795C35.1321 19.6432 29.4392 18.8226 27.1312 18.1046C23.7462 17.0788 23.3872 17.3353 22.6179 13.3861C21.9511 9.94982 23.0795 0 17.5404 0C11.6936 0 13.6425 13.1809 12.0013 15.7966C9.64209 19.5406 0 17.3865 0 23.0795C0 28.3621 8.25732 26.4645 10.873 29.3879C13.3861 32.2087 11.8475 46.1077 17.5404 46.1077C22.7205 46.1077 21.7973 37.3375 22.5666 34.0551C23.1821 31.2855 23.7462 29.8495 26.208 28.4134C28.516 27.0799 35.1321 26.9773 35.1321 23.0282V23.0795Z" fill="#007070"/>
						</svg>
					</span>
				</h1>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( woodmart_loop_prop( 'parts_meta' ) ) : ?>
		<?php
		woodmart_enqueue_inline_style( 'blog-mod-author' );
		woodmart_enqueue_inline_style( 'blog-mod-comments-button' );
		?>
		<div class="wd-post-meta">
			<div class="tw--mt-8 tw-text-[#161616] tw-w-full tw-flex tw-flex-row tw-justify-center tw-text-center tw-text-base lg:tw-text-lg 3xl:tw-text-xl tw-max-w-5xl 3xl:tw-max-w-6xl tw-mx-auto tw-px-[5%]">
				<?php woodmart_post_meta_author( true, 'long', true, 22 ); ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( woodmart_loop_prop( 'parts_text' ) ) : ?>
		<div class="wd-entry-content tw-text-base lg:tw-text-lg 3xl:tw-text-xl tw-max-w-5xl xl:tw-max-w-7xl 3xl:tw-max-w-[1450px] tw-mx-auto tw-px-[5%] tw-py-8 lg:tw-py-12 3xl:tw-py-14">
			<?php woodmart_get_content( woodmart_loop_prop( 'parts_btn' ), true ); ?>

			<?php
				wp_link_pages(
					array(
						'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'woodmart' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
					)
				);
			?>
		</div>
	<?php endif; ?>

</article>

<?php if ( get_the_author_meta( 'description' ) ) : ?>
	<?php get_template_part( 'author-bio' ); ?>
<?php endif; ?>

<?php
// Increase loop count.
woodmart_set_loop_prop( 'woodmart_loop', $woodmart_loop + 1 );
