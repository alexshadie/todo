<?php
/**
 * Created by PhpStorm.
 * User: tolmachyov
 * Date: 13.10.16
 * Time: 12:18
 */

namespace Todo\Task;


class TaskService implements ITaskService {
	/**
	 * @var ITaskProvider
	 */
	private $taskProvider;

	/**
	 * TaskService constructor.
	 * @param ITaskProvider $taskProvider
	 */
	public function __construct(ITaskProvider $taskProvider) {
		$this->taskProvider = $taskProvider;
	}

	/**
	 * @param int $userId
	 * @return Task[]
	 */
	public function getTasksForUser($userId) {
		return $this->taskProvider->getTasksByUser($userId);
	}

	/**
	 * @param int $taskId
	 * @param int $userId
	 * @return Task
	 */
	public function getTaskById($taskId, $userId) {
		return $this->taskProvider->getTaskById($taskId, $userId);
	}

	/**
	 * @param string $title
	 * @return array
	 */
	private function parseTitle($title) {
		$result = [];
		if (preg_match('/!!!/', $title)) {
			$result['priority'] = 'high';
		} else {
			$result['priority'] = 'normal';
		}
		$result['text'] = $title;
		$result['projectId'] = '';
		return $result;
	}

	/**
	 * @param string $taskName
	 * @param int $userId
	 * @return Task
	 */
	public function createTask($taskName, $userId) {
		str_replace(['[', ']', '(', ')'], '', $taskName);
		$taskAttrs = $this->parseTitle($taskName);
		$task = Task::build()
					->setUserId($userId)
					->setText($taskAttrs['text'])
					->setPriority($taskAttrs['priority'])
					->setState(' ')
					->setProjectId($taskAttrs['projectId'])
					->create();

		return $this->taskProvider->save($task);
	}

	/**
	 * @param int $time
	 * @return string
	 */
	public function renderDuration($time) {
		$result = [];
		if ($time % 60) {
			array_unshift($result, str_pad($time % 60, 2, '0', STR_PAD_LEFT) . " sec");
		}
		if (floor($time / 60) % 60) {
			array_unshift($result, str_pad(floor($time / 60) % 60, 2, '0', STR_PAD_LEFT) . " min");
		}
		if (floor($time / 3600)) {
			array_unshift($result, str_pad(floor($time / 3600), 2, '0', STR_PAD_LEFT) . " hr");
		}
		if (!$result) {
			return null;
		}
		return join(' ', $result);
	}

	/**
	 * @param int $stateId
	 * @return string
	 */
	public function getStateText($stateId) {
		// TODO: Implement getStateText() method.
	}

	/**
	 * @param Task $task
	 * @param int $time
	 * @return bool
	 */
	public function startTask(Task $task, $time) {
		$task = Task::build()
			->assignFrom($task)
			->setTime($time)
			->setState('o')
			->create();
		$this->taskProvider->save($task);
	}

	/**
	 * @param Task $task
	 * @param $time
	 * @return bool
	 */
	public function finishTask(Task $task, $time) {
		$duration = $task->getDuration() + ($time - $task->getTime());
		$task = Task::build()
			->assignFrom($task)
			->setTime($time)
			->setState('x')
			->setDuration($duration)
			->create();
		$this->taskProvider->save($task);
	}
}