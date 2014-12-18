$(function() {
/*
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
*/
	//孫要素が一つでも表示になっていたら、一個上の子要素はチェックをする。
	$(".form-text-grandchild :checkbox").click(function() {
		if ($(this).is(':checked').length > 0) {
			$(".form-text-children :checkbox").prop("checked", true);
		}
	});
	//子要素が一つでも表示になっていたら、最上位の親要素にチェックを入れる。

});