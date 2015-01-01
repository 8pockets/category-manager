<?php
/* 
Plugin Name: タクソノミ非表示マネージャー
Plugin URI: http://8pockets.com
Description: タクソノミー非表示マネージャー
Author: 8pockets
Version: 1.0.0 
Author URI: http://8pockets.com/
*/

define('BASEPATH', plugin_dir_path(__FILE__));
define('BASEURL', plugins_url('', __FILE__));
 
//categoryの加工処理
add_action('get_terms_args', 'category_manager_content_filter');
function category_manager_content_filter($args, $taxonomies) {
	if ( is_admin() && 'category' !== $taxonomies[0]){
		return $args;
	}
	//index,page,singleで出し分けられるようにする。	
	$post_num = get_option('category_manager_exclude_post');
	if(is_single($post_num) && 'category' !== $taxonomies[0]){
		return $args;
	}
	//page指定でカテゴリーの非表示を止める
	$page_num = get_option('category_manager_exclude_page');
	if(is_page($page_num) && 'category' !== $taxonomies[0]){
		return $args;
	}
 
	$temrs = get_option('category_manager_plugin_value');
	array_pop($temrs);
	$args['exclude'] = $temrs;
	return $args;
}
/*
add_filter('get_terms_orderby', 'TO_get_terms_orderby', 10, 2);
function TO_get_terms_orderby($orderby, $args){
	//ここにタクソノミーの並び順を出力
	return $orderby
}
*/
//管理画面のメニューページ追加
add_action('admin_menu', 'category_manager_admin_menu');
function category_manager_admin_menu() {
	add_submenu_page('post-new.php', タクソノミー表示選択, タクソノミー表示選択, administrator, __FILE__,category_manager_admin_menu_edit_setting);
}
 
//メニューページのCSS読み込み
add_action('admin_print_styles', 'admin_styles');
function admin_styles()
{
	$myCssFile = BASEURL . '/css/to.css';
	wp_register_style('tocss', $myCssFile);
	wp_enqueue_style( 'tocss');
	
	$JqueryUiCssFile = BASEURL . '/css/jquery-ui.css';
	wp_register_style('jquery-uicss', $JqueryUiCssFile);
	wp_enqueue_style( 'jquery-uicss');
}
//メニューページのJS読み込み

add_action('admin_print_scripts', 'admin_scripts');
function admin_scripts()
{
	$JqueryFile = BASEURL . '/js/jquery-2.1.1.min.js';
	wp_register_script('Jqueryjs', $JqueryFile);
	wp_enqueue_script( 'Jqueryjs');

	$JqueryUiJsFile = BASEURL . '/js/jquery-ui.min.js';
	wp_register_script('JqueryUijs', $JqueryUiJsFile);
	wp_enqueue_script( 'JqueryUijs');
 
	$myJsFile = BASEURL . '/js/to-javascript.js';
	wp_register_script('to-javascriptjs', $myJsFile);
	wp_enqueue_script( 'to-javascriptjs');
} 
 
//メニューページ関連の実際の処理
function category_manager_admin_menu_edit_setting() {
	if (isset($_POST['category'])) {
		update_option('category_manager_plugin_value', $_POST['category']);
	}
	$temrs = get_option('category_manager_plugin_value');
//print_r(array_values($temrs));

	if (isset($_POST['order'])) {
		update_option('category_manager_order', $_POST['order']);
	}
	$temrs_order = get_option('category_manager_order');
	print_r($temrs_order);

	echo <<< EOM
	<h2>フロントから非表示にするカテゴリーを選んでください。</h2>
	<h5>選択されているカテゴリーの中の記事は消えずに表示されます。</h5>
	<div id="ajax-response"></div>
	<div class="category-manager">
	<form action="" method="post">
	<ul class="sortable ui-sortable parent_sortable">
EOM;
	$categories = get_categories('hide_empty=0');
	foreach ($categories as $parent_value){
		if($parent_value->category_parent==0){
			if(array_search($parent_value->term_id,$temrs) !== FALSE){$checked = "checked";}
				echo <<< EOM
				<li id="{$parent_value->term_id}">
				<div class="form-text-parent">
					<label>
					<input type="checkbox" name="category[]" value="{$parent_value->term_id}" {$checked}>
					<span class="label_text">{$parent_value->name}</span>
					<span class="label_count">({$parent_value->count})</span>
					</label>
				</div>
EOM;
				$checked = "";
			$children = get_categories("hide_empty=0&child_of=$parent_value->term_id");
			for($i=0;$i<count($children);$i++){
				$children_value = $children[$i];
				if($i == 0){echo '<ul class="children sortable ui-sortable children_sortable">';}
				if($children_value->category_parent==$parent_value->term_id){
					if(array_search($children_value->term_id,$temrs) !== FALSE){$checked = "checked";}
						echo <<< EOM
						<li id="{$children_value->term_id}">
						<div class="form-text-children">
							<label class="children_value">
							<input type="checkbox" name="category[]" value="{$children_value->term_id}" {$checked}>
							<span class="label_text">{$children_value->name}</span>
							<span class="label_count">({$children_value->count})</span>
							</label>
						</div>
EOM;
						$checked = "";
					$grandchild = get_categories("hide_empty=0&parent=$children_value->term_id");
					for($e=0;$e<count($grandchild);$e++){
						$grandchild_value = $grandchild[$e];
						if($e == 0){echo '<ul class="children sortable ui-sortable grandchildren_sortable">';}
						if($grandchild_value->category_parent==$children_value->term_id){
							if(array_search($grandchild_value->term_id,$temrs) !== FALSE){$checked = "checked";}
								echo <<< EOM
								<li id="{$grandchild_value->term_id}">
								<div class="form-text-grandchild">
									<label class="grandchild_value">
									<input type="checkbox" name="category[]" value="{$grandchild_value->term_id}" {$checked}>
									<span class="label_text">{$grandchild_value->name}</span>
									<span class="label_count">({$grandchild_value->count})</span>
									</label>
								</div>
EOM;
								$checked = "";
							echo '</li>';
						}
						if($e == count($grandchild)-1){echo '</ul>';}
					}
					echo '</li>';
				}
				if($i == count($children)-1){echo '</ul>';}
			}
			echo '</li>';
		}
	}
	echo <<< EOM
	</ul>
		<div class="submit_box">
			<input type="hidden" name="category[]" value="display-none" class="display-none" checked>
			<input type="hidden" id="result" name="order">
			<input type="submit" id="submit_button" value="更新" />
		</div>
	</form>
	</div>
EOM;
	//表示させるページをしぼるPHPファイル
	include_once(BASEPATH . '/include/exclude-page.php');

}
