<?php
ini_set('max_execution_time ', 999999999999);

$db = mysql_connect('localhost', 'socialmigomby', 'p6P6rwkQSZrc');
mysql_select_db('socialmigomby1', $db);


///////////////////////// ›“¿œ 1 !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//$res = mysql_query('select * from users_old_db where 1 group by id');

//while($user = mysql_fetch_assoc($res)){
//	$login = array_shift(explode('@', $user['email']));
//    mysql_query('insert into users (id, login, password, role, email, status, date_add, date_edit) VALUES('.$user['id'].', "'.$login.'", "'.md5($login.'intwall').'", 1, "'.$user['email'].'", 2, "'.time().'", "'.time().'")');
//	echo $user['id'].'<br/>';
//}


///////////////////////// ›“¿œ 2 !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

//$res = mysql_query('select * from notify_product_cost_oldDB where 1 order by id');

//while($notify = mysql_fetch_assoc($res)){
//	$user_id = false;
//	if(!$notify['email']){
//		continue;
//	}
//	$resUser = mysql_query('select * from users where email = "'.$notify['email'].'"');
//	$user = @mysql_fetch_assoc($resUser);
//	if(!$user){
//		$login = array_shift(explode('@', $notify['email']));
//		mysql_query('insert into users (id, login, password, role, email, status, date_add, date_edit) VALUES(NULL, "'.$login.'", "'.md5($login.'intwall').'", 1, "'.$notify['email'].'", 2, "'.time().'", "'.time().'")');
//		$user_id = mysql_insert_id();
//	} else {
//		$user_id = $user['id'];
//	}
//	
//    mysql_query('insert into notify_product_cost (id, product_id, cost, user_id) VALUES(NULL, "'.$notify['product_id'].'", '.$notify['cost'].', '.$user_id.')');
//	echo $notify['id'].'<br/>';
//}

///////////////////////// ›“¿œ 3 !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

//$res = mysql_query('select * from news_comments_oldDB where 1 order by id');
//$lost = 0;
//while($comm = mysql_fetch_assoc($res)){
//	$resUser = mysql_query('select * from users where id = "'.$comm['user_id'].'"');
//	$user = @mysql_fetch_assoc($resUser);
//	echo $comm['id'].'<br/>';
//	if(!$user){
//			$lost++;
//		continue;
//	}
//
//	mysql_query('insert into news_comments (id, parent_id, entity_id, user_id, text, likes, dislikes, status, level, moderate_id, created_at, updated_at) VALUES('.$comm['id'].', 0, '.$comm['object_id'].', '.$comm['user_id'].', "'.mysql_real_escape_string($comm['text']).'", 0, 0, 1, 0, 3, '.((strtotime($comm['created_at']))?strtotime($comm['created_at']):0).', '.((strtotime($comm['updated_at']))?strtotime($comm['updated_at']):0).')');
//
//	var_dump(mysql_error());
//	echo $comm['id'].'<br/>';
//
//}
//var_dump($lost);











