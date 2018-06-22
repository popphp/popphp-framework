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
        $this->createHttpFrontController($location, $namespace);
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
        $this->createHttpFrontController($location, $namespace);
        $this->createHttpController($location, $namespace . '\\Http');
        $this->createModule($location, $namespace, true);
        $this->createConsoleFrontController($location, $namespace);
        $this->createConsoleController($location, $namespace . '\\Console');
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
        $httpConfig->appendToBody("            'controller' => '" . $namespace . "\\Controller\\IndexController',");
        $httpConfig->appendToBody("            'action'     => 'index'");
        $httpConfig->appendToBody('        ],');
        $httpConfig->appendToBody("        '*' => [");
        $httpConfig->appendToBody("            'controller' => '" . $namespace . "\\Controller\\IndexController',");
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
        $consoleConfig->appendToBody("            'controller' => '" . $namespace . "\\Console\\Controller\\ConsoleController',");
        $consoleConfig->appendToBody("            'action'     => 'help'");
        $consoleConfig->appendToBody('        ],');
        $consoleConfig->appendToBody("        '*' => [");
        $consoleConfig->appendToBody("            'controller' => '" . $namespace . "\\Console\\Controller\\ConsoleController',");
        $consoleConfig->appendToBody("            'action'     => 'error'");
        $consoleConfig->appendToBody('        ]');
        $consoleConfig->appendToBody('    ]');
        $consoleConfig->appendToBody('];');

        $consoleConfig->save();
    }

    /**
     * Create HTTP front controller method
     *
     * @param  string $location
     * @param  string $namespace
     * @return void
     */
    protected function createHttpFrontController($location, $namespace)
    {
        $index = new Code\Generator($location . '/public/index.php', Code\Generator::CREATE_EMPTY);

        $index->appendToBody("\$autoloader = include __DIR__ . '/../vendor/autoload.php';");
        $index->appendToBody("\$autoloader->addPsr4('" . $namespace . "\\\\', __DIR__ . '/../app/src');");
        $index->appendToBody("");
        $index->appendToBody("try {");
        $index->appendToBody("    \$app = new Pop\\Application(\$autoloader, include __DIR__ . '/../app/config/app.http.php');");
        $index->appendToBody("    \$app->register(new " .  $namespace . "\\Module());");
        $index->appendToBody("    \$app->run();");
        $index->appendToBody("} catch (\\Exception \$exception) {");
        $index->appendToBody("    \$app = new " .  $namespace . "\\Module();");
        $index->appendToBody("    \$app->httpError(\$exception);");
        $index->appendToBody("}");

        $index->save();

        copy(__DIR__ . '/../../config/resources/.htaccess', $location . '/public/.htaccess');
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
        if (strpos($namespace, '\\Http') !== false) {
            $ctrl = new Code\Generator($location . '/app/src/Http/Controller/IndexController.php', Code\Generator::CREATE_CLASS);
        } else {
            $ctrl = new Code\Generator($location . '/app/src/Controller/IndexController.php', Code\Generator::CREATE_CLASS);
        }

        $ctrl->code()->setName('IndexController')
            ->setParent('\Pop\Controller\AbstractController');

        $namespaceObject = new Code\Generator\NamespaceGenerator($namespace . '\Controller');
        $namespaceObject->setUse('Pop\Application')
            ->setUse('Pop\Http\Request')
            ->setUse('Pop\Http\Response')
            ->setUse('Pop\View\View');

        $ctrl->code()->setNamespace($namespaceObject);

        $ctrl->code()->addProperty(new Code\Generator\PropertyGenerator('application', 'Application'));
        $ctrl->code()->addProperty(new Code\Generator\PropertyGenerator('request', 'Request'));
        $ctrl->code()->addProperty(new Code\Generator\PropertyGenerator('response', 'Response'));

        $constructMethod = new Code\Generator\MethodGenerator('__construct');
        $constructMethod->addArgument('application', null, 'Application')
            ->addArgument('request', null, 'Request')
            ->addArgument('response', null, 'Response');

        $constructMethod->appendToBody('$this->application = $application;');
        $constructMethod->appendToBody('$this->request     = $request;');
        $constructMethod->appendToBody('$this->response    = $response;');

        $applicationMethod = new Code\Generator\MethodGenerator('application');
        $applicationMethod->appendToBody('return $this->application;');

        $requestMethod = new Code\Generator\MethodGenerator('request');
        $requestMethod->appendToBody('return $this->request;');

        $responseMethod = new Code\Generator\MethodGenerator('response');
        $responseMethod->appendToBody('return $this->response;');

        $indexMethod = new Code\Generator\MethodGenerator('index');
        if (strpos($namespace, '\\Http') !== false) {
            $indexMethod->appendToBody("\$view        = new View(__DIR__ . '/../../../view/index.phtml');");
        } else {
            $indexMethod->appendToBody("\$view        = new View(__DIR__ . '/../../view/index.phtml');");
        }
        $indexMethod->appendToBody("\$view->title = '" . str_replace('\\Http', '', $namespace) . "';");
        $indexMethod->appendToBody("\$this->response->setBody(\$view->render());");
        $indexMethod->appendToBody("\$this->response->send();");

        $errorMethod = new Code\Generator\MethodGenerator('error');
        if (strpos($namespace, '\\Http') !== false) {
            $errorMethod->appendToBody("\$view        = new View(__DIR__ . '/../../../view/error.phtml');");
        } else {
            $errorMethod->appendToBody("\$view        = new View(__DIR__ . '/../../view/error.phtml');");
        }
        $errorMethod->appendToBody("\$view->title = 'Error';");
        $errorMethod->appendToBody("\$this->response->setBody(\$view->render());");
        $errorMethod->appendToBody("\$this->response->send(404);");

        $ctrl->code()->addMethod($constructMethod);
        $ctrl->code()->addMethod($applicationMethod);
        $ctrl->code()->addMethod($requestMethod);
        $ctrl->code()->addMethod($responseMethod);
        $ctrl->code()->addMethod($indexMethod);
        $ctrl->code()->addMethod($errorMethod);

        $ctrl->save();
    }

    /**
     * Create console front controller method
     *
     * @param  string $location
     * @param  string $namespace
     * @return void
     */
    protected function createConsoleFrontController($location, $namespace)
    {
        $app = new Code\Generator($location . '/script/app', Code\Generator::CREATE_EMPTY);
        $app->setEnv('#!/usr/bin/php');
        $app->appendToBody("\$autoloader = include __DIR__ . '/../vendor/autoload.php';");
        $app->appendToBody("\$autoloader->addPsr4('" . $namespace . "\\\\', __DIR__ . '/../app/src');");
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

        chmod($location . '/script/app', 0755);
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
        $ctrl = new Code\Generator($location . '/app/src/Console/Controller/ConsoleController.php', Code\Generator::CREATE_CLASS);
        $ctrl->code()->setName('ConsoleController')
            ->setParent('\Pop\Controller\AbstractController');

        $namespaceObject = new Code\Generator\NamespaceGenerator($namespace . '\Controller');
        $namespaceObject->setUse('Pop\Application')
            ->setUse('Pop\Console\Console');

        $ctrl->code()->setNamespace($namespaceObject);

        $ctrl->code()->addProperty(new Code\Generator\PropertyGenerator('application', 'Application'));
        $ctrl->code()->addProperty(new Code\Generator\PropertyGenerator('console', 'Console'));

        $constructMethod = new Code\Generator\MethodGenerator('__construct');
        $constructMethod->addArgument('application', null, 'Application')
            ->addArgument('console', null, 'Console');

        $constructMethod->appendToBody('$this->application = $application;');
        $constructMethod->appendToBody('$this->console     = $console;');

        $applicationMethod = new Code\Generator\MethodGenerator('application');
        $applicationMethod->appendToBody('return $this->application;');

        $consoleMethod = new Code\Generator\MethodGenerator('console');
        $consoleMethod->appendToBody('return $this->console;');

        $helpMethod = new Code\Generator\MethodGenerator('help');
        $helpMethod->appendToBody("\$this->console->append();");
        $helpMethod->appendToBody("");
        $helpMethod->appendToBody("\$command = \$this->console->colorize(\"./app\", Console::BOLD_CYAN) . ' ' .");
        $helpMethod->appendToBody("    \$this->console->colorize(\"help\", Console::BOLD_YELLOW);");
        $helpMethod->appendToBody("\$this->console->append(\$command . \"\\t\\t Show the help screen\");");
        $helpMethod->appendToBody("");
        $helpMethod->appendToBody("\$this->console->append();");
        $helpMethod->appendToBody("");
        $helpMethod->appendToBody("\$this->console->send();");

        $errorMethod = new Code\Generator\MethodGenerator('error');
        $errorMethod->appendToBody("throw new \\" . str_replace('\\Console', '', $namespace) . "\\Exception('Invalid Command');");

        $ctrl->code()->addMethod($constructMethod);
        $ctrl->code()->addMethod($applicationMethod);
        $ctrl->code()->addMethod($consoleMethod);
        $ctrl->code()->addMethod($helpMethod);
        $ctrl->code()->addMethod($errorMethod);

        $ctrl->save();
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
            $cliErrorMethod->appendToBody("    \$string .= \"    Try \\x1b[1;33m./app help\\x1b[0m for help\" . PHP_EOL . PHP_EOL;");
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
        copy(__DIR__ . '/../../config/resources/index.phtml', $location . '/app/view/index.phtml');
        copy(__DIR__ . '/../../config/resources/error.phtml', $location . '/app/view/error.phtml');
        copy(__DIR__ . '/../../config/resources/error.phtml', $location . '/app/view/exception.phtml');
    }
}