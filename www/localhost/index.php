<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<h1 style="text-align: center;">欢迎使用DNMP！</h1>';
echo '<h2>版本选择</h2>';
echo '<div style="display:flex;justify-content: space-evenly;">';
echo '<h3><a href="/">PHP72</a></h3>';
echo '<h3><a href="/73/">PHP73</a></h3>';
echo '<h3><a href="/74/">PHP74</a></h3>';
echo '<h3><a href="/80/">PHP80</a></h3>';
echo '<h3><a href="/81/">PHP81</a></h3>';
echo '</div>';
echo '<h2>版本信息</h2>';

echo '<ul>';
echo '<li>PHP版本：', PHP_VERSION, '</li>';
echo '<li>Nginx版本：', $_SERVER['SERVER_SOFTWARE'], '</li>';
echo '<li>MySQL服务器版本：', getMysqlVersion(), '</li>';
echo '<li>Redis服务器版本：', getRedisVersion(), '</li>';
echo '<li>MongoDB服务器版本：', getMongoVersion(), '</li>';
echo '</ul>';

echo '<h2>已安装扩展</h2>';
printExtensions();


/**
 * 获取MySQL版本
 * @return string
 */
function getMysqlVersion()
{
    if (extension_loaded('PDO_MYSQL')) {
        try {
            $dbh = new PDO('mysql:host=mysql80;dbname=mysql', 'xiaoyu', 'xiaoyu');
            $sth = $dbh->query('SELECT VERSION() as version');
            $info = $sth->fetch();
            return $info['version'];
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    } else {
        return 'PDO_MYSQL 扩展未安装 ×';
    }

}

/**
 * 获取Redis版本
 * @return string
 */
function getRedisVersion()
{
    if (extension_loaded('redis')) {
        try {
            $redis = new Redis();
            $redis->connect('redis62', 6379);
            $redis->auth('123456');
            /** @var Redis $info */
            $info = $redis->info();
            return $info['redis_version'];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    } else {
        return 'Redis 扩展未安装 ×';
    }
}

/**
 * 获取MongoDB版本
 * @return string
 * @throws \MongoDB\Driver\Exception\Exception
 */
function getMongoVersion()
{
    if (extension_loaded('mongodb')) {
        try {
            $manager = new MongoDB\Driver\Manager('mongodb://root:root@mongo60:27017');
            $command = new MongoDB\Driver\Command(array('serverStatus'=>true));

            $cursor = $manager->executeCommand('admin', $command)->toArray();

            return $cursor[0]->version;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    } else {
        return 'MongoDB 扩展未安装 ×';
    }
}

/**
 * 获取已安装扩展列表
 */
function printExtensions()
{
    echo '<ol>';
    foreach (get_loaded_extensions() as $i => $name) {
        echo "<li>", $name, '=', phpversion($name), '</li>';
    }
    echo '</ol>';
}