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
}
//メニューページのJS読み込み
/*
add_action('admin_print_scripts', 'admin_scripts');
function admin_scripts()
{
	$myJsFile = BASEURL . '/js/jquery-2.1.1.min.js';
	wp_register_script('jqueryjs', $myJsFile);
	wp_enqueue_script( 'jqueryjs');
 
	$myJsFile = BASEURL . '/js/to-javascript.js';
	wp_register_script('to-javascriptjs', $myJsFile);
	wp_enqueue_script( 'to-javascriptjs');
}
*/
 
 
//メニューページ関連の実際の処理
function category_manager_admin_menu_edit_setting() {
	if (isset($_POST['category'])) {
		update_option('category_manager_plugin_value', $_POST['category']);
	}
	$temrs = get_option('category_manager_plugin_value');
//print_r(array_values($temrs));
 
	$categories = get_categories('hide_empty=0');
	echo '<h2>フロントから非表示にするカテゴリーを選んでください。</h2><h5>選択されているカテゴリーの中の記事は消えずに表示されます。</h5><div class="category-manager"><form action="" method="post">';
	foreach ($categories as $parent_value){
		if($parent_value->category_parent==0){
			if(array_search($parent_value->term_id,$temrs) === FALSE){
				echo '<div class="form-text-parent"><label><input type="checkbox" name="category[]" value="' . $parent_value->term_id . '">' . $parent_value->name . '</label><span>('.$parent_value->count.')</span></div>';
			}else{
				echo '<div class="form-text-parent"><label><input type="checkbox" name="category[]" value="' . $parent_value->term_id . '" checked>' . $parent_value->name . '</label><span>('.$parent_value->count.')</span></div>';
			}
			$children = get_categories("hide_empty=0&child_of=$parent_value->term_id");
			foreach( $children as $children_value ){
				if($children_value->category_parent==$parent_value->term_id){
					if(array_search($children_value->term_id,$temrs) === FALSE){
						echo '<div class="form-text-children"><label class="children_value"><input type="checkbox" name="category[]" value="' . $children_value->term_id . '">' . $children_value->name . '</label><span>('.$parent_value->count.')</span></div>';
					}else{
						echo '<div class="form-text-children"><label class="children_value"><input type="checkbox" name="category[]" value="' . $children_value->term_id . '" checked>' . $children_value->name . '</label><span>('.$parent_value->count.')</span></div>';
					}
					$grandchild = get_categories("hide_empty=0&parent=$children_value->term_id");
					foreach( $grandchild as $grandchild_value ){
						if($grandchild_value->category_parent==$children_value->term_id){
							if(array_search($grandchild_value->term_id,$temrs) === FALSE){
								echo '<div class="form-text-grandchild"><label class="grandchild_value"><input type="checkbox" name="category[]" value="' . $grandchild_value->term_id . '">' . $grandchild_value->name . '</label><span>('.$parent_value->count.')</span></div>';
							}else{
								echo '<div class="form-text-grandchild"><label class="grandchild_value"><input type="checkbox" name="category[]" value="' . $grandchild_value->term_id . '" checked>' . $grandchild_value->name . '</label><span>('.$parent_value->count.')</span></div>';
							}
						}
					}
				}
			}
		}
	}
	echo '<p><input type="checkbox" name="category[]" value="display-none" class="display-none" checked><input type="submit" id="submit_button" value="更新" /></p></form></div>';
	//表示させるページをしぼるPHPファイル
	include_once(BASEPATH . '/include/exclude-page.php');
}
