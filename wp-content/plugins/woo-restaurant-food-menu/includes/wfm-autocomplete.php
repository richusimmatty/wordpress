<?php
require_once('../../../../wp-blog-header.php');
header('HTTP/1.1 200 OK');
global $wpdb,$table_prefix;
if(isset($_GET['q'])){
  $q=$_GET['q'];
}else{
  $q='';
}
echo get_permalink();
die('++');
$table_res='tag';
$my_data=$q;
$args = array( 'post_type' => 'vendor_product',
	             'meta_query' => array(
               'relation' => 'OR',    
				        array(
				            'key' => '_vendor_name',
				            'value' => $my_data,
                    'compare' => 'LIKE'
				        ),
				        array(
				            'key' => '_vendor_zip',
				            'value' => $my_data,
                    'compare' => 'LIKE'
				        ),
                array(
				            'key' => '_vendor_address',
				            'value' => $my_data,
                    'compare' => 'LIKE'
				        ),
                array(
				            'key' => '_vendor_country',
				            'value' => $my_data,
                    'compare' => 'LIKE'
				        ),
                array(
				            'key' => '_vendor_state',
				            'value' => $my_data,
                    'compare' => 'LIKE'
				        )   
				    ),
				   	'orderby' => 'title',
					'order' => 'ASC',
	        'posts_per_page' => 300 );
	$loop = new WP_Query( $args );
  $u=0;
  while ( $loop->have_posts() ) : $loop->the_post();    
	$r=get_post_meta($post->ID,'_vendor_name');  
  if($u<2){
    echo $r[0]."\n";
  }else{
    echo "<a onclick='wfm_search_form_submit();'>more..</a>\n";
    break;
  }
  $u++;
	endwhile;
  wp_reset_query();