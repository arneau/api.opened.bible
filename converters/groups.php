<?php

// Require files
require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';

// Find or create root group
$group_object = GroupQuery::create()
	->findRoot();

if (!$group_object) {
$group_object = new Group();
$group_object->makeRoot()
	->setTitle('Groups')
	->save();
}

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
		->orderByTitle()
		->find();

	foreach ($topic_children_objects as $topic_child_object) {
		convertTopicToGroup($topic_child_object, $group_object);
	}

}

// Get topics
$topics_objects = TopicQuery::create()
	->filterByIsRoot(1)
	->orderByTitle()
	->find();

// Convert topics to groups
foreach ($topics_objects as $topic_object) {
	convertTopicToGroup($topic_object, $group_object);
}

// Define function
function convertLessonToGroup($lesson_object, $parent_group_object) {

	$group_object = new Group();
	$group_object->insertAsLastChildOf($parent_group_object)
		->setTitle($lesson_object->getTitle())
		->setType(\Map\GroupTableMap::CLASSKEY_2)
		->save();

	$lesson_children_objects = LessonQuery::create()
		->useLessonParentRelatedByLessonIdQuery()
		->filterByParentId($lesson_object->getId())
		->endUse()
		->orderByTitle()
		->find();

	foreach ($lesson_children_objects as $lesson_child_object) {
		convertLessonToGroup($lesson_child_object, $group_object);
	}

}

// Get lessons
$lessons_objects = LessonQuery::create()
	->filterByIsRoot(1)
	->orderByTitle()
	->find();

// Convert lessons to groups
foreach ($lessons_objects as $lesson_object) {
	convertLessonToGroup($lesson_object, $group_object);
}