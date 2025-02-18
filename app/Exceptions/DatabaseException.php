<?php
namespace App\Exceptions;

class DatabaseException extends \Exception {
    private $sqlErrorCode;
    private $sqlState;

    public function __construct($message, $sqlErrorCode = null, $sqlState = null) {
        parent::__construct($message);
        $this->sqlErrorCode = $sqlErrorCode;
        $this->sqlState = $sqlState;
    }

    public function getSqlErrorCode() {
        return $this->sqlErrorCode;
    }

    public function getSqlState() {
        return $this->sqlState;
    }
}
