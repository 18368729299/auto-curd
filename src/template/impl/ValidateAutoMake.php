<?php

namespace cbs\tp6curd\template\impl;

use cbs\tp6curd\extend\Utils;
use cbs\tp6curd\template\IAutoMake;
use Symfony\Component\VarExporter\VarExporter;
use think\console\Output;
use think\facade\App;
use think\facade\Db;

class ValidateAutoMake implements IAutoMake
{
    public function check($table, $path, $force)
    {
        $validateName = Utils::camelize($table) . 'Validate';
        $validateFilePath = App::getAppPath() . $path . DS . 'validate' . DS . $validateName . '.php';

        if (!is_dir(App::getAppPath() . $path . DS . 'validate')) {
            mkdir(App::getAppPath() . $path . DS . 'validate', 0755, true);
        }

        if (file_exists($validateFilePath) && !$force) {
            $output = new Output();
            $output->error("$validateName.php已经存在");
            exit;
        }
    }

    public function make($table, $path, $other)
    {
        $validateTpl = dirname(dirname(__DIR__)) . '/tpl/validate.tpl';
        $tplContent = file_get_contents($validateTpl);

        $model = ucfirst(Utils::camelize($table));
        $filePath = empty($path) ? '' : DS . $path;
        $namespace = empty($path) ? '\\' : '\\' . $path . '\\';

        $prefix = config('database.connections.mysql.prefix');
        $column = Db::query('SHOW FULL COLUMNS FROM `' . $prefix . $table . '`');
        $rule = [];
        $attributes = [];
        $scene = [];
        foreach ($column as $vo) {
            if ($vo['Null'] == 'NO') {
                $rule[$vo['Field']] = 'require';
                if ($vo['Field'] != 'id') {
                    $scene['add'][] = $vo['Field'];
                    $scene['edit'][] = $vo['Field'];
                } else {
                    $scene['edit'][] = $vo['Field'];
                }
            } else {
                $rule[$vo['Field']] = '';
            }
            $attributes[$vo['Field']] = $vo['Comment'] ? $vo['Comment'] : $vo['Field'];
        }

        $ruleArr = VarExporter::export($rule);
        $attributesArr = VarExporter::export($attributes);
        $sceneArr = VarExporter::export($scene);

        $tplContent = str_replace('<namespace>', $namespace, $tplContent);
        $tplContent = str_replace('<model>', $model, $tplContent);
        $tplContent = str_replace('<rule>', '' . $ruleArr, $tplContent);
        $tplContent = str_replace('<attributes>', $attributesArr, $tplContent);
        $tplContent = str_replace('<scene>', $sceneArr, $tplContent);

        file_put_contents(App::getAppPath() . $filePath . DS . 'validate' . DS . $model . 'Validate.php', $tplContent);
    }
}
