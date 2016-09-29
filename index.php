<?php
$fname = __DIR__ . '/data.txt';

class Task {
	private $key;
	private $state;
	private $text;
	private $time;
	private $duration;

	public function __construct($source) {
		preg_match('!^(?P<key>\d+) \[(?P<state>.)\] (?P<text>[^\[]+)(| \[(?P<time>[^\[]+)])(| \((?P<duration>[^\)]*)\))$!', trim($source), $matches);
		$this->key = $matches['key'];
		$this->state = $matches['state'];
		$this->text = $matches['text'];
		$this->time = (isset($matches['time']) && $matches['time']) ? strtotime($matches['time']) : null;
		$this->duration = (isset($matches['duration'])) ? $matches['duration'] : null;
	}

	public static function convertToString($sec) {
		$result = [];
		if ($sec % 60) {
			array_unshift($result, str_pad($sec % 60, 2, '0', STR_PAD_LEFT) . " sec");
		}
		if (floor($sec / 60) % 60) {
			array_unshift($result, str_pad(floor($sec / 60) % 60, 2, '0', STR_PAD_LEFT) . " min");
		}
		if (floor($sec / 3600)) {
			array_unshift($result, str_pad(floor($sec / 3600), 2, '0', STR_PAD_LEFT) . " hr");
		}
		if (!$result) {
			return null;
		}
		return join(' ', $result);
	}

	/**
	 * @return mixed
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * @return mixed
	 */
	public function getState() {
		return $this->state;
	}

	public function getStateNum() {
		if ($this->getState() == 'x') {
			return 3;
		}
		if ($this->getState() == ' ') {
			return 2;
		}
		return 1;
	}
	/**
	 * @return mixed
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * @return int|null
	 */
	public function getTime() {
		return $this->time;
	}

	/**
	 * @return null
	 */
	public function getDuration() {
		return $this->duration;
	}

	public function __toString() {
		return $this->key . " [" . $this->state . "] " . $this->text . ($this->time ? " [" . date('d.m.Y H:i', $this->time) . "]" : "") . ($this->duration ? " (" . $this->duration . ")" : "");
	}

	public function start() {
		$this->state = "o";
		$this->time = time();
	}

	public function finish() {
		$this->state = "x";
		$time = time();
		if ($this->time) {
			$this->duration = $time - $this->time;
			$this->duration = $this->convertToString($this->duration);
		}
		$this->time = $time;
	}
}

$tasks = file($fname);
$tasks = array_map('trim', $tasks);
$tasks = array_map(
	function ($item) {
		return new Task($item);
	}, $tasks
);

usort($tasks, function($a, $b) {
	$aState = $a->getStateNum();
	$bState = $b->getStateNum();
	if ($aState == $bState) {
		if ($a->getKey() == $b->getKey()) {
			return 0;
		}
		return $a->getKey() < $b->getKey() ? 1 : -1;
	}
	return ($aState < $bState) ? -1 : 1;
});
file_put_contents($fname, join("\n", $tasks));

if (isset($_POST['newtask'])) {
	$taskName = $_POST['newtask'];
	str_replace(['[', ']', '(', ')'], '', $taskName);
	array_unshift($tasks, new Task(count($tasks) . " [ ] $taskName"));
	file_put_contents($fname, join("\n", $tasks));
	header("Location: /");
	die();
}

if (isset($_GET['start'])) {
	$tasks[$_GET['start']]->start();
	file_put_contents($fname, join("\n", $tasks));
	header("Location: /");
	die();
}

if (isset($_GET['finish'])) {
	$tasks[$_GET['finish']]->finish();
	file_put_contents($fname, join("\n", $tasks));
	header("Location: /");
	die();
}

?>
	<div>
		<form method="post">
			<input type="text" name="newtask" id="newtask" placeholder="enter new task"/><input type="submit" value="Add"/>
		</form>
	</div>
<?php
/** @var Task $task */
foreach ($tasks as $key => $task) {
	?>
	<div role="task" style="font-family: 'Courier New'" data-index="<?=$key + 1;?>">
		[<?= $task->getState(); ?>]
		<?= $task->getText(); ?>
		<?= $task->getTime() ? "[" . date('d.m.Y H:i', $task->getTime()) . "]" : ""; ?>
		<?= $task->getDuration() ? "(" . $task->getDuration() . ")" : ""; ?>

		<?php if ($task->getState() == ' ') : ?><a role="start" href="?start=<?= $key; ?>">[s]</a><?php endif; ?>
		<?php if ($task->getState() != 'x') : ?><a role="finish" href="?finish=<?= $key; ?>">[f]</a><?php endif; ?>
	</div>
	<?php
}
?>
<style>
	.active {
		background: slategray;
	}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript">
	var curIdx = 0, maxIdx = <?=count($tasks);?>;
	$(function() {
		$('#newtask').focus();
	});
	$(document).keydown(function(event) {
		function highlight() {
			$('[role=task]').removeClass('active');
			$('[role=task][data-index=' + curIdx + ']').addClass('active');
		}
		console.log(event.keyCode);
		switch (event.keyCode) {
			case 40: // DOWN
				$('#newtask').blur();
				curIdx++;
				if (curIdx > maxIdx) {
					curIdx = 0;
					$('#newtask').focus();
				}
				highlight();
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
				break;
			case 79: // o
				if (curIdx !== 0) {
					$('[role=start]', $('[role=task][data-index=' + curIdx + ']'))[0].click();
					console.log($('[role=start]', $('[role=task][data-index=' + curIdx + ']')));
				}
				break;
			case 88: // x
				if (curIdx !== 0) {
					$('[role=finish]', $('[role=task][data-index=' + curIdx + ']'))[0].click();
				}
				break;
		}
	});
</script>