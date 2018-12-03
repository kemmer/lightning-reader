<?php

namespace LightningReader\Data;

/**
 * RequestLogInterface
 *
 * A RequestLog should be able to input
 * the data represented by following methods
 */
interface RequestLogInterface
{
    public function setService(string $data);
    public function setDateTime(string $data);
    public function setRequestDetails(string $data);
    public function setHttpCode(string $data);
}
