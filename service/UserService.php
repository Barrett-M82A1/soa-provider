<?php
/**
 * UserService业务实现
 */

class UserService
{
    public function getUserName($uid)
    {
        // 假设以下内容从数据库取出
        return $uid.'Hellow';
    }
}