<?php
require_once('../../../../wp-blog-header.php');
header('HTTP/1.1 200 OK');
global $wpdb;
global $woocommerce;
$pid=$_GET['pid'];
$wqo_v=0;
$product = get_product($pid);
$product_description = get_post($pid)->post_content;
if($wqo_v==1){
  $product_description=get_post_meta($pid, '_variation_description');  
  $product_description=$product_description[0];
  if(empty($product_description)){
    $product_description=$product->post->post_content;
  }
}
if (has_post_thumbnail($pid)){ 
          $imgUlr = wp_get_attachment_url( get_post_thumbnail_id($pid) );
          $src = '<img src="'.$imgUlr.'" alt="Placeholder" width="200" />';
        } else {
          //$imgUlr=WQO_BASE_URL.'/images/placeholder.png'; 
          $imgUlr=wc_placeholder_img_src();
          $src = '<img src="'.$imgUlr.'" alt="Placeholder" width="200"  />';
        }
?>
<div style="margin-top: 2px; position: relative;" class="ajax-text-and-image white-popup-block">
	<style>
	.ajax-text-and-image {
		max-width:700px; margin: 20px auto; background: #FFF; padding: 0; line-height: 0;
	}
	.ajcol {
		width: 40%; float:left;
	}
	.ajcol img {
		width: 100%; height: auto;
	}
	@media all and (max-width:30em) {
		.ajcol { 
			width: 100%;
			float:none;
		}
	}
	</style>
	<div class="ajcol">
    <?php echo $src;?>
	</div>
	<div style="line-height: 1.231;" class="ajcol">
		<div style="padding: 1em">      
			<h2><?php echo $product->get_title();?></h2>
      <h3><?php echo wc_get_template( 'loop/price.php' );?></h3>
      <div class="wfm_product_meta">
        <?php do_action( 'woocommerce_product_meta_start' ); ?>
        <?php if ( $product->is_type( array( 'simple', 'variable' ) ) && get_option( 'woocommerce_enable_sku' ) == 'yes' && $product->get_sku() ) : ?>
          <span itemprop="productID" class="sku_wrapper"><?php _e( 'SKU:', 'woocommerce' ); ?> <span class="sku"><?php echo $product->get_sku(); ?></span>.</span>
        <?php endif; ?>
        <?php
          //$size = sizeof( get_the_terms( $_GET['pid'], 'product_cat' ) );
          //echo $product->get_categories( ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', $size, 'woocommerce' ) . ' ', '.</span>' );
        //------------------------------------------
        echo '<br />';
        $args = array(
            'orderby' => 'name',
        );
        $product_cats = wp_get_post_terms( $_GET['pid'], 'product_cat', $args );
        $cat_count = sizeof( $product_cats );

        echo '<span class="posted_in">' . _n( 'Category:', 'Categories:', $cat_count, 'woocommerce' );
        for ( $i = 0; $i < $cat_count; $i++ ) {
            echo $product_cats[$i]->name;
            echo ( $i === $cat_count - 1 ) ? '' : ', ';
        }
        echo '</span>';       
        
        //------------------------------------------
        
        
        ?>
        <?php
          //$size = sizeof( get_the_terms( $_GET['pid'], 'product_tag' ) );
          //echo $product->get_tags( ', ', '<br /><span class="tagged_as">' . _n( 'Tag:', 'Tags:', $size, 'woocommerce' ) . ' ', '.</span>' );
        
        //------------------------------------------
        
        $product_tags = wp_get_post_terms( $_GET['pid'], 'product_tag', $args );
        $tag_count = sizeof( $product_tags );

        echo '<span class="posted_in">' . _n( 'Category:', 'Categories:', $tag_count, 'woocommerce' );
        for ( $i = 0; $i < $tag_count; $i++ ) {
            echo $product_tags[$i]->name;
            echo ( $i === $tag_count - 1 ) ? '' : ', ';
        }
        echo '</span>';        
        //------------------------------------------
        ?>
        <?php do_action( 'woocommerce_product_meta_end' ); ?>
      </div>		    
          <?php
              echo '<div class="wfm_popup_con_3">'.$product_description.'</div>';
          
          ?>        
		</div>
	</div>
	<div style="clear:both; line-height: 0;"></div>
</div>