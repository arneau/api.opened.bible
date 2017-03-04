<?php

// Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';

// Set headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Get groups tree
if ($_GET['action'] == 'get_datas') {

	$groups_datas = [];

	$groups_objects = GroupQuery::create()
		->filterByPrimaryKeys(explode(',', $_GET['ids']))
		->find();

	foreach ($groups_objects as $group_object) {
		$groups_datas[$group_object->getId()] = [
			'id' => $group_object->getId(),
			'title' => $group_object->getTitle(),
			'type' => substr(strtolower(get_class($group_object)), 3),
		];
	}

	echo json_encode([
		'groups' => [
			'datas' => $groups_datas,
		],
	]);

}

// Get groups tree
if ($_GET['action'] == 'get_tree') {

	$groups_tree = [];

	$groups_objects = GroupQuery::create()
		->retrieveTree();

	unset($groups_objects[0]);

	foreach ($groups_objects as $group_object) {

		if ($group_object->getId() == 1) {
			continue;
		}

		if ($group_object->getParent()->getId() == 1) {
			$groups_tree[0][] = $group_object->getId();
		} else {
			$groups_tree[$group_object->getParent()->getId()][] = $group_object->getId();
		}

	}

	echo json_encode([
		'groups' => [
			'tree' => $groups_tree,
		],
	]);

}

//if (!isset($_GET['parent_topic_id'])) {
//	$topics_objects = TopicQuery::create()
//		->filterByIsRoot(1)
//		->find();
//} else {
//	$topics_objects = TopicQuery::create()
//		->useTopicParentRelatedByTopicIdQuery()
//		->filterByParentId($_GET['parent_topic_id'])
//		->endUse()
//		->find();
//}
//
//// Define topics array
//$topics_array = [];
//
//// Generate topics array
//foreach ($topics_objects as $topic_object) {
//	$topics_array[$topic_object->getId()] = [
//		'id' => $topic_object->getId(),
//		'title' => $topic_object->getTitle(),
//		'type' => 'topic',
//	];
//}