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
        $this->createHttpController($location, $namespace);
        $this->createModule($location, $namespace);
        $this->createViews($location);
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
        $this->createHttpController($location, $namespace);
        $this->createModule($location, $namespace, true);
        $this->createConsoleController($location, $namespace);
        $this->createViews($location);
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

    /**
     * Create HTTP controller method
     *
     * @param  string $location
     * @param  string $namespace
     * @return void
     */
    protected function createHttpController($location, $namespace)
    {
        $index = new Code\Generator($location . '/public/index.php', Code\Generator::CREATE_EMPTY);

        $index->appendToBody("\$autoloader = include __DIR__ . '/../vendor/autoload.php';");
        $index->appendToBody("");
        $index->appendToBody("try {");
        $index->appendToBody("    \$app = new Pop\\Application(\$autoloader, include __DIR__ . '/../app/config/app.http.php');");
        $index->appendToBody("    \$app->register(new " .  $namespace . "\\Module());");
        $index->appendToBody("    \$app->run();");
        $index->appendToBody("} catch (\\Exception \$exception) {");
        $index->appendToBody("    \$app = new " .  $namespace . "\\Module();");
        $index->appendToBody("    \$app->httpError(new Pop\\Application(include __DIR__ . '/../app/config/app.http.php'), \$exception);");
        $index->appendToBody("}");

        $index->save();

        copy(__DIR__ . '/../../config/resources/.htaccess', $location . '/public/.htaccess');
    }

    /**
     * Create console controller method
     *
     * @param  string $location
     * @param  string $namespace
     * @return void
     */
    protected function createConsoleController($location, $namespace)
    {
        $app = new Code\Generator($location . '/script/app', Code\Generator::CREATE_EMPTY);
        $app->setEnv('#!/usr/bin/php');
        $app->appendToBody("\$autoloader = include __DIR__ . '/../vendor/autoload.php';");
        $app->appendToBody("");
        $app->appendToBody("try {");
        $app->appendToBody("    \$app = new Pop\\Application(\$autoloader, include __DIR__ . '/../app/config/app.console.php');");
        $app->appendToBody("    \$app->register(new " .  $namespace . "\\Module());");
        $app->appendToBody("    \$app->run();");
        $app->appendToBody("} catch (\\Exception \$exception) {");
        $app->appendToBody("    \$app = new " .  $namespace . "\\Module();");
        $app->appendToBody("    \$app->cliError(\$exception);");
        $app->appendToBody("}");

        $app->save();
    }

    /**
     * Create module method
     *
     * @param  string  $location
     * @param  string  $namespace
     * @param  boolean $cli
     * @return void
     */
    protected function createModule($location, $namespace, $cli = false)
    {
        $module          = new Code\Generator($location . '/app/src/Module.php', Code\Generator::CREATE_CLASS);
        $namespaceObject = new Code\Generator\NamespaceGenerator($namespace);
        $namespaceObject->setUse('Pop\Application')
            ->setUse('Pop\Http\Request')
            ->setUse('Pop\Http\Response')
            ->setUse('Pop\View\View');

        $module->code()->setNamespace($namespaceObject)
            ->setName('Module')
            ->setParent('\Pop\Module\Module');

        $name = new Code\Generator\PropertyGenerator('name', 'string', str_replace('\\', '-', strtolower($namespace)));

        $registerMethod = new Code\Generator\MethodGenerator('register');
        $registerMethod->addArgument('application', null, 'Application');
        $registerMethod->appendToBody('parent::register($application);');
        $registerMethod->appendToBody('');

        $registerHttpMethod = new Code\Generator\MethodGenerator('registerHttp');
        $registerHttpMethod->appendToBody("if (null !== \$this->application->router()) {");
        $registerHttpMethod->appendToBody("    \$this->application->router()->addControllerParams(");
        $registerHttpMethod->appendToBody("        '*', [");
        $registerHttpMethod->appendToBody("            'application' => \$this->application,");
        $registerHttpMethod->appendToBody("            'request'     => new Request(),");
        $registerHttpMethod->appendToBody("            'response'    => new Response()");
        $registerHttpMethod->appendToBody("        ]");
        $registerHttpMethod->appendToBody("    );");
        $registerHttpMethod->appendToBody("}");

        $module->code()->addMethod($registerMethod);
        $module->code()->addMethod($registerHttpMethod);

        if ($cli) {
            $registerMethod->appendToBody('if ($this->application->router()->isCli()) {');
            $registerMethod->appendToBody('    $this->registerCli();');
            $registerMethod->appendToBody('} else {');
            $registerMethod->appendToBody('    $this->registerHttp();');
            $registerMethod->appendToBody('}');

            $registerCliMethod = new Code\Generator\MethodGenerator('registerCli');
            $registerCliMethod->appendToBody("if (null !== \$this->application->router()) {");
            $registerCliMethod->appendToBody("    \$this->application->router()->addControllerParams(");
            $registerCliMethod->appendToBody("        '*', [");
            $registerCliMethod->appendToBody("            'application' => \$this->application,");
            $registerCliMethod->appendToBody("            'console'     => new \\Pop\\Console\\Console(120, '    ')");
            $registerCliMethod->appendToBody("        ]");
            $registerCliMethod->appendToBody("    );");
            $registerCliMethod->appendToBody("}");

            $module->code()->addMethod($registerCliMethod);
        } else {
            $registerMethod->appendToBody('$this->registerHttp();');
        }
        $registerMethod->appendToBody('');
        $registerMethod->appendToBody('return $this;');

        $httpErrorMethod = new Code\Generator\MethodGenerator('httpError');
        $httpErrorMethod->addArgument('application', null, 'Application');
        $httpErrorMethod->addArgument('exception', null, '\Exception');
        $httpErrorMethod->appendToBody("\$response    = new Response();");
        $httpErrorMethod->appendToBody("\$view        = new View(__DIR__ . '/../view/exception.phtml');");
        $httpErrorMethod->appendToBody("\$view->title = \$exception->getMessage();");
        $httpErrorMethod->appendToBody("\$response->setBody(\$view->render());");
        $httpErrorMethod->appendToBody("\$response->send(500);");

        $module->code()->addMethod($httpErrorMethod);

        if ($cli) {
            $cliErrorMethod = new Code\Generator\MethodGenerator('cliError');
            $cliErrorMethod->addArgument('exception', null, '\Exception');
            $cliErrorMethod->appendToBody("\$message = strip_tags(\$exception->getMessage());");
            $cliErrorMethod->appendToBody("");
            $cliErrorMethod->appendToBody("if (stripos(PHP_OS, 'win') === false) {");
            $cliErrorMethod->appendToBody("    \$string  = \"    \\x1b[1;37m\\x1b[41m    \" . str_repeat(' ', strlen(\$message)) . \"    \\x1b[0m\" . PHP_EOL;");
            $cliErrorMethod->appendToBody("    \$string .= \"    \\x1b[1;37m\\x1b[41m    \" . \$message . \"    \\x1b[0m\" . PHP_EOL;");
            $cliErrorMethod->appendToBody("    \$string .= \"    \\x1b[1;37m\\x1b[41m    \" . str_repeat(' ', strlen(\$message)) . \"    \\x1b[0m\" . PHP_EOL . PHP_EOL;");
            $cliErrorMethod->appendToBody("    \$string .= \"    Try \\x1b[1;33m./nova help\\x1b[0m for help\" . PHP_EOL . PHP_EOL;");
            $cliErrorMethod->appendToBody("} else {");
            $cliErrorMethod->appendToBody("    \$string  = \$message . PHP_EOL . PHP_EOL;");
            $cliErrorMethod->appendToBody("    \$string .= '    Try \'./app help\' for help' . PHP_EOL . PHP_EOL;");
            $cliErrorMethod->appendToBody("}");
            $cliErrorMethod->appendToBody("");
            $cliErrorMethod->appendToBody("echo \$string;");
            $cliErrorMethod->appendToBody("echo PHP_EOL;");
            $cliErrorMethod->appendToBody("");
            $cliErrorMethod->appendToBody("exit(127);");

            $module->code()->addMethod($cliErrorMethod);
        }

        $module->code()->addProperty($name);

        $module->save();

        $exception = new Code\Generator($location . '/app/src/Exception.php', Code\Generator::CREATE_CLASS);
        $exception->code()->setNamespace(new Code\Generator\NamespaceGenerator($namespace))
            ->setName('Exception')
            ->setParent('\Exception');

        $exception->save();
    }

    /**
     * Create views method
     *
     * @param  string $location
     * @return void
     */
    protected function createViews($location)
    {
        copy(__DIR__ . '/../../config/resources/index.phtml', $location . '/view/index.phtml');
        copy(__DIR__ . '/../../config/resources/error.phtml', $location . '/view/error.phtml');
        copy(__DIR__ . '/../../config/resources/error.phtml', $location . '/view/exception.phtml');
    }
}