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
        $cmd = array(
            'cd '.$this->config->get('dir'),
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
        $message = \preg_replace_callback($pattern, array($this, 'replaceM'), $message);
        return $message;
    }

    private function replaceM($m)
    {
        return \date($m[1]);
    }

    /**
     * @var \Backuper\Config
     */
    private $config;
}
