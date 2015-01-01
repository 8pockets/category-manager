<?php
	echo '<div class="exclude-area"><p>これより以下ののエリアは、非表示を行いたくない投稿やページを指定できます。「,」(コンマ)で区切ると複数指定できます。</p>';
	if (isset($_POST['post-exclude'])) {
		update_option('category_manager_exclude_post', $_POST['post-exclude']);
	}
	$post_temrs = get_option('category_manager_exclude_post');
	echo '<hr><form action="" method="post"><input type="text" name="post-exclude" size="30" maxlength="50" value=' . $post_temrs . '><input type="submit" class="exclude-submit" value="投稿の例外登録" /></form>';
	
	if (isset($_POST['page-exclude'])) {
		update_option('category_manager_exclude_page', $_POST['page-exclude']);
	}
	$page_temrs = get_option('category_manager_exclude_page');
	echo '<hr><form action="" method="post"><input type="text" name="page-exclude" size="30" maxlength="50" value=' . $page_temrs . '><input type="submit" class="exclude-submit" value="固定ページの例外登録" /></form><hr></div>';