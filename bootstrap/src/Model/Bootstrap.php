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
namespace Pop\Bootstrap\Model;

use Pop\Code;

/**
 * Main model class
 *
 * @category   Pop\Bootstrap
 * @package    Pop\Bootstrap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2018 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    3.7.0
 */
class Bootstrap extends \Pop\Model\AbstractModel
{

    /**
     * Install method
     *
     * @param  string $location
     * @param  string $namespace
     * @return void
     */
    public function install($location, $namespace)
    {
        mkdir($location . '/app');
        mkdir($location . '/app/config');
        mkdir($location . '/app/src');
        mkdir($location . '/app/src/Controller');
        mkdir($location . '/app/view');
        mkdir($location . '/public');

        $this->createHttpConfig($location, $namespace);
    }

    /**
     * Install with CLI method
     *
     * @param  string $location
     * @param  string $namespace
     * @return void
     */
    public function installCli($location, $namespace)
    {
        mkdir($location . '/app');
        mkdir($location . '/app/config');
        mkdir($location . '/app/src');
        mkdir($location . '/app/src/Console');
        mkdir($location . '/app/src/Console/Controller');
        mkdir($location . '/app/src/Http');
        mkdir($location . '/app/src/Http/Controller');
        mkdir($location . '/app/view');
        mkdir($location . '/public');
        mkdir($location . '/script');

        $this->createHttpConfig($location, $namespace . '\\Http');
        $this->createConsoleConfig($location, $namespace);
    }

    /**
     * Create HTTP config method
     *
     * @param  string $location
     * @param  string $namespace
     * @return void
     */
    protected function createHttpConfig($location, $namespace)
    {
        $httpConfig = new Code\Generator($location . '/app/config/app.http.php', Code\Generator::CREATE_EMPTY);

        $httpConfig->appendToBody('return [');
        $httpConfig->appendToBody("    'routes' => [");
        $httpConfig->appendToBody("        '[/]' => [");
        $httpConfig->appendToBody("            'controller' => '" . $namespace . "\\Controller\\IndexController'");
        $httpConfig->appendToBody("            'action'     => 'index'");
        $httpConfig->appendToBody('        ],');
        $httpConfig->appendToBody("        '*' => [");
        $httpConfig->appendToBody("            'controller' => '" . $namespace . "\\Controller\\IndexController'");
        $httpConfig->appendToBody("            'action'     => 'error'");
        $httpConfig->appendToBody('        ]');
        $httpConfig->appendToBody('    ]');
        $httpConfig->appendToBody('];');

        $httpConfig->save();
    }

    /**
     * Create console config method
     *
     * @param  string $location
     * @param  string $namespace
     * @return void
     */
    protected function createConsoleConfig($location, $namespace)
    {
        $consoleConfig = new Code\Generator($location . '/app/config/app.console.php', Code\Generator::CREATE_EMPTY);

        $consoleConfig->appendToBody('return [');
        $consoleConfig->appendToBody("    'routes' => [");
        $consoleConfig->appendToBody("        'help' => [");
        $consoleConfig->appendToBody("            'controller' => '" . $namespace . "\\Console\\Controller\\ConsoleController'");
        $consoleConfig->appendToBody("            'action'     => 'help'");
        $consoleConfig->appendToBody('        ],');
        $consoleConfig->appendToBody("        '*' => [");
        $consoleConfig->appendToBody("            'controller' => '" . $namespace . "\\Console\\Controller\\ConsoleController'");
        $consoleConfig->appendToBody("            'action'     => 'error'");
        $consoleConfig->appendToBody('        ]');
        $consoleConfig->appendToBody('    ]');
        $consoleConfig->appendToBody('];');

        $consoleConfig->save();
    }
}