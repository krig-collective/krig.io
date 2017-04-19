<?php
/* (c) Anton Medvedev <anton@medv.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer\Task;

use Deployer\Server\Environment;
use Deployer\Server\ServerInterface;
use Deployer\Exception\Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Context
{
    /**
     * @var ServerInterface|null
     */
    private $server;

    /**
     * @var Environment|null
     */
    private $env;

    /**
     * @var InputInterface|null
     */
    private $input;

    /**
     * @var OutputInterface|null
     */
    private $output;

    /**
     * @var Context[]
     */
    private static $contexts = [];

    /**
     * @param ServerInterface|null $server
     * @param Environment|null $env
     * @param InputInterface|null $input
     * @param OutputInterface|null $output
     */
    public function __construct($server, $env, $input, $output)
    {
        $this->server = $server;
        $this->env = $env;
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * @param Context $context
     */
    public static function push(Context $context)
    {
        self::$contexts[] = $context;
    }

    /**
     * @return Context|false
     */
    public static function get()
    {
        return end(self::$contexts);
    }

    /**
     * Throws a Exception when not called within a task-context and therefore no Context is available.
     *
     * This method provides a useful error to the end-user to make him/her aware
     * to use a function in the required task-context.
     *
     * @param string $callerName
     * @throws Exception
     */
    public static function required($callerName)
    {
        if (!self::get()) {
            throw new Exception("'$callerName' can only be used within a task.");
        }
    }

    /**
     * @return Context
     */
    public static function pop()
    {
        return array_pop(self::$contexts);
    }

    /**
     * @return Environment|null
     */
    public function getEnvironment()
    {
        return $this->env;
    }

    /**
     * @return InputInterface|null
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return OutputInterface|null
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @return ServerInterface|null
     */
    public function getServer()
    {
        return $this->server;
    }
}
