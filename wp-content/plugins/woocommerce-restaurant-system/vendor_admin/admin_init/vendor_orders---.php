<?php
function wvs_woocommerce_order_status_completed($order_id){
   global $wpdb;
   $table=$wpdb->prefix.'woocommerce_vendor';
   $sql="UPDATE $table SET vendor_product_delivared='Delivered' WHERE vendor_order_id=$order_id";
   $wpdb->query($wpdb->prepare($sql));

}
//add_action( 'woocommerce_order_status_changed', 'wvs_woocommerce_order_status_completed',10,1 );
add_action( 'woocommerce_order_status_changed', 'wvs_woocommerce_order_status_completed', 10, 2 );

function wvs_vendor_orders_details() {
  global $woocommerce, $post, $wpdb;
  $args = array(
      'post_type' => 'vendor_product',
      'post_status' => 'publish',
      'posts_per_page' => -1
  );
  $posts = new WP_Query($args);
  $posts = $posts->posts;
  $option_arr = array();
  if (!empty($posts)) {
    foreach ($posts as $pst) {
      $vendor_list = get_post_meta($pst->ID, '_vendor_company', true);
      if ($vendor_list != '') {
        $option_arr[$pst->ID] = __($vendor_list, 'woocommerce');
      } else {
        $option_arr[$pst->ID] = __($pst->post_title, 'woocommerce');
      }
    }
  }
  //------------------------After post ---------------------------   

$page_url=get_admin_url().'edit.php?post_type=vendor_product&page=vendor_orders';
  if (empty($option_arr)) {
    echo '<div class="error notice" style="padding:20px;">You do not have any restaurant to view order.</div>';
  } else {
    ?>
    <div class="wrap">  
      <form method="get" action="<?php echo $page_url;?>">
        <table class="widefat">
          <input type="hidden" name="post_type" value="vendor_product" /> 
          <input type="hidden" name="page" value="vendor_orders" />
          <tr><td colspan="2"><h2><?php _e("Pay to the Restaurant for Orders", "woocommerce-vendor-setup"); ?></h2></td></tr>
          <tr>
            <td width="200px">Restaurant</td>
            <td>
              <select name="vid" id="restaurant">
                <option value="">Select Restaurant</option>
                <?php
                foreach ($option_arr as $key => $value) {
                  $select = '';
                  if (isset($_GET['vid']) && $_GET['vid'] == $key) {
                    $select = 'selected="selected"';                    
                  }
                  echo '<option value="' . $key . '" ' . $select . '>' . $value . '</option>';
                }
                ?>
              </select>
            </td>
          </tr>
          
          <tr>
            <td>Payment Status</td>
            <td>
              <select name="order_type">                
                <option value="2" <?php if (isset($_GET['order_type']) && $_GET['order_type'] == '2') echo 'selected="selected"'; ?>>Pending</option>
                <option value="3" <?php if (isset($_GET['order_type']) && $_GET['order_type'] == '3') echo 'selected="selected"'; ?>>Success </option>
                <option value="1" <?php if (isset($_GET['order_type']) && $_GET['order_type'] == '1') echo 'selected="selected"'; ?>>All</option>
              </select>            
            </td>
          </tr>
          <tr>
            <td>Orders Status</td>
            <td>
              <select name="order_status">
                <option value="1" <?php if(isset($_GET['order_status']) && $_GET['order_status'] == '1') echo 'selected="selected"'; ?>>All</option>
                <option value="2" <?php if(isset($_GET['order_status']) && $_GET['order_status'] == '2') echo 'selected="selected"'; ?>>Delivered</option>
                <option value="3" <?php if(isset($_GET['order_status']) && $_GET['order_status'] == '3') echo 'selected="selected"'; ?>>Pending</option>
              </select>            
            </td>
          </tr>
          <tr>
            <td colspan="2"><input type="submit" name="Submit"  class="button-primary" value="<?php _e("Search", "woocommerce-vendor-setup"); ?>" /></td>
          </tr>
        </table>    
      </form>
      </div>
      <?php
      //--------------------------------Data After Submit--------------------------------
      $vid='';
      $type='';
      $order_status='';
      if(isset($_GET['vid'])){          
          $vid=$_GET['vid'];
          $type=$_GET['order_type'];
          $order_status=$_GET['order_status'];
      }
      
      if ($vid!='') {        
          //$vid = $_POST['restaurant'];
          $uid = get_post_meta($vid, '_vendor_user_id', true);
          $table = $wpdb->prefix . 'woocommerce_vendor';
          $pstatus='';
          $ostatus='';
          if($type==2){
            $pstatus="and vendor_send_money_status='Pending'";            
          }else if($type==3){            
            $pstatus="and vendor_send_money_status='Success'";
          }
          if($order_status==2){
            $ostatus=" and vendor_product_delivared='Delivered'";            
          }else if($order_status==3){            
            $ostatus=" and vendor_product_delivared='Pending'";
          }
          $sql="SELECT * FROM " . $table . "  where vendor_vendor_id = $vid $pstatus $ostatus";          
          $vendor_records = $wpdb->get_results($sql);
          
          if (count($vendor_records) > 0) {
            tt_render_list_page($vid,$type, $order_status);          
          } else {
            echo '<div class="widefat" style="padding:10px; color:#E8A737; width:50%; background:#FFFFFF;">Sorry no record found.</div>';
          }
      }
    }
  }
  
function tt_render_list_page($vid,$type, $order_status){
  //ob_start();
    $testListTable = new Vendor_Record_List_Table();    
    $testListTable->wvs_vendor_prepare_items($vid, $type, $order_status);  
   
    ?>
    <div class="wrap">        
        <h2>Order Info</h2>        
        <form id="movies-filter" method="get">
            <input type="hidden" name="post_type" value="<?php echo $_REQUEST['post_type'] ?>" /> 
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <input type="hidden" name="vid" value="<?php echo $vid ?>" />
            <input type="hidden" name="order_type" value="<?php echo $type ?>" />
            <?php $testListTable->display() ?>
        </form> 
    <div class="clear"></div>    
    </div>
    <div class="clear"></div>
    <?php
   
  /*$output_string = ob_get_contents();
  ob_end_clean();
  return $output_string; */
}
  
  
  if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/************************** CREATE A PACKAGE CLASS ******************************/
class Vendor_Record_List_Table extends WP_List_Table {
  
    function __construct(){
        global $status, $page;
                
        
        parent::__construct( array(
            'singular'  => 'vendor_order_record',     //singular name of the listed records
            'plural'    => 'vendor_order_records',    //plural name of the listed records
            'ajax'      => True        //does this table support ajax?
        ) );        
    }
    
    function column_default($item, $column_name){
        switch($column_name){
            case 'vendor_id':
            case 'vendor_order_id':
                return $item[$column_name];  
            case 'vendor_product_name':
                return $item[$column_name];
            case 'vendor_product_qty':
                return $item[$column_name];
            case 'vendor_product_unit_price':
                return $item[$column_name];
            case 'vendor_product_amount':
                return $item[$column_name];
            case 'vendor_percent':
                return $item[$column_name];
            case 'vendor_amount':
                return $item[$column_name];
            case 'vendor_send_money_status':
                return $item[$column_name];
            case 'vendor_product_delivared':
                return $item[$column_name];
            case 'vendor_amount':
                return $item[$column_name];
            case 'vendor_order_date':
                return $item[$column_name];
            case 'vendor_send_money_date':
                return $item[$column_name];  
            default:
                return print_r($item,true);
        }
    }

    function column_title($item){        
        //Build row actions
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&vendor_id=%s">Edit</a>',$_REQUEST['page'],'edit',$item['vendor_id']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&vendor_id=%s">Delete</a>',$_REQUEST['page'],'delete',$item['vendor_id']),
        );        
        //Return the title contents
        return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
            /*$1%s*/ $item['vendor_id'],
            /*$2%s*/ $item['vendor_id'],
            /*$3%s*/ $this->row_actions($actions)
        );
    }

    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['vendor_id']                //The value of the checkbox should be the record's id
        );
    }

    function get_columns(){
        $columns = array(
            'cb'                            => '<input type="checkbox" />', //Render a checkbox instead of text
            'vendor_id'                     => 'ID',
            'vendor_order_id'               => 'Order ID',
            'vendor_product_name'           => 'Name',
            'vendor_product_qty'            => 'Qty',
            'vendor_product_unit_price'     => 'Unit Price',            
            'vendor_product_amount'         => 'Total',
            'vendor_percent'                => 'Percent',
            'vendor_amount'                 => 'Percent Amount',
            'vendor_send_money_status'      => 'Payment Status',
            'vendor_product_delivared'      => 'Order Status',
            'vendor_amount'                 => 'Percent Amount',
            'vendor_order_date'             => 'Order Date',
            'vendor_send_money_date'        => 'Payment date'
        );
        return $columns;
    }
    function get_columns2(){
        $columns = array(            
            'vendor_id'                     => 'ID',
            'vendor_order_id'               => 'Order ID',
            'vendor_product_name'           => 'Name',
            'vendor_product_qty'            => 'Qty',
            'vendor_product_unit_price'     => 'Unit Price',            
            'vendor_product_amount'         => 'Total',
            'vendor_percent'                => 'Percent',
            'vendor_amount'                 => 'Percent Amount',
            'vendor_send_money_status'      => 'Payment Status',
            'vendor_product_delivared'      => 'Order Status',
            'vendor_amount'                 => 'Percent Amount',
            'vendor_order_date'             => 'Order Date',
            'vendor_send_money_date'        => 'Payment date'
        );
        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'vendor_id'     => array('vendor_id',false),     //true means it's already sorted
            'vendor_order_id'    => array('vendor_order_id',false),
            'vendor_product_name'    => array('vendor_product_name',false),
            'vendor_product_qty'  => array('vendor_product_qty',false),
            'vendor_send_money_status'  => array('vendor_send_money_status',false),
            'vendor_product_delivared'  => array('vendor_product_delivared',false)
        );
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(
            'pay'    => 'Pay'
        );
        return $actions;
    }

    function process_bulk_action() {      
      if(isset($_GET['action'])){
         if( 'pay'==$_GET['action'] ) { 
          if(isset($_GET['vendor_order_record'])){
            wvs_vendor_payment($_GET['vendor_order_record']);
          }            
        }
      }      
    }
    protected function set_pagination_args( $args ) {
    $args = wp_parse_args( $args, array(
      'total_items' => 0,
      'total_pages' => 0,
      'total' => 0,  
      'per_page' => 0,
    ) );

    if ( !$args['total_pages'] && $args['per_page'] > 0 )
      $args['total_pages'] = ceil( $args['total_items'] / $args['per_page'] );      
    
    // Redirect if page number is invalid and headers are not already sent.
    if ( ! headers_sent() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) && $args['total_pages'] > 0 && $this->get_pagenum() > $args['total_pages'] ) {
      wp_redirect( add_query_arg( 'paged', $args['total_pages'] ) );
      exit;
    }
    $this->_pagination_args = $args;
  }
    
    function wvs_vendor_prepare_items($vid, $order_type, $order_status) {      
        global $wpdb;       
        
        $per_page = 2;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();        
        $table=$wpdb->prefix.'woocommerce_vendor';
          $pstatus='';
          $ostatus='';
          if($order_type==2){
            $pstatus="and vendor_send_money_status='Pending'";            
          }else if($order_type==3){            
            $pstatus="and vendor_send_money_status='Success'";
          }
          if($order_status==2){
            $ostatus=" and vendor_product_delivared='Delivered'";            
          }else if($order_status==3){            
            $ostatus=" and vendor_product_delivared='Pending'";
          }
          $sql="SELECT * FROM " . $table . "  where vendor_vendor_id = $vid $pstatus $ostatus"; 
        
        $data = $wpdb->get_results( $sql, 'ARRAY_A' );
                
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'vendor_id';
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc';
            $result = strcmp($a[$orderby], $b[$orderby]);
            return ($order==='asc') ? $result : -$result;
        }
        usort($data, 'usort_reorder');

        $current_page = $this->get_pagenum();
        $total_items = count($data);
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        $this->items = $data;
        
        $this->set_pagination_args( array(            
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items/$per_page)
        ));
    }
}

function wvs_vendor_payment($vendors){
  require('paypal_config.php');
  global $wpdb;
  $vid=$_GET['vid'];
  $uid = get_post_meta($vid, '_vendor_user_id', true);
  $paypal_email= get_post_meta($vid, '_vendor_paypal', true);
  $vendor_paypal_username = urlencode(get_option('masspay_user_name'));
  $vendor_paypal_password = urlencode(get_option('masspay_api_pass'));
  $vendor_paypal_signature = urlencode(get_option('masspay_api_signature'));
  $vendor_paypal_api_mode = get_option('masspay_api_mode');
  ///-------------------  
  $version = urlencode(VERSION);
  $emailSubject = urlencode(EMAIL_SUBJECT);
  $receiverType = urlencode(RECEIVER_TYPE);
  $currency = urlencode(CURRENCY);
  $nvpStr="&EMAILSUBJECT=$emailSubject&RECEIVERTYPE=$receiverType&CURRENCYCODE=$currency";
  
  //sandbox
  //live
  if($vendor_paypal_username==''|| $vendor_paypal_password=='' || $vendor_paypal_signature=='' ||  $vendor_paypal_api_mode==''){
    echo '<div class="widefat" style="padding:10px; margin-top:50px; color:#FF0000; width:95%; background:#FFFFFF;">Please set PayPal Mass Payment Settings</div>';
    return false; 
  }  
  if(empty($paypal_email)){
    echo '<div class="widefat" style="padding:10px; margin-top:50px; color:#FF0000; width:95%; background:#FFFFFF;">Please set paypal email for this restaurant.</div>';
    return false;    
  }  
  $table=$wpdb->prefix.'woocommerce_vendor';
  $amount=0;
  foreach ($vendors as $key => $value){
    $id=$value;   
    $sql = "SELECT * FROM $table where vendor_id = $id";
    $data = $wpdb->get_row( $sql, 'ARRAY_A' );
    if($data['vendor_send_money_status']=='Pending'){      
      $amount=$data['vendor_product_amount']-$data['vendor_amount'];
      $unique_id = substr(md5(time() * rand()),0,5);
      $note='Order Id:'.$data['vendor_order_id'].', Product Name:'.$data['vendor_product_name'];
      $nvpStr .= "&L_EMAIL0=$paypal_email&L_Amt0=$amount&L_UNIQUEID0=$unique_id&L_NOTE0=$note";
      $httpParsedResponseAr = wvs_paypal_post_data('MassPay', $nvpStr, $vendor_paypal_username, $vendor_paypal_password,$vendor_paypal_signature, $vendor_paypal_api_mode, $version);
      if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {        
        $wpdb->update($table, array('vendor_send_money_status' => 'Success'), array( 'vendor_id' => $id ));        
      }else {        
      }     
    }
  }  
}