<?php
namespace Cyantree\Grout\App\Generators\Template;

use Cyantree\Grout\App\App;
use Cyantree\Grout\App\Task;
use Cyantree\Grout\Filter\ArrayFilter;
use Cyantree\Grout\Tools\AppTools;

class TemplateGenerator
{
    /** @var App */
    public $app;

    public $baseTemplate = null;

    private function _decodeName($name)
    {
        $data = AppTools::decodeUri($name, $this->app, $this->app->currentTask->module, $this->app->currentTask->plugin);

        if ($data[1]) {
            $template = $data[1]->path . 'templates/' . $data[2] . '.php';
        } else {
            $template = $data[0]->path . 'templates/' . $data[2] . '.php';
        }

        return $template;
    }

    public function load($name, $in = null, $baseTemplate = null)
    {
        $file = $this->_decodeName($name);

        $c = new TemplateContext();
        $c->generator = $this;
        $c->task = $this->app->currentTask;
        $c->app = $this->app;
        $c->out = new ArrayFilter();
        $c->baseTemplate = $baseTemplate !== null ? $baseTemplate : $this->baseTemplate;
        $c->parse($file, $in);
        return $c;
    }
}