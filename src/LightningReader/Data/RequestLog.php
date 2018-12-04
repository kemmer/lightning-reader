<?php

namespace LightningReader\Data;

use LightningReader\Database\Information\RequestTable;
use LightningReader\Data\Log;
use LightningReader\Data\Sanitize\DateTimeSanitize;
use LightningReader\Validator\Rule\{ServiceRule, NumericRule, DateTimeRule};


class RequestLog extends Log
{
    protected function configureFields()
    {
        $this->fields = RequestTable::dataFields();
    }

    protected function configurePayload()
    {
        $this->payload = [];
        foreach($this->fields as $field) {
            $this->payload[$field] = null;
        }
    }

    protected function configureValidatorRules()
    {
        $this->validator->newField("service");
        $this->validator->newField("moment");
        $this->validator->newField("http_code");

        $this->validator->addFieldRule("service", new ServiceRule);
        $this->validator->addFieldRule("moment", new DateTimeRule);
        $this->validator->addFieldRule("http_code", new NumericRule);
    }

    public function complete() : bool
    {
        foreach($this->fields as $field) {
            if(empty($this->payload[$field]))
                return false;
        }

        $this->completed = true;
        return $this->completed;
    }

    public function validate() : bool
    {
        $this->validator->setFieldData("service", $this->service);
        $this->validator->setFieldData("moment", $this->moment);
        $this->validator->setFieldData("http_code", $this->http_code);

        $this->validated = $this->validator->validate();
        return $this->validated;
    }

    public function sanitize() : bool
    {
        /* Sanitize $moment */
        $sanitizer = new DateTimeSanitize;
        $this->payload['moment'] = $sanitizer->transform($this->payload['moment']);

        /**
         * The other fields are already ok since
         * here they have been already validated
         */
        $this->sanitized = true;
        return $this->sanitized;
    }

    public function toArray() : array
    {
        return $this->payload;
    }
}
