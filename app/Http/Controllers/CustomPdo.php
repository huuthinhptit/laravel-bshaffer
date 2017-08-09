<?php
namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use OAuth2\Storage\Pdo;

class CustomPdo extends Pdo
{
    function __construct($connection, $config = array())
    {
        $this->config['user_table'] = 'profile';
        parent::__construct($connection, $config);
    }

    public function getUser($username)
    {
        $stmt = $this->db->prepare($sql = sprintf('SELECT * from %s where username=:username', $this->config['user_table']));
        $stmt->execute(array('username' => $username));
        if (!$userInfo = $stmt->fetch(\PDO::FETCH_BOTH)) {
            return false;
        }

        // the default behavior is to use "username" as the user_id
        return array_merge(array(
            'user_id' => $userInfo['id'],
        ), $userInfo);
    }

}