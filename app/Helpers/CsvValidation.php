<?php

namespace App\Helpers;

use DateTime;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class CsvValidation
{
    public function validateCSV($filePath, $delimiter, $rulesFilePath, $invalid_path) {

        // Read rules from S3
        $rulesData = Storage::disk('s3')->get($rulesFilePath);
        $rules = json_decode($rulesData, true);

        $this->validateHeaders($filePath, $rules['columns'], ',');

        // Create error array
        $errors = [];

        // Validate data format and math operations
        $expressionLanguage = new ExpressionLanguage();
        foreach ((new FastExcel)->sheet(1)->configureCsv($delimiter)->import($filePath) as $data) {
            $rowErrors = [];
            foreach ($rules['rules'] as $rule) {
                $field = $rule['field'];
                $value = $data[$field] ?? null;
                $type = $rule['type'];
                $message = $rule['message'];
                switch ($type) {
                    case 'date':
                        $format = $rule['format'];
                        $date = DateTime::createFromFormat($format, $value);
                        if (!$date || $date->format($format) != $value) {
                            $rowErrors[] = $message;
                        }
                        break;
                    case 'number':
                        $min = $rule['min'];
                        $max = $rule['max'];
                        if ($value < $min || $value > $max) {
                            $rowErrors[] = $message;
                        }
                        break;
                    case 'expression':
                        $expression = $rule['expression'];
                        $vars = array_merge($data, ['_rule' => $rule]);
                        $result = $expressionLanguage->evaluate($expression, $vars);
                        if (!$result) {
                            $rowErrors[] = $message;
                        }
                        break;
                }
            }
            // Check for null values
            if (in_array(null, $data)) {
                $rowErrors[] = 'There are null values in the CSV file.';
            }

            // Add errors to error array
            if (!empty($rowErrors)) {
                $errors[] = array_merge($data, ['errors' => implode(', ', $rowErrors)]);
            }
        }


        // If there are errors, write them to a new CSV file
        if (!empty($errors)) {
            $errorFilePath = 'errors.csv';
            $path = Storage::disk("private")->path($invalid_path . 'errors.csv');
            (new FastExcel($errors))->export($path);
            throw ValidationException::withMessages([
                'csvFile' => 'There were errors in the CSV file. Please check the errors.csv file for details.'
            ]);
        }
        return true;
    }

    function validateHeaders($filePath, $expectedHeaders, $delimiter) {
        $file = fopen($filePath, 'r');
        if (!$file) {
            throw new Exception("Failed to open file: $filePath");
        }

        // Read header row from file
        $header = fgetcsv($file, 0, $delimiter);
        // dd($header, $expectedHeaders, $header == $expectedHeaders);
        if (!$header) {
            fclose($file);
            throw new Exception("Failed to read header row from file: $filePath");
        }

        // Check that header row matches expected headers
        if ($header != $expectedHeaders) {
            fclose($file);
            throw new Exception("Header row in file does not match expected headers.");
        }

        fclose($file);
    }
}
