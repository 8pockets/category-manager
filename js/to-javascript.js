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

});