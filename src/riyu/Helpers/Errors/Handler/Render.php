<?php
namespace Riyu\Helpers\Errors\Handler;

class Render
{
    public function view($view, $data = [])
    {
        $view = new View();
        $view->startOb();
        extract($data);
        include $view;
        $view->endOb();
        return $view->getOb();
    }

    public function json($data = [])
    {
        return json_encode($data);
    }

    public function xml($data = [])
    {
        return $data;
    }

    public function text($data = [])
    {
        return $data;
    }

    public function html($data = [])
    {
        return $data;
    }

    public function pdf($data = [])
    {
        return $data;
    }

    public function csv($data = [])
    {
        return $data;
    }

    public function image($data = [])
    {
        return $data;
    }
}