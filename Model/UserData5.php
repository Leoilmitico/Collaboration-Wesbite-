<?php /** @noinspection ALL */
class UserData5 implements JsonSerializable
{
    // Class for user data
    protected $_id, $_firstName, $_lastName, $_email, $_password;

    public function __construct($dbRow)
    {
        if ($dbRow && is_array($dbRow)) {
            $this->_id = $dbRow['id'];
            $this->_email = $dbRow['email'];
            $this->_password = $dbRow['password'];
        } else {
            // Set default values
            $this->_id = -1;
            $this->_email = '';
            $this->_password = '';
        }
    }

    public function jsonSerialize()
    {
        return [
            '_id' => $this->_id,
            '_email' => $this->_email,
            '_password' => $this->_password,
        ];
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function getPassword()
    {
        return $this->_password;
    }
}