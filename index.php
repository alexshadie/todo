<?php

use Todo\Task\Task;
use Todo\Task\TxtTaskProvider;
use Todo\Project\ProjectService;
use Todo\Task\TaskService;

define ('ROOT', __DIR__ . "/");
require __DIR__ . "/vendor/autoload.php";
$fname = __DIR__ . '/data.txt';

$taskProvider = new TxtTaskProvider();
$taskService = new TaskService($taskProvider);

$userId = 1;

$tasks = $taskService->getTasksForUser($userId);

if (isset($_POST['newtask'])) {
	$taskName = $_POST['newtask'];
	$taskService->createTask($taskName, $userId);
	header("Location: /");
	die();
}

if (isset($_GET['start'])) {
	$task = $taskService->getTaskById($_GET['start'], $userId);
	$taskService->startTask($task, time());
	header("Location: /");
	die();
}

if (isset($_GET['finish'])) {
	$task = $taskService->getTaskById($_GET['finish'], $userId);
	$taskService->finishTask($task, time());
	header("Location: /");
	die();
}

?>
	<div>
		<form method="post">
			<input type="text" name="newtask" id="newtask" placeholder="enter new task" autocomplete="off"/><input type="submit" value="Add"/>
		</form>
	</div>
<?php
/** @var Task $task */
foreach ($tasks as $key => $task) {
	?>
	<div role="task" style="font-family: 'Courier New'" data-index="<?=$key + 1;?>" data-priority="<?=$task->getPriority(); ?>">
		[<?= $task->getState(); ?>]
		<span class="text"><?= $task->getText(); ?></span>
		<?= $task->getTime() ? "[" . date('d.m.Y H:i', $task->getTime()) . "]" : ""; ?>
		<?= $task->getDuration() ? "(" . $taskService->renderDuration($task->getDuration()) . ")" : ""; ?>

		<?php if ($task->getState() == ' ') : ?><a role="start" href="?start=<?= $task->getKey(); ?>">[o]</a><?php endif; ?>
		<?php if ($task->getState() != 'x') : ?><a role="finish" href="?finish=<?= $task->getKey();; ?>">[x]</a><?php endif; ?>
	</div>
	<?php
}
?>
<style>
	.active {
		background: slategray;
	}
	[data-priority=high] span.text {
		color: red;
		font-weight: bold;
	}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript">
	var curIdx = 0, maxIdx = <?=count($tasks);?>;
	$(function() {
		$('#newtask').focus();

		function highlight() {
			$('[role=task]').removeClass('active');
			$('[role=task][data-index=' + curIdx + ']').addClass('active');
			$('[role=task][data-index=' + curIdx + ']')[0].scrollIntoView();
		}

		$('#newtask').focus(function() {
			curIdx = 0;
			highlight();
		});

		$(document).keydown(function(event) {
			function prevent() {
				event.preventDefault();
				event.stopPropagation();
			}
			switch (event.keyCode) {
				case 40: // DOWN
					$('#newtask').blur();
					curIdx++;
					if (curIdx > maxIdx) {
						curIdx = 0;
						$('#newtask').focus();
					}
					highlight();
					prevent();
					break;
				case 38: // UP
					$('#newtask').blur();
					curIdx--;
					if (curIdx < 0) {
						curIdx = maxIdx;
					}
					if (curIdx === 0) {
						$('#newtask').focus();
					}
					highlight();
					prevent();
					break;
				case 79: // o
					if (curIdx !== 0) {
						$('[role=start]', $('[role=task][data-index=' + curIdx + ']'))[0].click();
						console.log($('[role=start]', $('[role=task][data-index=' + curIdx + ']')));
						prevent();
					}
					break;
				case 88: // x
					if (curIdx !== 0) {
						$('[role=finish]', $('[role=task][data-index=' + curIdx + ']'))[0].click();
						prevent();
					}
					break;
			}
		});
	});
</script>