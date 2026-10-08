<?php

namespace Commerce\Platform\Import;

use Commerce\Platform\Config;
use Commerce\Platform\Logger;
use Bitrix\Main\Loader;

final class CsvImportService
{
    public function import(string $file): array
    {
        if (!is_file($file) || !is_readable($file)) {
            throw new \InvalidArgumentException('CSV file is not readable.');
        }

        if (!Loader::includeModule('iblock')) {
            throw new \RuntimeException('Iblock module is unavailable.');
        }

        $handle = fopen($file, 'rb');

        if (!$handle) {
            throw new \RuntimeException('Unable to open CSV.');
        }

        $header = fgetcsv($handle);
        $required = ['external_id', 'name', 'article', 'price', 'currency', 'brand'];

        if ($header !== $required) {
            fclose($handle);
            throw new \InvalidArgumentException('Invalid CSV header.');
        }

        $created = 0;
        $updated = 0;
        $errors = [];
        $rowNumber = 1;

        while (($row = fgetcsv($handle)) !== false) {
            ++$rowNumber;

            try {
                if (count($row) !== count($required)) {
                    throw new \InvalidArgumentException('Invalid column count.');
                }

                [$externalId, $name, $article, $price, $currency, $brand] = $row;

                if ($externalId === '' || $name === '' || !is_numeric($price)) {
                    throw new \InvalidArgumentException('Invalid required data.');
                }

                $existing = \CIBlockElement::GetList(
                    [],
                    [
                        'IBLOCK_ID' => Config::PRODUCT_IBLOCK_ID,
                        '=XML_ID' => $externalId,
                    ],
                    false,
                    ['nTopCount' => 1],
                    ['ID']
                )->Fetch();

                $element = new \CIBlockElement();

                $fields = [
                    'IBLOCK_ID' => Config::PRODUCT_IBLOCK_ID,
                    'NAME' => $name,
                    'XML_ID' => $externalId,
                    'ACTIVE' => 'Y',
                    'PROPERTY_VALUES' => [
                        'ARTICLE' => $article,
                        'BRAND' => $brand,
                    ],
                ];

                if ($existing) {
                    if (!$element->Update((int)$existing['ID'], $fields)) {
                        throw new \RuntimeException($element->LAST_ERROR);
                    }
                    ++$updated;
                } else {
                    if (!$element->Add($fields)) {
                        throw new \RuntimeException($element->LAST_ERROR);
                    }
                    ++$created;
                }
            } catch (\Throwable $e) {
                $errors[] = ['row' => $rowNumber, 'message' => $e->getMessage()];
                Logger::error('CSV import error', [
                    'row' => $rowNumber,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        fclose($handle);

        return compact('created', 'updated', 'errors');
    }
}
