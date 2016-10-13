<?php

namespace Todo\Task;

interface ITaskProvider {
	/**
	 * @param int $userId
	 * @return Task[]
	 */
	public function getTasksByUser($userId);

	/**
	 * @param int $taskId
	 * @param int $userId
	 * @return Task
	 */
	public function getTaskById($taskId, $userId);

	/**
	 * @param Task $task
	 * @return bool
	 */
	public function save(Task $task);
}