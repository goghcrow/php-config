<?php
/**
 * Created by PhpStorm.
 * User: chuxiaofeng
 * Date: 16/6/25
 * Time: 下午4:21
 */

namespace xiaofeng;

/**
 * Class ConfigGen
 * @package xiaofeng
 * 从Config数组生成对应代码文件
 * 让IDE对配置进行代码提示!!!
 */
class ConfigGen {

    public static function toArray($obj) {
        // ini_set("xdebug.max_nesting_level", 1500);
        if (!is_object($obj)) {
            return $obj;
        }
        $ret = [];
        foreach ((array) $obj as $k => $v) {
            $ret[$k] = is_object($v) ? static::toArray($v) : $v;
        }
        return $ret;
    }

    /**
     * 将配置生成
     * @param array $conf
	 * @param string $dir
     * @param string $clazz
     * @param string $namespace
     * @return ConfigObject
     */
    public static function requireOnce(array $conf, $dir, $clazz = "ConfigObject", $namespace = __NAMESPACE__) {
        if (!static::isLegalVarName($clazz)) {
            throw new \InvalidArgumentException("illegal class name \"$clazz\"");
        }

        static::genClassHelper($conf, $clazz, $clazzes);
        // TODO 添加自动生成标记
        $header = "<?php" . PHP_EOL . "namespace $namespace;";
        $body = implode(PHP_EOL . PHP_EOL, $clazzes);
        $footer = "return new $clazz;";
        $code = implode(PHP_EOL . PHP_EOL, [$header, $body, $footer]);
        $fTmpConf = "$dir/$clazz.php";
        file_put_contents($fTmpConf, $code);
        return require_once $fTmpConf;
    }

    protected static function isAllKeyString($var) {
        if (!is_array($var) || empty($var)) {
            return false;
        }
        foreach ($var as $k => $_) {
            if (!is_string($k)) {
                return false;
            }
        }
        return true;
    }

    protected static function isLegalVarName($varName) {
        return preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $varName);
    }

    protected static function formatVar($varName) {
        if (!static::isLegalVarName($varName)) {
            // throw new \RuntimeException("var name \"$varName\" is illegal");
            $varName = preg_replace_callback("/([^a-zA-Z0-9_])/", function($matches) {
                static $i = 0;
                // TODO 汉子转拼音
                return "_" . ++$i . "_";
            }, $varName);
        }
        return $varName;
    }

    protected static function genClassHelper(array $conf, $clazz, &$clazzes = []) {
        if (empty($conf)) {
            return false;
        }

        $props = [];
        $ctorAssigns = [];

        foreach($conf as $k => $v) {
            $prop = static::formatVar($k);
            if (static::isAllKeyString($v)) {
                $subClazz = $clazz . "_" . static::formatVar($k);
                $props[] = "\tpublic \$$prop;";
                $ctorAssigns[] = "\t\t\$this->$prop = new $subClazz;";
                static::genClassHelper($v, $subClazz, $clazzes);
            } else {
                $props[] = "\tpublic \$$prop = " . var_export($v, true) . ";";
            }
        }

        $propsStr =  implode(PHP_EOL, $props);
        $ctorAssignsStr =  implode(PHP_EOL, $ctorAssigns);

        $clazzes[] = <<<TPL
final class $clazz {

$propsStr

\tpublic function __construct() {
$ctorAssignsStr
\t}
}
TPL;
        return true;
    }
}