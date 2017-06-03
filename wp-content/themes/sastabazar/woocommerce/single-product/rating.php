<?php
/**
 * Single Product Rating
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.3.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
	return;
}

$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();
$average      = $product->get_average_rating();

$review_link = apply_filters('sanzo_woocommerce_review_link_filter', '#reviews');

if ( $rating_count > 0 ) : ?>

	<div class="woocommerce-product-rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
		<div class="star-rating" title="<?php printf( esc_html__( 'Rated %s out of 5', 'sanzo' ), $average ); ?>">
			<span style="width:<?php echo ( ( $average / 5 ) * 100 ); ?>%">
				<strong itemprop="ratingValue" class="rating"><?php echo esc_html( $average ); ?></strong> <?php printf( wp_kses( __( 'out of %s5%s', 'sanzo' ), array( 'span' => array( 'itemprop' => array() ) ) ), '<span itemprop="bestRating">', '</span>' ); ?>
				<?php printf( _n( 'based on %s customer rating', 'based on %s customer ratings', $rating_count, 'sanzo' ), '<span itemprop="ratingCount" class="rating">' . $rating_count . '</span>' ); ?>
			</span>
		</div>
		<?php if ( comments_open() ) : ?><a href="<?php echo esc_url($review_link); ?>" class="woocommerce-review-link" rel="nofollow"><?php printf( _n( '%s comment', '%s comments', $review_count, 'sanzo' ), '<span itemprop="reviewCount" class="count">(' . zeroise($review_count, 2) . ')</span>' ); ?></a><?php endif; ?>
	</div>
	
<?php else: ?>

	<div class="woocommerce-product-rating">
		<?php if ( comments_open() ) : ?><a href="<?php echo esc_url($review_link); ?>" class="woocommerce-review-link" rel="nofollow"><?php esc_html_e( 'Write your comment', 'sanzo' ); ?></a><?php endif; ?>
	</div>
	
<?php endif; ?>
