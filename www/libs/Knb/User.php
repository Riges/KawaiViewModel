<?php
class Knb_User
{
    private $userId;

    public function getId()
    {
        return $this->userId;
    }

    private $rights;
    private $infos;

    public function getRights()
    {
        $this->ensureRightsLoaded();

        return $this->rights;
    }

    public function haveRight($right)
    {
        $this->ensureRightsLoaded();

        return in_array($right, $this->rights);
    }

    public function haveAllRights($rights)
    {
        foreach ($rights as $right) {
            if (!$this->haveRight($right)) return false;
        }
        return true;
    }

    public function haveOneRight($rights)
    {
        foreach ($rights as $right) {
            if ($this->haveRight($right)) return true;
        }
        return false;
    }

    public static function getRightDescription($rightName)
    {
        global $g_database;
        $description = $g_database->fetchOne('SELECT right_description FROM right_ WHERE right_name = ?',
            array($rightName));

        return ($description == NULL) ? $rightName : $description;
    }


    private function ensureRightsLoaded()
    {
        if ($this->rights == NULL) $this->updateRights();
    }

    /**
     * Update the rights stored in this class from the database.
     */
    private function updateRights()
    {
        if ($this->isAnonymous()) {
            $this->rights = array();
            return;
        }

        global $g_database;
        $query = <<< EOD
SELECT right_name AS name, user__right_value AS value
	FROM user__right
		INNER JOIN right_ ON right_.right_id = user__right.right_id
	WHERE user_id = ?
UNION
SELECT right_name AS name, group__right_value AS value
	FROM user__group
		INNER JOIN group__right ON group__right.group_id = user__group.group_id
		INNER JOIN right_ ON right_.right_id = group__right.right_id
	WHERE user_id = ?
EOD;
        $dbRights = $g_database->fetchAll($query, array($this->userId, $this->userId));
        $rights = array();
        foreach ($dbRights as $dbRight) {
            $value = ($dbRight->value === 'allow');
            if (!array_key_exists($dbRight->name, $rights)) {
                $rights[$dbRight->name] = $value;
            } else {
                if ($rights[$dbRight->name] && ($value === FALSE)) {
                    $rights[$dbRight->name] = FALSE;
                }
            }
        }
        $this->rights = array_keys(array_filter($rights));
    }

    private function updateInfos()
    {
        if ($this->isAnonymous()) {
            $this->infos = NULL;
            return;
        }

        $query = <<< EOD
		SELECT *
			FROM user
			WHERE user_id = ?
EOD;
        global $g_database;
        $this->infos = $g_database->fetchRow($query, array($this->userId));
        if ($this->infos == NULL)
            throw new Exception('Invalid user specified in Knb_User::__construct()');
    }

    public function __construct($userId)
    {
        $this->userId = $userId;
        $this->updateInfos();
        $this->rights = NULL;
    }

    public function getLoginForDisplay()
    {
        if ($this->isAnonymous()) return "Anonymous";
        return $this->infos->user_login;
    }

    public function getLogin()
    {
        if ($this->isAnonymous()) return NULL;
        return $this->infos->user_login;
    }

    public function getMail()
    {
        if ($this->isAnonymous()) return NULL;
        return $this->infos->user_mail;
    }

    public function getFullName()
    {
        if ($this->isAnonymous()) return 'Anonymous';
        return $this->infos->user_full_name;
    }

    public function isAnonymous()
    {
        return ($this->userId === -1);
    }
}
?>
