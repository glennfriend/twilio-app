<?php
namespace Bridge\Options;

class ViewNormal
{
    protected $data;
    protected $renderBeforeCallback = null;

    /**
     *  init
     */
    public function init()
    {
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function get($key, $defaultValue=null)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
        return $defaultValue;
    }

    /* --------------------------------------------------------------------------------

    -------------------------------------------------------------------------------- */
    public function renderBefore($callback)
    {
        $this->renderBeforeCallback = $callback;
    }

    public function render($path, $params)
    {
        if (false !== strstr($path,'..')) {
            throw new \Exception('View path is error');
            exit;
        }

        $templateName = basename($path);

        if (!preg_match('/^[a-zA-Z0-9\.]+$/', $templateName)) {
            throw new \Exception('View name ['. htmlspecialchars($templateName) .'] is error');
            exit;
        }

        $tmp = explode('\\', get_class($this));
        $className = strtolower($tmp[count($tmp)-1]);

        if (!file_exists($path)) {
            throw new \Exception('View file "'. htmlspecialchars($path) .'" not found!');
            exit;
        }

        // event
        if ($event = $this->get('renderBeforeEvent')) {
            $event();
        }

        // load template
        $___path    = $path;
        $___params  = $params;
        $render = function() use ($___path, $___params) {
            // EXTR_SKIP - 如果有沖突，覆蓋已有的變量
            extract($___params, EXTR_OVERWRITE);
            include $___path;
        };
        ob_start();
            $render();
            $content = ob_get_contents();
        ob_end_clean();

        // load layout
        $layoutTemplate = '';
        $layout = $this->get('layout');
        if ($layout && file_exists($layout)) {
            ob_start();
                include $layout;
                $layoutTemplate = ob_get_contents();
            ob_end_clean();
        }

        $output = str_replace("{{content}}", $content, $layoutTemplate);
        return $output;
    }

}
