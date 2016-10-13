<?php

namespace Todo\Task;


interface ITaskService {
	/**
	 * @param int $userId
	 * @return Task[]
	 */
	public function getTasksForUser($userId);

	/**
	 * @param int $taskId
	 * @param int $userId
	 * @return Task
	 */
	public function getTaskById($taskId, $userId);

	/**
	 * @param string $taskName
	 * @param int $userId
	 * @return Task
	 */
	public function createTask($taskName, $userId);

	/**
	 * @param int $time
	 * @return string
	 */
	public function renderDuration($time);

	/**
	 * @param int $stateId
	 * @return string
	 */
	public function getStateText($stateId);

	/**
	 * @param Task $task
	 * @param int $time
	 * @return bool
	 */
	public function startTask(Task $task, $time);

	/**
	 * @param Task $task
	 * @param $time
	 * @return bool
	 */
	public function finishTask(Task $task, $time);
}