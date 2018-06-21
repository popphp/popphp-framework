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

        $htaccess  = "<IfModule mod_rewrite.c>" . PHP_EOL;
        $htaccess .= "    RewriteEngine On" . PHP_EOL . PHP_EOL;
        $htaccess .= "    RewriteCond %{REQUEST_FILENAME} -s [OR]" . PHP_EOL;
        $htaccess .= "    RewriteCond %{REQUEST_FILENAME} -l [OR]" . PHP_EOL;
        $htaccess .= "    RewriteCond %{REQUEST_FILENAME} -f [OR]" . PHP_EOL;
        $htaccess .= "    RewriteCond %{REQUEST_FILENAME} -d" . PHP_EOL . PHP_EOL;
        $htaccess .= "    RewriteRule ^.*$ - [NC,L]" . PHP_EOL;
        $htaccess .= "    RewriteRule ^.*$ index.php [NC,L]" . PHP_EOL;
        $htaccess .= "</IfModule>" . PHP_EOL;

        file_put_contents($location . '/public/.htaccess', $htaccess);
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
     * Create views method
     *
     * @param  string $location
     * @return void
     */
    protected function createViews($location)
    {
        $index  = "<!DOCTYPE html>" . PHP_EOL;
        $index .= "<html>" . PHP_EOL;
        $index .= "<head>" . PHP_EOL;
        $index .= "    <title><?=\$title; ?></title>" . PHP_EOL;
        $index .= "</head>" . PHP_EOL;
        $index .= "<body>" . PHP_EOL;
        $index .= "    <h1><?=\$title; ?></h1>" . PHP_EOL;
        $index .= "</body>" . PHP_EOL;
        $index .= "</html>" . PHP_EOL;

        file_put_contents($location . '/view/index.phtml', $index);

        $error  = "<!DOCTYPE html>" . PHP_EOL;
        $error .= "<html>" . PHP_EOL;
        $error .= "<head>" . PHP_EOL;
        $error .= "    <title><?=\$title; ?></title>" . PHP_EOL;
        $error .= "</head>" . PHP_EOL;
        $error .= "<body>" . PHP_EOL;
        $error .= "    <h1 style=\"color: #f00;\"><?=\$title; ?></h1>" . PHP_EOL;
        $error .= "</body>" . PHP_EOL;
        $error .= "</html>" . PHP_EOL;

        file_put_contents($location . '/view/error.phtml', $error);
    }
}