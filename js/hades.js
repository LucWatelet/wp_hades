/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function () {
	jQuery("span.cat-plus").click(function () {
		var sel=jQuery("#select_cat_model").clone(true);
    var cpt=Math.ceil(10000*Math.random());
		sel.attr('id','');
		sel.attr('name','taxocat['+jQuery(this).attr('term_id')+']['+ cpt +'][fk_cat_id]');
		sel.css('display','initial');
		sel.appendTo(jQuery(this).parent());
    jQuery(this).parent().append('<input class="hades_xpath_input" type="hidden" name="taxocat['+jQuery(this).attr('term_id')+']['+ cpt +'][xpath]" value="" />');
	});
	
	jQuery("span.cat-moins").click(function () {
		jQuery(this).parent("div.cat_button").remove();
	});	
  
	jQuery("span.cat-xpath").click(function () {
    jQuery(this).siblings("input.hades_xpath_input").attr("type","text");
    jQuery(this).remove();
	});
  
});