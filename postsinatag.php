<?php
/*
Plugin Name: Posts in a tag
Plugin URI: http://peta.okechan.net/blog/archives/tag/wp-posts-in-a-tag
Description: 特定のタグの記事タイトル一覧を表示します。
Author: peta_okechan
Version: 0.1.01
Author URI: http://peta.okechan.net/blog/
Description: 例1 [piat tag=test] → testというタグの記事を日付の昇順に本文を表示せず一覧表示。
例2 [piat tag=test order=DESC bodynumchars=10] → testというタグの記事を日付の降順に本文を10文字だけ表示して一覧表示。
*/

function postsInATag($atts, $content = null) {
    // デフォルト値の設定
    extract(shortcode_atts(array(
        'tag' => '',
        'order' => 'ASC',
        'bodynumchars' => 0,
        'wholeformat' => "<ul>\n{list}\n</ul>\n",
        'lineformat' => "<li><a href=\"{link}\">{title}</a>&nbsp;{body}</li>\n",
    ), $atts));


    $posts = get_posts('posts_per_page=1000&order='.$order.'&orderby=date&tag='.$tag);
    $list = '';
    foreach ($posts as $post) {
        setup_postdata($post);
        $body = $bodynumchars?mb_strimwidth(strip_tags($post->post_content, ''), 0, $bodynumchars, '…', 'utf8'):'';
        $line = preg_replace('/{title}/', $post->post_title, $lineformat);
        $line = preg_replace('/{body}/', $body, $line);
        $line = preg_replace('/{link}/', get_permalink($post->ID), $line);
        $list .= $line;
    }
    $ret = preg_replace('/{list}/', $list, $wholeformat);    
    return $ret;
}

add_shortcode("piat","postsInATag");
?>
