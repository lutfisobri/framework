<?php
namespace Riyu\Helpers\Errors\Handler;

use Riyu\App\Config;
use Riyu\Helpers\Errors\Handler\Contract\Buffering;

class View implements Buffering
{
    private $isSafety;

    public function startOb()
    {
        ob_start();
    }

    public function endOb()
    {
        ob_end_flush();
    }

    public function getOb()
    {
        return ob_get_contents();
    }

    public function cleanOb()
    {
        ob_clean();
    }

    public function flushOb()
    {
        ob_flush();
    }

    public function obStatus()
    {
        return ob_get_status();
    }

    public function obGetLevel()
    {
        return ob_get_level();
    }

    public function render($exception, $isSafety = false)
    {
        $this->isSafety = $isSafety;
        $this->cleanOb();
        $this->flushOb();
        $this->startOb();
        $this->renderException($exception);
        $this->endOb();
    }

    public function renderException($exception)
    {
        $this->renderExceptionStyles();
        $this->renderExceptionHeader($exception);
        $this->renderExceptionBody($exception);
        $this->renderExceptionFooter($exception);
    }

    public function renderExceptionStyles()
    {
        echo '<style>';
        echo 'body { background-color: #fff; color: #333; font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.42857143; margin: 0; padding: 0; }';
        echo 'h1 { font-size: 36px; margin: 0; }';
        echo 'h2 { font-size: 30px; margin: 0; }';
        echo 'h3 { font-size: 24px; margin: 0; }';
        echo 'h4 { font-size: 18px; margin: 0; }';
        echo 'h5 { font-size: 14px; margin: 0; }';
        echo 'h6 { font-size: 12px; margin: 0; }';
        echo 'p { margin: 0 0 10px; }';
        echo 'a { color: #337ab7; text-decoration: none; }';
        echo 'a:hover { color: #23527c; text-decoration: underline; }';
        echo 'a:focus { color: #23527c; text-decoration: underline; }';
        echo 'a:active { color: #23527c; text-decoration: underline; }';
        echo 'a:hover, a:focus { color: #23527c; text-decoration: underline; }';
        echo 'a:active { color: #23527c; text-decoration: underline; }';
        echo 'pre { background-color: #f5f5f5; border: 1px solid #ccc; border-radius: 4px; font-family: Menlo, Monaco, Consolas, "Courier New", monospace; font-size: 12px; line-height: 1.42857143; margin: 0 0 10px; padding: 9.5px; }';
        echo 'code { background-color: #f5f5f5; border: 1px solid #ccc; border-radius: 4px; color: #333; font-family: Menlo, Monaco, Consolas, "Courier New", monospace; font-size: 12px; line-height: 1.42857143; margin: 0 2px; padding: 2px 4px; }';
        echo 'table { background-color: transparent; }';
        echo 'th { text-align: left; }';
        echo 'table { border-collapse: collapse; border-spacing: 0; }';
        echo 'table { border-collapse: collapse; border-spacing: 0; }';
        echo 'td, th { padding: 0; }';
        echo '</style>';
    }

    public function renderExceptionHeader($exception)
    {
        if ($this->isSafety) {
            echo '<h1>Something went wrong with null safety.</h1>';
            echo '<h2>' . $exception->getMessage() . '</h2>';
        } else {
            echo '<h1>Something went wrong.</h1>';
            echo '<h2>' . $exception->getMessage() . '</h2>';
        }
    }

    public function renderExceptionBody($exception)
    {
        echo '<h3>Exception:</h3>';
        echo '<pre>' . $exception->getMessage() . '</pre>';
        echo '<h3>File:</h3>';
        echo '<pre>' . $exception->getFile() . '</pre>';
        echo '<h3>Line:</h3>';
        echo '<pre>' . $exception->getLine() . '</pre>';
        echo '<h3>Trace:</h3>';
        echo '<pre>' . $exception->getTraceAsString() . '</pre>';
    }

    public function renderExceptionFooter($exception)
    {
        $url = Config::get('app');
        echo '<p><a href="' . $url['url'] . '">' . $url['name'] . '</a></p>';
    }

    public function renderExceptionStylesWithBootstrap()
    {
        echo '<style>';
        echo 'body { background-color: #fff; color: #333; font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.42857143; margin: 0; padding: 0; }';
        echo 'h1 { font-size: 36px; margin: 0; }';
        echo 'h2 { font-size: 30px; margin: 0; }';
        echo 'h3 { font-size: 24px; margin: 0; }';
        echo 'h4 { font-size: 18px; margin: 0; }';
        echo 'h5 { font-size: 14px; margin: 0; }';
        echo 'h6 { font-size: 12px; margin: 0; }';
        echo 'p { margin: 0 0 10px; }';
        echo 'a { color: #337ab7; text-decoration: none; }';
        echo 'a:hover { color: #23527c; text-decoration: underline; }';
        echo 'a:focus { color: #23527c; text-decoration: underline; }';
        echo 'a:active { color: #23527c; text-decoration: underline; }';
        echo 'a:hover, a:focus { color: #23527c; text-decoration: underline; }';
        echo 'a:active { color: #23527c; text-decoration: underline; }';
        echo 'pre { background-color: #f5f5f5; border: 1px solid #ccc; border-radius: 4px; font-family: Menlo, Monaco, Consolas, "Courier New", monospace; font-size: 12px; line-height: 1.42857143; margin: 0 0 10px; padding: 9.5px; }';
        echo 'code { background-color: #f5f5f5; border: 1px solid #ccc; border-radius: 4px; color: #333; font-family: Menlo, Monaco, Consolas, "Courier New", monospace; font-size: 12px; line-height: 1.42857143; margin: 0 2px; padding: 2px 4px; }';
        echo 'table { background-color: transparent; }';
        echo 'th { text-align: left; }';
        echo 'table { border-collapse: collapse; border-spacing: 0; }';
        echo '</style>';
    }
}