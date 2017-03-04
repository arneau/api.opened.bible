<?php

// Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';

// Create root group
$group_object = new Group();
$group_object->makeRoot()
	->setTitle('Groups')
	->save();

// Define function
function convertTopicToGroup($topic_object, $parent_group_object) {

	$group_object = new Group();
	$group_object->insertAsLastChildOf($parent_group_object)
		->setTitle($topic_object->getTitle())
		->setType(\Map\GroupTableMap::CLASSKEY_1)
		->save();

	$topic_children_objects = TopicQuery::create()
		->useTopicParentRelatedByTopicIdQuery()
		->filterByParentId($topic_object->getId())
		->endUse()
		->find();

	foreach ($topic_children_objects as $topic_child_object) {
		convertTopicToGroup($topic_child_object, $group_object);
	}

}

// Get topics
$topics_objects = TopicQuery::create()
	->filterByIsRoot(1)
	->find();

// Convert topics to groups
foreach ($topics_objects as $topic_object) {
	convertTopicToGroup($topic_object, $group_object);
}