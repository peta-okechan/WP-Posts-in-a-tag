<?php
/*
Plugin Name: Posts in a tag
Plugin URI: http://www.example.com/
Description: 特定のタグの記事タイトル一覧を表示します。
Author: peta_okechan
Version: 0.1
Author URI: http://peta.okechan.net/blog/
Description: 例1 [piat tag=test] → tagnameの記事を日付の昇順に本文を表示せず一覧表示。
例2 [piat tag=test order=DESC bodynumchars=10] → tagnameの記事を日付の降順に本文を10文字だけ表示して一覧表示。
*/

function postsInATag($atts, $content = null) {
    extract(shortcode_atts(array('tag' => '', 'order' => 'ASC', 'bodynumchars' => 0), $atts));
    $posts = get_posts('posts_per_page=1000&order='.$order.'&orderby=date&tag='.$tag);
    $ret = "<ul>\n";
    foreach ($posts as $post) {
        setup_postdata($post);
        $body = $bodynumchars?mb_strimwidth(strip_tags($post->post_content, ''), 0, $bodynumchars, '…', 'utf8'):'';
        $ret .= "<li><a href=\"".get_permalink($post->ID)."\"/>".$post->post_title."</a>&nbsp;".$body."</li>\n";
    }
    $ret .= "</ul>\n";
    return $ret;
}

add_shortcode("piat","postsInATag");
?>
