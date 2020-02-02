<?php

declare(strict_types=1);

namespace App\Service;

use Jawira\CaseConverter\CaseConverterException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Jawira\CaseConverter\Convert;

class ValidationFormatter
{
    /**
     * @param ConstraintViolationListInterface $violations
     *
     * @return array
     * @throws CaseConverterException
     */
    public function format(ConstraintViolationListInterface $violations): array
    {
        $result = [];

        /** @var ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
            $params = preg_split(
                '/\[|\]|\./',
                $violation->getPropertyPath(),
                -1,
                PREG_SPLIT_NO_EMPTY
            );

            /** @var array $array */
            $array = $violation->getMessage();

            for ($i = count($params) - 1; $i >= 0; $i--) {
                $array = [
                    $params[$i] => $array
                ];
            }

            /** @var array $result */
            $result = array_replace_recursive($array, $result);
        }

        return $this->convertKeysToSnake($result);
    }

    /**
     * @param array $array
     *
     * @return array
     * @throws CaseConverterException
     */
    private function convertKeysToSnake(array $array): array
    {
        $result = [];

        /**
         * @var string $key
         * @var array|string $value
         */
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this->convertKeysToSnake($value);
            }

            $key = (new Convert($key))->toSnake();
            $result[$key] = $value;
        }

        return $result;
    }
}
