<?php
namespace PHPResque;
require_once dirname(__FILE__) . '/Failure/Interface.php';
require_once dirname(__FILE__) . '/Failure/Redis.php';
use Fgs\Exception\DeveloperException;
/**
 * Failed Resque job.
 *
 * @package		Resque/Failure
 * @author		Chris Boulton <chris@bigcommerce.com>
 * @license		http://www.opensource.org/licenses/mit-license.php
 */
class Resque_Failure
{
	/**
	 * @var string Class name representing the backend to pass failed jobs off to.
	 */
	private static $backend;

	/**
	 * Create a new failed job on the backend.
	 *
	 * @param object $payload        The contents of the job that has just failed.
	 * @param \Exception $exception  The exception generated when the job failed to run.
	 * @param \Resque_Worker $worker Instance of Resque_Worker that was running this job when it failed.
	 * @param string $queue          The name of the queue that this job was fetched from.
	 */
	public static function create($payload, \Exception $exception, Resque_Worker $worker, $queue)
	{
		$data = new \stdClass();

		$data->failed_at = strftime('%a %b %d %H:%M:%S %Z %Y');
		$data->payload = $payload;
		$data->exception = get_class($exception);
		$data->error = $exception->getMessage();
		$data->backtrace = explode("\n", $exception->getTraceAsString());
		$data->worker = (string)$worker;
		$data->queue = $queue;
		$data = json_encode($data);
		Resque::redis()->rpush('failed', $data);

		$data = (array) $data;

		throw new DeveloperException($exception->getMessage(), $data);
	}
}
