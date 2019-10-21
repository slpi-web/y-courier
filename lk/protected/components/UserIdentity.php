<?php

class UserIdentity extends CUserIdentity
{
    const ERROR_STATUS_INACTIVE = 3;
    const ERROR_STATUS_BANNED = 4;
    const ERROR_NOT_ADMIN = 5;


    protected $userId;

    protected $onlyAdmin = false;

    public function allowAdminOnly()
    {
        $this->onlyAdmin = true;
    }

    public function authenticate() {
        $user = User::findUser($this->username);
        if (!$user || !($user instanceof User)) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if ($user->status == User::STATUS_INACTIVE)
            $this->errorCode = self::ERROR_STATUS_INACTIVE;
        else if ($user->status == User::STATUS_BANNED)
            $this->errorCode = self::ERROR_STATUS_BANNED;
        else if ($user->validatePassword($this->password)) {
            $valid = true;
            if ($this->onlyAdmin)
                if (!$user->isAdmin()) {
                    $valid = false;
                    $this->errorCode = self::ERROR_NOT_ADMIN;
                }
            if ($valid) {
                $this->userId = $user->id;
                $this->username = $user->email;
                $user->onLogin();
                $this->errorCode = self::ERROR_NONE;
            }
        } else
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        return !$this->errorCode;
    }

    public function getId() {
        return $this->userId;
    }

}
