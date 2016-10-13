<?php

namespace Todo\Task;

class TaskBuilder {
	/**
	 * @var int
	 */
	private $key;
	/**
	 * @var string
	 */
	private $state;
	/**
	 * @var string;
	 */
	private $text;
	/**
	 * @var string
	 */
	private $time;
	/**
	 * @var int
	 */
	private $duration;
	/**
	 * @var int
	 */
	private $priority;
	/**
	 * @var int
	 */
	private $userId;
	/**
	 * @var int
	 */
	private $projectId;

	/**
	 * @return Task
	 */
	public function create() {
		return new Task(
			$this->key,
			$this->state,
			$this->text,
			$this->time,
			$this->duration,
			$this->priority,
			$this->userId,
			$this->projectId
		);
	}

	/**
	 * @param Task $task
	 * @return TaskBuilder
	 */
	public function assignFrom(Task $task) {
		$this->key = $task->getKey();
		$this->state = $task->getState();
		$this->text = $task->getText();
		$this->time = $task->getTime();
		$this->duration = $task->getDuration();
		$this->priority = $task->getPriority();
		$this->userId = $task->getUserId();
		$this->projectId = $task->getProjectId();
		return $this;
	}

	/**
	 * @param int $key
	 * @return TaskBuilder
	 */
	public function setKey($key) {
		$this->key = $key;
		return $this;
	}

	/**
	 * @param string $state
	 * @return TaskBuilder
	 */
	public function setState($state) {
		$this->state = $state;
		return $this;
	}

	/**
	 * @param string $text
	 * @return TaskBuilder
	 */
	public function setText($text) {
		$this->text = $text;
		return $this;
	}

	/**
	 * @param string $time
	 * @return TaskBuilder
	 */
	public function setTime($time) {
		$this->time = $time;
		return $this;
	}

	/**
	 * @param int $duration
	 * @return TaskBuilder
	 */
	public function setDuration($duration) {
		$this->duration = $duration;
		return $this;
	}

	/**
	 * @param int $priority
	 * @return TaskBuilder
	 */
	public function setPriority($priority) {
		$this->priority = $priority;
		return $this;
	}

	/**
	 * @param int $userId
	 * @return TaskBuilder
	 */
	public function setUserId($userId) {
		$this->userId = $userId;
		return $this;
	}

	/**
	 * @param int $projectId
	 * @return TaskBuilder
	 */
	public function setProjectId($projectId) {
		$this->projectId = $projectId;
		return $this;
	}
}