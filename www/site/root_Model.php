<?php

Class Root
{
    private static $database;

    public static function setDatabase($db)
    {
        self::$database = $db;
    }

    public static function login($login, $password)
    {
        $query = 'DELETE FROM session
                  WHERE DATE_ADD(session_start_timestamp, INTERVAL ? DAY) < now();';
        self::$database->query($query, array(Knb_ConnectedUser::COOKIE_EXPIRE_DAYS));
        $user_id = self::$database->fetchOne(
            "SELECT user_id FROM user WHERE user_mail = ? AND user_password_md5 = MD5(CONCAT(user_password_nonce, ?))",
            array($login, $password));

        if ($user_id == NULL) throw new Exception(_("Invalid user name or password"));

        $session_key = Knb_ConnectedUser::generateSessionKey();
        self::$database->query("INSERT INTO session (user_id, session_key) VALUES (?, ?)", array($user_id, $session_key));
        Knb_ConnectedUser::createCookie($session_key);
    }
}