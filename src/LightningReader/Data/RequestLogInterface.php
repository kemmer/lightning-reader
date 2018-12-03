<?php

namespace LightningReader\Data;


interface RequestLogInterface
{
    public function setService();
    public function setDateTime();
    public function setRequestDetails();
    public function setHttpCode();
}
