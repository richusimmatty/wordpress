<?php
function wcp_custom_degin_panel2(){ 
  $cnt=1;
  $cnt_data=1;
  ?>
  <table>
  <tr>
    <td><strong>Enable Custom Field: &emsp;</strong></td>
    <td>
      <input type="checkbox" name="wcf_status" id="wcf_status" />
    </td>
  </tr>
  </table>  
  <table class="rwd-table frwd-table input_fields_wrap rwd-table2">
    <tr>
      <th>Field Name</th>
      <th>Field Value & Price</th>
      <th>Field Type</th>
    </tr>    
    <tr>
      <td data-th="Name">
        <input type="text" class="wpr_mf_text" placeholder="Field Label" name="wpr_mf_text[1][1]"/>
      </td>
      <td data-th="value" >
        <input type="text" class="wpr_mf_text" placeholder="value:price|value:price" name="wpr_mf_text[1][2]" value="" />
      </td>
      <td data-th="Type">
          <select  class="wpr_mf_ddl" name="wpr_mf_text[1][3]">
            <option value="chk_box">Checkbox</option>
            <option value="radio">Radio</option>
            <option value="ddl">DropDownList</option>
          </select>          
        </td> 
    </tr>    
  </table>  
  <table>
    <tr>
      <td data-th="Manufacturer" align="right"><span class="wpr_f_add_field ">Add More Fields</span><br /></td>
    </tr>    
  </table>  
  <input type="hidden" name="wpr_frm_submit" value="1" />
  <input type="hidden" name="wpr_frm_cnt" id="wpr_frm_cnt" value="<?php echo $cnt;?>" />
<?php
}

?>