<?php
namespace App\Utility\Url;

/**
 *  路徑管理
 *      - Url Generator
 *
 *  @see https://en.wikipedia.org/wiki/Uniform_Resource_Identifier
 */
class AdminUrlManager
{

    /**
     *  儲存基本路徑資訊
     */
    protected static $data = [];

    /**
     *
     */
    public static function init($option)
    {
        self::$data = [
            'basePath'  => $option['basePath'],
            'baseUrl'   => $option['baseUrl'],
            'host'      => $option['host'],
        ];
    }

    public static function getBaseUrl()
    {
        return self::$data['baseUrl'];
    }

    public static function getHost()
    {
        return self::$data['host'];
    }

    /**
     *  傳回網站基本目錄 uri
     */
    public static function getUrl($path)
    {
        return self::$data['baseUrl'] .'/'. $pathFile;
    }

    /* ================================================================================
        extends
    ================================================================================ */

    /**
     *
     */
    public static function createUrl($segment, $args=[])
    {
        $url = self::$data['baseUrl'] . $segment;
        if (!$args) {
            return $url;
        }
        $query = [];
        foreach ($args as $key => $value) {
            $query[] = $key .'='. $value;
        }
        return $url . '?' . join('&', $query);
    }

    /**
     *
     */
    public static function createUri($segment, $args=[])
    {
        return 'http://' . self::$data['host'] . self::createUrl($segment, $args);
    }

    /* ================================================================================
        產生專案以外的網址
    ================================================================================ */

    // public function getxxxxxx()




}
