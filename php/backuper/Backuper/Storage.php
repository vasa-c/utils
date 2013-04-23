<?php
/**
 * Хранитель бэкапов - репа git'а
 *
 * @package Backuper
 * @author  Григорьев Олег aka vasa_c <go.vasac@gmail.com>
 */

namespace Backuper;

class Storage
{
    /**
     * Конструктор
     *
     * @param \Backuper\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Сохранить текущее состояние
     */
    public function save()
    {
        $dir = $this->config->get('dir');
        $this->initGit($dir);
        $cmd = array(
            'cd '.$dir,
            'unset GIT_DIR',
            'git add .',
            'git commit --message="'.\addslashes($this->createMessage()).'"',
        );
        if ($this->config->get('git_gc')) {
            $cmd[] = 'git gc';
        }
        $cmd = \implode(' && ', $cmd);
        Helpers::exec($cmd);
    }

    /**
     * Описание коммита
     *
     * @return string
     */
    private function createMessage()
    {
        $message = $this->config->get('commit_message');
        $pattern = '/\{\{\s*(\S+)\s*\}\}/';
        $replace = function ($m) {
            return \date($m[1]);
        };
        $message = \preg_replace_callback($pattern, $replace, $message);
        return $message;
    }

    /**
     * Проверить, есть ли репа в нужном каталоге и создать если нет
     *
     * @param string $dir
     */
    private function initGit($dir)
    {
        if (\is_dir($dir.'/.git')) {
            return;
        }
        $cmd = array(
            'cd '.$dir,
            'unset GIT_DIR',
            'git init',
        );
        $cmd = \implode(' && ', $cmd);
        Helpers::exec($cmd);
    }

    /**
     * @var \Backuper\Config
     */
    private $config;
}
