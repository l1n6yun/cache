<?php


namespace L1n6yun\Cache;


/**
 * Class Cache
 * @package Cache
 */
class Cache
{
    /**
     * @var string 缓存目录
     */
    public $cache_path = "/tmp/cache";

    /**
     * Cache constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        if (!empty($options['cache_path'])) {
            $this->cache_path = $options['cache_path'];
        }
    }

    /**
     * 设置缓存
     * @param string $name 缓存变量名
     * @param mixed $value 存储数据
     * @param int $expire 有效时间（秒）
     * @return string
     */
    public function setCache($name, $value, $expire = null)
    {
        if (is_null($expire)) {
            $expire = 0;
        }
        $file = self::_getCacheName($name);
        $data = serialize($value);
        $data = "<?php\n//" . sprintf('%012d', $expire) . "\n exit();?>\n" . $data;
        file_put_contents($file, $data);
        return $file;
    }

    /**
     * 获取缓存
     * @param string $name 缓存变量名
     * @param mixed $default 默认值
     * @return mixed
     */
    public function getCache($name, $default = false)
    {
        $file = self::_getCacheName($name);
        if (file_exists($file) && ($content = file_get_contents($file))) {
            $expire = (int)substr($content, 8, 12);
            if ($expire === 0 || filemtime($file) + $expire >= time()) {
                $content = unserialize(substr($content, 32));
                return $content;
            }
            self::delCache($name);
        }
        return $default;
    }

    /**
     * 清楚缓存
     * @param string $name 缓存变量名
     * @return bool
     */
    public function delCache($name)
    {
        $file = self::_getCacheName($name);
        return file_exists($file) ? unlink($file) : true;
    }

    /**
     * 应用缓存目录
     * @param string $name 缓存变量名
     * @return string
     */
    private function _getCacheName($name)
    {
        if (empty($this->cache_path)) {
            $this->cache_path = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR;
        }
        $this->cache_path = rtrim($this->cache_path, '/\\') . DIRECTORY_SEPARATOR;
        if (!file_exists($this->cache_path)) {
            mkdir($this->cache_path, 0755, true);
        }
        return $this->cache_path . $name;
    }
}