<?php


namespace Language\api;


class ApiCallDTO
{
    private $status;
    private $data;
    private $errorType;

    /**
     * ApiCallDTO constructor.
     * @param $status
     * @param $data
     * @param $errorType
     */
    public function __construct($status, $data, $errorType)
    {
        $this->status = $status;
        $this->data = $data;
        $this->errorType = $errorType;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getErrorType()
    {
        return $this->errorType;
    }



}