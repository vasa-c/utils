# skeleton.php

Шаблон для создания init.d-скриптов.

Переименовать файл, изменить нужные protected-переменные класса и использовать:

```plain
$ ./script.php start
Started (PID=5605)

$ ./script.php restart
Stopped...
Sleep (3s)...
Started (PID=5605)

$ ./script.php status
Daemon is running. Pid=5605. Starting: 12.08.2013, 18:47:03

$ ./script.php stop
Stopped
```

