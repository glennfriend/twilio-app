<?php
namespace App\Module\View;

/**
 *
 */
class ViewHelper
{
    /**
     *  get view path
     */
    public static function getPath()
    {
        return conf('app.path') . '/resource/views';
    }

    public static function get($dotName)
    {
        // validate to show error
        // a-z A-Z _ -
        $dots = explode('.', $dotName);
        foreach ($dots as $name) {
            if (!preg_match('/^[a-z_][a-zA-Z0-9_-]+$/', $name)) {
                throw new Exception("Error: template name is wrong! ===> [{$dotName}]");
            }
        }

        // 併接
        $viewFile = self::getPath();
        $count = count($dots);
        for ($i = 0; $i < $count; $i++) {
            if (($i+1)===$count) {
                $viewFile .= '.' . $dots[$i];
            }
            else {
                $viewFile .= '/' . $dots[$i];
            }
        }

        $viewFile .= '.phtml';
        return $viewFile;
    }

}
