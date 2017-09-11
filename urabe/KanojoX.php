<?php 
/**
 * A Database data struct 
 * 
 * Kanajo means girlfriend and this class saves the connection data used to connect to
 * a MySQL database.
 * @version 1.0.0
 * @api Makoto Urabe
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
class KanojoX
{
    /**
     * @var string $error 
     * The last error description.
     */
    public $error;
    /**
     * @var string $host Can be either a host name or an IP address.
     */
    public $host = "127.0.0.1";
    /**
     * @var string $db_name The default database to be used when performing queries.
     */
    public $db_name = "avalon";
    /**
     * @var string $user_name The MySQL user name.
     */
    public $user_name = "root";
    /**
     * @var string|NULL $server The MySQL user password can be null.
     */
    public $password = "";
    /**
     * Creates a MySQl connection object.
     *
     * @return mysqli The connection object
     */
    public function create_connection()
    {
        try {
            $conn = new mysqli($this->host, $this->user_name, $this->password, $this->db_name);
            return $conn;
        } catch (mysqli_sql_exception $e) {
            $this->error = $e->getMessage();
            return FALSE;
        }
    }
}
?>