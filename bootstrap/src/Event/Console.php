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
namespace Pop\Bootstrap\Event;

/**
 * Console event class
 *
 * @category   Pop\Bootstrap
 * @package    Pop\Bootstrap
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2018 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    3.8.0
 */
class Console
{

    /**
     * Display console header
     *
     * @return void
     */
    public static function header()
    {
        $consoleTitle = 'Pop Bootstrap Console';
        echo PHP_EOL . '    ' . $consoleTitle . PHP_EOL;
        echo '    ' . str_repeat('=', strlen($consoleTitle)) . PHP_EOL . PHP_EOL;
    }

    /**
     * Display console footer
     *
     * @return void
     */
    public static function footer()
    {
        echo PHP_EOL;
    }

}