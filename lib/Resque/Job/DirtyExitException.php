<?php

namespace PHPResque;

use Fgs\Exception\DeveloperException;

/**
 * Runtime exception class for a job that does not exit cleanly.
 *
 * @package        Resque/job
 * @author        Chris Boulton <chris@bigcommerce.com>
 * @license        http://www.opensource.org/licenses/mit-license.php
 */
class Resque_Job_DirtyExitException extends DeveloperException
{

}