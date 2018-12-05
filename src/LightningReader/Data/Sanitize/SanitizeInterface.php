<?php

namespace LightningReader\Data\Sanitize;


interface SanitizeInterface
{
    public function transform(string $data);
}
