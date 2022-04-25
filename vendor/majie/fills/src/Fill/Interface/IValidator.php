<?php

namespace Majie\Fills\Fill\Interface;

interface IValidator
{
    public function validate($data = null): void;

    public function afterValidate(): void;

    public function rules(): array;
    
}
