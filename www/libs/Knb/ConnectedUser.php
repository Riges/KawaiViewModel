<?php

/**
 * Store an user session, it is persisted on the user-side via cookies.
 */
class Knb_ConnectedUser extends Knb_User
{
    const COOKIE_NAME = 'session_id';
    const COOKIE_EXPIRE_DAYS = 30;
    private $sessionId;

    private function getSessionIdFromCookie()
    {
        if (!array_key_exists(self::COOKIE_NAME, $_COOKIE)) return NULL;
        else return $_COOKIE[self::COOKIE_NAME];
    }

    private function getUserFromSessionId($session_id, $database)
    {
        if ($session_id == NULL) return -1;

        $query = <<< EOD
		SELECT *
			FROM session
			WHERE
				session.session_key = ?
EOD;
        $session_infos = $database->fetchRow($query, array($session_id));
        if ($session_infos == NULL) return -1;
        return $session_infos->user_id;
    }

    public function __construct($database)
    {
        $this->sessionId =$this->getSessionIdFromCookie();
        parent::__construct($this->getUserFromSessionId($this->sessionId, $database), $database);
        $this->trimSessions();
    }

    private function trimSessions()
    {
        $query = <<< EOD
			DELETE
				FROM session
				WHERE DATE_ADD(session_start_timestamp, INTERVAL ? DAY) < now();
EOD;
        global $g_database;
        $this->getDatabase()->query($query, array(self::COOKIE_EXPIRE_DAYS));
    }

    private static function generateSessionKey()
    {
        return md5(uniqid(rand(), true));
    }

    private static function createCookie($session_key)
    {
        setcookie(self::COOKIE_NAME, $session_key,
            time() + 60 * 60 * 24 * self::COOKIE_EXPIRE_DAYS, ROOT_URL);
    }

    public static function login($login, $password)
    {
        self::trimSessions();

        global $g_database;
        $user_id = $g_database->fetchOne(
            "SELECT user_id FROM user WHERE user_login = ? AND user_password_md5 = MD5(CONCAT(user_password_nonce, ?))",
            array($login, $password));

        if ($user_id == NULL) throw new Exception("Invalid user name or password");

        $session_key = self::generateSessionKey();

        $g_database->query("INSERT INTO session (user_id, session_key) VALUES (?, ?)", array($user_id, $session_key));

        self::createCookie($session_key);
    }

    public function logout()
    {
        if ($this->isAnonymous()) return;

        global $g_database;
        $g_database->query("DELETE FROM session WHERE session_key = ?", array($this->sessionId));
    }

    public function assertRight($right)
    {
        if (!$this->haveRight($right)) {
            throw new Exception("You (" . $this->getLoginForDisplay() . ") don't have the needed right to do this action : "
                . self::getRightDescription($right));
        }
    }
}

?>
