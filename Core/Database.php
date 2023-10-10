<?php
    class Database
    {
        const HOST = 'localhost';
        const USENAME = 'root';
        const PASSWORD = '';
        const DB_NAME = 'db_tree';
        const PORT = '3308';

        public function connect()
        {
            $connect = mysqli_connect(self::HOST, self::USENAME, self::PASSWORD, self::DB_NAME, self::PORT);

            mysqli_set_charset($connect, "utf8");

            if (mysqli_connect_errno() === 0)
            {
                return $connect;
            }

            return false;
        }
    }