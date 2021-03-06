<?php

/**
 * Created by PhpStorm.
 * Date: 2021/7/8
 * Time: 8:23 PM
 */

namespace cbs\tp6curd\command;

use cbs\tp6curd\strategy\AutoMakeStrategy;
use cbs\tp6curd\template\impl\ControllerAutoMake;
use cbs\tp6curd\template\impl\ModelAutoMake;
use cbs\tp6curd\template\impl\ValidateAutoMake;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

class Curd extends Command
{
    protected function configure()
    {
        $this->setName('auto curd')
            ->addOption('table', 't', Option::VALUE_OPTIONAL, 'the table name', null)
            ->addOption('name', 'c', Option::VALUE_OPTIONAL, 'the controller name', null)
            ->addOption('path', 'p', Option::VALUE_OPTIONAL, 'the path', null)
            ->addOption('force', 'f', Option::VALUE_OPTIONAL, 'is force', null)
            ->addOption('soft', 's', Option::VALUE_OPTIONAL, 'is force', null)
            ->setDescription('auto make curd file');
    }

    protected function execute(Input $input, Output $output)
    {
        $table = $input->getOption('table');
        if (!$table) {
            $output->error("请输入 -t 表名");
            exit;
        }

        $controller = $input->getOption('name');
        if (!$controller) {
            $output->error("请输入 -c 控制器名");
            exit;
        }

        $path = $input->getOption('path');
        if (!$path) {
            $path = '';
        }
        $soft = $input->getOption('soft');
        if (!$soft) {
            $soft = false;
        }

        $force = $input->getOption('force');

        $context = new AutoMakeStrategy();

        // 执行生成controller策略
        $context->Context(new ControllerAutoMake());
        $context->executeStrategy($controller, $path, $table, $force);

        // 执行生成model策略
        $context->Context(new ModelAutoMake());
        $context->executeStrategy($table, $path, $soft, $force);

        // 执行生成validate策略
        $context->Context(new ValidateAutoMake());
        $context->executeStrategy($table, $path, '', $force);

        $output->info("auto make curd success");
    }
}
