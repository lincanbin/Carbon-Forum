<?php
class Task
{
	// 任务 ID
	protected $taskId;
	// 协程对象
	protected $coroutine;
	// send() 值
	protected $sendVal = null;
	// 是否首次 yield
	protected $beforeFirstYield = true;

	public function __construct($taskId, Generator $coroutine) {
		$this->taskId = $taskId;
		$this->coroutine = $coroutine;
	}

	public function getTaskId() {
		return $this->taskId;
	}

	public function setSendValue($sendVal) {
		$this->sendVal = $sendVal;
	}

	public function run() {
		// 如之前提到的在send之前, 当迭代器被创建后第一次 yield 之前，一个 renwind() 方法会被隐式调用
		// 所以实际上发生的应该类似:
		// $this->coroutine->rewind();
		// $this->coroutine->send();

		// 这样 renwind 的执行将会导致第一个 yield 被执行, 并且忽略了他的返回值.
		// 真正当我们调用 yield 的时候, 我们得到的是第二个yield的值，导致第一个yield的值被忽略。
		// 所以这个加上一个是否第一次 yield 的判断来避免这个问题
		if ($this->beforeFirstYield) {
			$this->beforeFirstYield = false;
			return $this->coroutine->current();
		} else {
			$retval = $this->coroutine->send($this->sendVal);
			$this->sendVal = null;
			return $retval;
		}
	}

	public function isFinished() {
		return !$this->coroutine->valid();
	}
}

class Scheduler
{
	protected $maxTaskId = 0;
	protected $tasks = []; // taskId => task
	protected $queue;

	// resourceID => [socket, tasks]
	protected $waitingForRead = [];
	protected $waitingForWrite = [];

	public function __construct() {
		// SPL 队列
		$this->queue = new SplQueue();
	}

	public function newTask(Generator $coroutine) {
		$tid = ++$this->maxTaskId;
		$task = new Task($tid, $coroutine);
		$this->tasks[$tid] = $task;
		$this->schedule($task);
		return $tid;
	}

	public function schedule(Task $task) {
		// 任务入队
		$this->queue->enqueue($task);
	}

	public function run() {
		while (!$this->queue->isEmpty()) {
			// 任务出队
			$task = $this->queue->dequeue();
			$task->run();

			if ($task->isFinished()) {
				unset($this->tasks[$task->getTaskId()]);
			} else {
				$this->schedule($task);
			}
		}
	}

	public function waitForRead($socket, Task $task)
	{
		if (isset($this->waitingForRead[(int)$socket])) {
			$this->waitingForRead[(int)$socket][1][] = $task;
		} else {
			$this->waitingForRead[(int)$socket] = [$socket, [$task]];
		}
	}

	public function waitForWrite($socket, Task $task)
	{
		if (isset($this->waitingForWrite[(int)$socket])) {
			$this->waitingForWrite[(int)$socket][1][] = $task;
		} else {
			$this->waitingForWrite[(int)$socket] = [$socket, [$task]];
		}
	}

	/**
	 * @param $timeout 0 represent
	 */
	protected function ioPoll($timeout)
	{
		$rSocks = [];
		foreach ($this->waitingForRead as list($socket)) {
			$rSocks[] = $socket;
		}

		$wSocks = [];
		foreach ($this->waitingForWrite as list($socket)) {
			$wSocks[] = $socket;
		}

		$eSocks = [];
		// $timeout 为 0 时, stream_select 为立即返回，为 null 时则会阻塞的等，见 http://php.net/manual/zh/function.stream-select.php
		if (!@stream_select($rSocks, $wSocks, $eSocks, $timeout)) {
			return;
		}

		foreach ($rSocks as $socket) {
			list(, $tasks) = $this->waitingForRead[(int)$socket];
			unset($this->waitingForRead[(int)$socket]);

			foreach ($tasks as $task) {
				$this->schedule($task);
			}
		}

		foreach ($wSocks as $socket) {
			list(, $tasks) = $this->waitingForWrite[(int)$socket];
			unset($this->waitingForWrite[(int)$socket]);

			foreach ($tasks as $task) {
				$this->schedule($task);
			}
		}
	}

	/**
	 * 检查队列是否为空，若为空则挂起的执行 stream_select，否则检查完 IO 状态立即返回，详见 ioPoll()
	 * 作为任务加入队列后，由于 while true，会被一直重复的加入任务队列，实现每次任务前检查 IO 状态
	 * @return Generator object for newTask
	 *
	 */
	protected function ioPollTask()
	{
		while (true) {
			if ($this->taskQueue->isEmpty()) {
				$this->ioPoll(null);
			} else {
				$this->ioPoll(0);
			}
			yield;
		}
	}

	/**
	 * $scheduler = new Scheduler;
	 * $scheduler->newTask(Web Server Generator);
	 * $scheduler->withIoPoll()->run();
	 *
	 * 新建 Web Server 任务后先执行 withIoPoll() 将 ioPollTask() 作为任务入队
	 *
	 * @return $this
	 */
	public function withIoPoll()
	{
		$this->newTask($this->ioPollTask());
		return $this;
	}
}