jQuery(document).ready(function() {
    var max_fields      = 11;
    var wrapper         = jQuery(".input_fields_wrap");
    var add_button      = jQuery(".wpr_add_field");
    var x = 4;
    jQuery(add_button).click(function(e){
      var wpr_frm_data = JSON.parse(wpr_frm_data_tr);
      var td_data='';
      for ( var i = 1; i <= wpr_cnt_frm; i++ ) {        
        td_data=td_data+ '<td data-th="'+ wpr_frm_data[i] +'"><input type="text" class="wpr_mf_text" name="wpr_mf_text['+ x +'][]"/></td>';
      }
        e.preventDefault();
        if(x < max_fields){
            x++;            
            jQuery(wrapper).append('<tr class="wpr_tr'+ x +'"> '+ td_data +'<td><span class="wpr_remove" >Remove</span></td></tr>');
        }
    });
    
    jQuery(wrapper).on('click','.wpr_remove',function(){      
        jQuery(this).parent().parent().remove();
        x=x-1;
    });
});

function wpr_remove_tr(id){
  alert(x);
  jQuery('.wpr_tr'+ id +'').remove();
  x=x-1;
}

jQuery(document).ready(function() {
    var wpr_f_max_fields      = 15;
    var wpr_f_wrapper         = jQuery(".input_fields_wrap");
    var frm_add_button      = jQuery(".wpr_f_add_field");
    var x = jQuery("#wpr_frm_cnt").val();
    jQuery(frm_add_button).click(function(e){
        e.preventDefault();
        if(x < wpr_f_max_fields){
            x++;
            jQuery(wpr_f_wrapper).append('<tr class="wpr_tr'+ x +'"><td data-th="Name"><input type="text" class="wpr_mf_text" placeholder="Field Label" name="wpr_mf_text['+ x +'][1]"/></td><td data-th="Value"><input type="text" class="wpr_mf_text" placeholder="value:price|value:price" name="wpr_mf_text['+ x +'][2]"/></td><td data-th="Type"><select  class="wpr_mf_ddl" name="wpr_mf_text['+ x +'][3]"><option value="chk_box">Checkbox</option><option value="radio">Radio</option><option value="ddl">DropDownList</option></select>&nbsp;<span class="wpr_f_remove" >Remove</span></td></tr>');  
        }
    });
    jQuery(wpr_f_wrapper).on('click','.wpr_f_remove',function(){        
        jQuery(this).parent().parent().remove();
        x=x-1;
    });
});
//----------front-end js---------
