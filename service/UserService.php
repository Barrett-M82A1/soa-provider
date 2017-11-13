<?php
/**
 * UserService业务实现
 */

class UserService
{
    public static function getUserInfo($uid)
    {
        // 假设以下内容从数据库取出
        return [
            'id'       => $uid,
            'username' => 'mengkang',
        ];
    }

    public static function updateUsername($uid,$name){
        // 数据入库操作...
        return true;
    }
}