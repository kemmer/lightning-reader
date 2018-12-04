<?php

namespace LightningReader\Data;

use LightningReader\Data\CompletenessInterface;
use LightningReader\Data\RequestLogInterface;

/**
 * RequestLogAbstract
 *
 * Contract specifying an base class
 * for RequestLog implementation
 */
abstract class RequestLogAbstract
    implements RequestLogInterface, CompletenessInterface, RegisterInterface
{
}
