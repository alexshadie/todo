<?php

namespace Todo\Task;

class TxtTaskProvider implements ITaskProvider {
	/**
	 * @param string $state
	 * @return int
	 */
	private function convertTextStateToNum($state) {
		if ($state == 'x') {
			return 3;
		}
		if ($state == ' ') {
			return 2;
		}
		return 1;
	}

	/**
	 * @param int $userId
	 * @return string
	 */
	private function getFilename($userId) {
		return ROOT . '/data.txt';
	}

	/**
	 * @param Task $task
	 * @return string
	 */
	private function renderTask(Task $task) {
		return
				$task->getKey() . " [" . $task->getState() . "] " .
				$task->getText() .
				($task->getTime() ? " [" . date('d.m.Y H:i', $task->getTime()) . "]" : "") .
				($task->getDuration() ? " (" . $task->getDuration() . ")" : "");
	}

	/**
	 * @param Task[] $tasks
	 */
	private function saveTasks($tasks, $userId) {
		$tasks = array_map(function ($item) {
			return $this->renderTask($item);
		}, $tasks);
		file_put_contents($this->getFilename($userId), join("\n", $tasks));
	}

	/**
	 * @param int $userId
	 * @return Task[]
	 */
	public function getTasksByUser($userId) {
		$tasks = file($this->getFilename($userId));
		$tasks = array_map('trim', $tasks);

		$tasks = array_map(
			function ($item) use ($userId) {
				preg_match('!^(?P<key>\d+) \[(?P<state>.)\] (?P<text>[^\[]+)(| \[(?P<time>[^\[]+)])(| \((?P<duration>[^\)]*)\))$!', trim($item), $matches);
				$key = $matches['key'];
				$state = $matches['state'];
				$text = $matches['text'];
				$projectId = $projectName = "";
				$time = (isset($matches['time']) && $matches['time']) ? strtotime($matches['time']) : null;
				$duration = (isset($matches['duration'])) ? $matches['duration'] : null;
				$priority = 'normal';
				if (preg_match('/!!!/', $text)) {
					$priority = 'high';
				}
				return new Task($key, $state, $text, $time, $duration, $priority, $userId, $projectId);
			}, $tasks
		);
		usort(
			$tasks, function (Task $a, Task $b) {
				$aState = $this->convertTextStateToNum($a->getState());
				$bState = $this->convertTextStateToNum($b->getState());
				if ($aState == $bState) {
					if ($a->getPriority() == $b->getPriority()) {
						if ($a->getKey() == $b->getKey()) {
							return 0;
						}
						return $a->getKey() < $b->getKey() ? 1 : -1;
					}
					return $a->getPriority() == 'high' ? -1 : 1;
				}
				return ($aState < $bState) ? -1 : 1;
			}
		);
		$this->saveTasks($tasks, $userId);
		return $tasks;
	}

	/**
	 * @param int $taskId
	 * @param int $userId
	 * @return Task
	 * @throws \Exception
	 */
	public function getTaskById($taskId, $userId) {
		$tasks = $this->getTasksByUser($userId);
		foreach ($tasks as $task) {
			if ($task->getKey() == $taskId) {
				return $task;
			}
		}
		throw new \Exception('Not found');
	}

	/**
	 * @param Task $task
	 * @return bool
	 */
	public function save(Task $task) {
		if ($task->getKey() == 0) {
			$tasks = $this->getTasksByUser($task->getUserId());
			$task = Task::build()
						->assignFrom($task)
						->setKey(array_reduce($tasks, function($carry, Task $item) {return max($carry, $item->getKey());}) + 1)
						->create();
			array_unshift($tasks, $task);
		} else {
			$tasks = $this->getTasksByUser($task->getUserId());
			foreach ($tasks as $key => $t) {
				if ($task->getKey() == $t->getKey()) {
					$tasks[$key] = $task;
					break;
				}
			}
		}
		$this->saveTasks($tasks, $task->getUserId());
		return $task;
	}
}