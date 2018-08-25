<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2018 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Bootstrap\Controller;

use Pop\Application;
use Pop\Console\Console;
use Pop\Bootstrap\Model;

/**
 * Console controller class
 *
 * @category   Pop\Bootstrap
 * @package    Pop\Bootstrap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2018 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    3.8.0
 */
class ConsoleController extends \Pop\Controller\AbstractController
{

    /**
     * Application object
     * @var Application
     */
    protected $application = null;

    /**
     * Console object
     * @var \Pop\Console\Console
     */
    protected $console = null;

    /**
     * Constructor for the controller
     *
     * @param  Application $application
     * @param  Console     $console
     */
    public function __construct(Application $application, Console $console)
    {
        $this->application = $application;
        $this->console     = $console;
    }

    /**
     * Get application object
     *
     * @return Application
     */
    public function application()
    {
        return $this->application;
    }

    /**
     * Get request object
     *
     * @return Console
     */
    public function console()
    {
        return $this->console;
    }

    /**
     * Install command
     *
     * @param  string $namespace
     * @param  array  $options
     * @return void
     */
    public function install($namespace = 'MyApp', $options = null)
    {
        if (empty($namespace)) {
            $namespace = 'MyApp';
        }

        $location = realpath(__DIR__ . '/../../../');

        $this->console->append("Installing '" . $namespace . "' in '" . $location . "'...");
        $this->console->append();

        $bootstrap = new Model\Bootstrap();

        if (isset($options['cli'])) {
            $bootstrap->installCli($location, $namespace);
        } else {
            $bootstrap->install($location, $namespace);
        }

        $this->console->append('Done!');
        $this->console->send();
    }

    /**
     * Help command
     *
     * @return void
     */
    public function help()
    {
        $command = $this->console->colorize("./pop", Console::BOLD_CYAN) . ' ' .
            $this->console->colorize("install", Console::BOLD_YELLOW) . ' ' .
            $this->console->colorize("[--cli]", Console::BOLD_GREEN) . ' ' .
            $this->console->colorize("<namespace>", Console::BOLD_MAGENTA);
        $this->console->append($command . "\t Install application scaffolding");

        $command = $this->console->colorize("./pop", Console::BOLD_CYAN) . ' ' .
            $this->console->colorize("help", Console::BOLD_YELLOW);
        $this->console->append($command . "\t\t\t\t Show the help screen");

        $this->console->append();

        $this->console->send();
    }

    /**
     * Default error action method
     *
     * @throws \Pop\Bootstrap\Exception
     * @return void
     */
    public function error()
    {
        throw new \Pop\Bootstrap\Exception('Invalid Command');
    }

}