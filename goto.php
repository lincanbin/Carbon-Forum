<?php
include(__DIR__ . '/common.php');
$TopicID = intval(Request('Get', 'topic_id', 0));
$PostID  = intval(Request('Get', 'post_id', 0));

if ($TopicID && $PostID) {
	$OlderPosts = $DB->single('SELECT count(ID) FROM ' . $Prefix . 'posts 
		WHERE TopicID = ? 
			AND PostTime <  (SELECT PostTime FROM ' . $Prefix . 'posts 
				WHERE ID = ?)', array(
		$TopicID,
		$PostID
	));
	$DB->CloseConnection();
	$AppendURL = ($OlderPosts >= $Config['PostsPerPage']) ? ('-' . ceil(($OlderPosts + 1) / $Config['PostsPerPage'])) : '';
	header("HTTP/1.1 301 Moved Permanently");
	header("Status: 301 Moved Permanently");
	header("Location: " . $Config['WebsitePath'] . "/t/" . $TopicID . $AppendURL); //.'#Post'.$PostID
} else {
	AlertMsg('Bad Request', 'Bad Request', 400);
}