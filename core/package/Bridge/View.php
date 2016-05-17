<?php
namespace Bridge;

// TODO: 下面這樣的寫法是無法抽出 view 的, 請正確處理程式碼

class View
{

    protected static $engine;

    /**
     *  request init
     */
    public static function init($config=[])
    {
      //self::$engine = new Options\ViewTwig($config);
        self::$engine = new Options\ViewNormal($config);
    }

    /**
     *  取得該頁面使用的 layout, 必須先設定, 沒有設定就沒有 layout
     */
    public static function getLayout()
    {
        return self::$engine->get('layout');
    }

    /**
     *  供給 controller 來設定 layout 使用
     */
    public static function setLayout($layout)
    {
        self::$engine->set('layout', $layout);
    }

    /**
     *  僅供給 controller 使用的 render()
     *  用來產生最後有帶 layout 的 view
     */
    public static function render($templateName, $params)
    {
        return self::$engine->render($templateName, $params);
    }

    /**
     *  代入樣版名稱, 來取得完整的檔案路徑
     */
    public static function getPathFile($templateName)
    {
        return self::$engine->getPathFile($templateName);
    }

    /**
     *  將 key, value 設定到 view 裡面
     *  提供 template 可以使用例如 $this->hello 的參數
     */
    public static function assingViewParam($key, $value)
    {
        // TODO: 必須修正以下的做法

        // 黑名單
        if (in_array($key,['config','data'])) {
            return;
        }
        self::$engine->$key = $value;
    }

}
