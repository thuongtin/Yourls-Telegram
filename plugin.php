<?php
/*
Plugin Name: Send notifications via Telegram
Plugin URI: https://github.com/thuongtin/yourls-telegram
Description: Gửi thông báo qua telegram khi có người sửa link
Version: 1.0
Author: Thuong Tin
Author URI: https://thuongtin.com/
*/

// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();
include 'Telegram.php';


function thuongtin_insert_link ( $args ) {
	$insert  = $args[0];
	$url     = $args[1];
	$keyword = $args[2];

	if ( $insert ) {
		//Xử lý dữ liệu ở đây
	}
}


function thuongtin_delete_link ( $args ) {
	$keyword = $args[0];
	//Xử lý dữ liệu ở đây
}


function thuongtin_pre_edit_link ( $args ) {
	$url                   = $args[0];
	$keyword               = $args[1];
	$newkeyword            = $args[2];
	$new_url_already_there = $args[3];
	$keyword_is_ok         = $args[4];
	
	if ( ( !$new_url_already_there || yourls_allow_duplicate_longurls() ) && $keyword_is_ok ) {
		$telegram = new Telegram(THUONGTIN_TELEGRAM_TOKEN);
		$msg = "Link rút gọn đã thay đổi";
		$msg .= "\nId: " . $keyword;
		$msg .= "\nLink cũ: " . yourls_get_keyword_longurl($keyword);
		$msg .= "\nLink mới: " . $url;
		$msg .= "\nNgười sửa: " . YOURLS_USER;
		
		$content = array('chat_id' => THUONGTIN_TELEGRAM_SEND_TO, 'text' => $msg);
		$telegram->sendMessage($content);
	}
}



yourls_add_action( 'insert_link',   'thuongtinr_insert_link' );

yourls_add_action( 'delete_link',   'thuongtin_delete_link' );

yourls_add_action( 'pre_edit_link', 'thuongtin_pre_edit_link' );
