/*
$(function() {

	$(".form-text-parent :checkbox").click(function() {
		if ($(this).is(':checked')) {
			$(".form-text-children :checkbox").prop("checked", true);
			$(".form-text-grandchild :checkbox").prop("checked", true);
		}else{
	        $(".form-text-children :checkbox").prop("checked", false);
			$(".form-text-grandchild :checkbox").prop("checked", false);
	    }
	});
	$(".form-text-children :checkbox").click(function() {
		if ($(this).is(':checked')) {
			$(".form-text-grandchild :checkbox").prop("checked", true);
		}else{
			$(".form-text-grandchild :checkbox").prop("checked", false);
	    }
	});

	//孫要素が一つでも表示になっていたら、一個上の子要素はチェックをする。
	$(".form-text-grandchild :checkbox").click(function() {
		if ($(this).is(':checked').length > 0) {
			$(".form-text-children :checkbox").prop("checked", true);
		}
	});
	//子要素が一つでも表示になっていたら、最上位の親要素にチェックを入れる。

});
*/
$(function(){
	$('ul.sortable').sortable({
        'tolerance':'intersect',
        'cursor':'pointer',
        'items':'> li',
        'axi': 'y',
        'placeholder':'placeholder',
        'nested': 'ul',
    });

    $("#submit_button").bind("click",function(){
/*
        var mySortable = new Array();
        jQuery(".sortable").each(function(){
            var serialized = jQuery(this).sortable("serialize");
            var parent_tag = jQuery(this).parent().get(0).tagName;
            parent_tag = parent_tag.toLowerCase()
            if (parent_tag == 'li')
                {
                    var tag_id = jQuery(this).parent().attr('id');
                    mySortable[tag_id] = serialized;
                }
                else
                {
                    mySortable[0] = serialized;
                }
        });
*/
		var result = $("ul.parent_sortable").sortable("toArray");
		//result['children'] = $("ul.children_sortable").sortable("toArray");
		//result['grandchildren'] = $("ul.grandchildren_sortable").sortable("toArray");
		$("#result").val(result);
		$("form").submit();
    });
});