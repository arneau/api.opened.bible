<?php

// Require files
require_once 'vendor/autoload.php';
require_once 'generated-conf/config.php';

// Get topics
if (!isset($_GET['parent_topic_id'])) {
	$topics_objects = TopicQuery::create()
		->filterByIsRoot(1)
		->find();
} else {
	$topics_objects = TopicQuery::create()
		->useTopicParentRelatedByTopicIdQuery()
		->filterByParentId($_GET['parent_topic_id'])
		->endUse()
		->find();
}

// Define topics array
$topics_array = [];

// Generate topics array
foreach ($topics_objects as $topic_object) {
	$topics_array[$topic_object->getId()] = [
		'id' => $topic_object->getId(),
		'title' => $topic_object->getTitle(),
		'type' => 'topic',
	];
}

// Set headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Echo topics
echo json_encode($topics_array);