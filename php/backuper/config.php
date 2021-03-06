<?php
/**
 * Конфигурация бэкапа
 *
 * @author Григорьев Олег aka vasa_c <go.vasac@gmail.com>
 */

return array(
    /* Путь к каталогу для хранения бэкапов.
     * Должен быть создан предварительно.
     */
    'dir' => '',

    /* Запускать "git gc" после каждого коммита. */
    'git_gc' => true,

    /* Формат сообщения коммита.
     * Переменные соответствуют date()
     */
    'commit_message' => 'backup {{ d }}.{{ m }}.{{ Y }} {{ H }}:{{ i }}',

    /* Список баз данных в формате "имя" => "массив параметров"
     * имя - используется в имени файла
     * параметры описаны ниже в комментариях к "defaults"
     */
    'databases' => array(
        /*
        'test' => array(
            'username' => 'test',
            'dbname'   => 'test',
            'password' => 'test',
            'ignore_table' => array('logs'),
        ),
         */
    ),

    /* Параметры по умолчанию.
     * Наследуются параметрами конкретных баз, если в них не переопределены на свои.
     */
    'defaults' => array(

        /**
         * Сколько времени в секундах должно пройти с предыдущей записи файла.
         * Например, если стоит ежесуточно на кроне и кто-то посреди дня случайно
         * запускает напрямую.
         * Должно быть чуть меньше точного периода (в сутках - 86400).
         * null - время не проверяется.
         */
        'period' => 86000,

        /* Дамп базы одним файлом.
         * FALSE - для каждой таблицы создаётся отдельный файл.
         */
        'single_file' => true,

        /* Шаблон имени файла с дампом, если используется один файл на базу.
         * Отсчитывется относительно $dir.
         */
        'single_filename' => '{{ db }}.sql',

        /* Шаблон имён файлов, если для каждой таблицы создаётся свой. */
        'table_filename' => '{{ db }}/{{ table }}.sql',

        /* Использовать расширенную форму INSERT.
         * Все данные таблицы вставляются одним запросом с множеством VALUES.
         * FALSE - запрос на каждую запись.
         */
        'extended_insert' => true,

        /* Выводить ли комментарии в дампе
         */
        'dump_comments' => false,

        /* Выводить время бэкапа после "Dump completed on"
         * Работает только с $dump_comments=TRUE
         * Минус: если база/таблица не изменилась, дамп всё равно изменится.
         */
        'dump_date' => false,

        /* Список таблиц, которые следует игнорировать. */
        'ignore_tables' => array(),

        /* Список таблиц в которых не следует сохранять данные, но структура нужна */
        'ignore_data' => array(),

        /**
         * Если сохраняется по таблицам, то требуется получить их список
         * TRUE: используется запрос через PDO Mysql. Требуется его наличие.
         * FALSE: через вызов 'echo "SHOW TABLES" | mysql'
         */
        'show_tables_pdo' => true,

        /* Параметры конкретной базы (переопределяются в databases) */
        'host' => 'localhost',
        'port' => null,
        'username' => null,
        'password' => null,
        'dbname'   => null,
    ),
);
