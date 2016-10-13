<?php

namespace Todo\Task;

class Task {
	const STATE_NEW = 0;
	const STATE_O = 1;
	const STATE_X = 2;

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
	 * Task constructor.
	 * @param int $key
	 * @param string $state
	 * @param string $text
	 * @param string $time
	 * @param int $duration
	 * @param int $priority
	 * @param int $userId
	 * @param int $projectId
	 */
	public function __construct($key, $state, $text, $time, $duration, $priority, $userId, $projectId) {
		$this->key = $key;
		$this->state = $state;
		$this->text = $text;
		$this->time = $time;
		$this->duration = $duration;
		$this->priority = $priority;
		$this->userId = $userId;
		$this->projectId = $projectId;
	}

	/**
	 * @return TaskBuilder
	 */
	public static function build() {
		return new TaskBuilder();
	}

	/**
	 * @return int
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * @return string
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * @return string
	 */
	public function getTime() {
		return $this->time;
	}

	/**
	 * @return int
	 */
	public function getDuration() {
		return $this->duration;
	}

	/**
	 * @return int
	 */
	public function getPriority() {
		return $this->priority;
	}

	/**
	 * @return int
	 */
	public function getUserId() {
		return $this->userId;
	}

	/**
	 * @return int
	 */
	public function getProjectId() {
		return $this->projectId;
	}


}