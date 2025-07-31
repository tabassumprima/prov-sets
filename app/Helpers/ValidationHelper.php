<?php

namespace App\Helpers;

use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Illuminate\Support\{Arr, Collection};
use Rap2hpoutre\FastExcel\FastExcel;
use Carbon\Exceptions\Exception;
use Carbon\Carbon;
use Storage;

class ValidationHelper
{

    private $rules, $headerIsValid, $errors = [], $row_number = 1, $checkEmptyRow, $storagePath;
    public $csvIsValid = true;

    public function __construct($csvFile, $rules, $storagePath, $checkEmptyRow = true, $delimiter = ",")
    {

        $this->rules = json_decode(file_get_contents($rules), true);
        $this->checkEmptyRow = $checkEmptyRow;
        $this->storagePath = $storagePath;
        $this->decodeCsv($csvFile, $delimiter);
    }

    private function decodeCsv($csv, $delimiter)
    {
        (new FastExcel)->configureCsv($delimiter)->import($csv, function ($row) {
            $this->processRow($row);
            $this->row_number++;
        });

        if (count($this->errors) > 0) {
            $this->csvIsValid = false;
            return $this->writeToFile();
        }
        return true;
    }

    private function processRow($row)
    {
        //check if column names are valid (this will only run once)
        if (!$this->headerIsValid) {
            $columns = collect($row)->keys();
            $this->verifyHeaders($columns, collect($this->rules['columns']), $row);
        }

        $this->parseData($row, $this->rules["columns"]);
    }

    private function verifyHeaders(Collection $csvHeaders, Collection $ruleColumns, $row)
    {
        $this->verifyColumnsLength($csvHeaders, $ruleColumns);
        $this->verifyColumnsName($ruleColumns->toArray(), $row);

        if (!$this->headerIsValid)
            return;

        return $this->headerIsValid;
    }

    private function parseData($csvRow, $rules)
    {
        $this->checkConditions($csvRow);
        // looping columns
        foreach ($rules as $key => $rule) {
            //convert data according to rules and validate date format
            $this->dataConversion($rule, $csvRow, $key);
            if ($this->checkEmptyRow)
                $this->checkEmptyRow($csvRow, $key);
        }
    }

    private function checkEmptyRow($row, $key)
    {
        $column = $row[$key];
        if ($column == '' || $column == null) {
            $row['message'] = $key . " cannot be empty on row number " . $this->row_number;
            $this->errors["errors"][] = $row;
        }
    }

    private function isDate($date): bool
    {
        try {

            Carbon::parse($date);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    private function verifyDateFormat($date, $format)
    {
        return Carbon::hasFormat($date, $format);
    }


    private function verifyColumnsLength(Collection $csvColumns, Collection $ruleColumns)
    {
        if ($csvColumns->count() != $ruleColumns->count())
            throw new \Exception("Columns are missing");
    }

    private function verifyColumnsName($ruleColumns, $row)
    {

        if (!Arr::has($row, array_keys($ruleColumns)))
            throw new \Exception("Bad CSV Uploaded");

        //compare collections
        return $this->headerIsValid = true;
    }

    private function dataConversion($rule, $csvRow, $key)
    {
        //by default php cannot format date type
        if (is_array($rule)) {
            // we will check manually if give value is a valid date
            if ($this->isDate($csvRow[$key])) {
                //if date is valid we will verify date according to rules
                if (!$this->verifyDateFormat($csvRow[$key], $rule['format']));
            } else {
                $csvRow['message'] = "invalid date on " . $key . " at row number" . $this->row_number;
                $this->errors["errors"][] = $csvRow;
            }
        } else {

            settype($csvRow[$key], $rule);
        }
    }

    private function checkConditions($csvRow)
    {
        if (isset($this->rules['conditions'])) {
            $conditions = $this->rules["conditions"];
            foreach ($conditions as $condition) {
                $this->operation($condition["value_1"], $condition["value_2"], $condition["operator"], $csvRow);
            }
        }
    }

    private function operation($value_1, $value_2, $operator, $csvRow)
    {
        $operators = collect(["==", "<=", ">=", "<", ">"]);
        if ($operators->doesntContain($operator))
            throw new \Exception("Only Supported Operator are [" . implode(",", $operators->toArray()) . "]");

        switch ($operator) {
            case "==":
                if ($csvRow[$value_1] == $csvRow[$value_2])
                    return true;
                else {
                    $csvRow['message']  = $value_1 .  " and " . $value_2 . " must be equal";
                    $this->errors['errors'][] = $csvRow;
                }
                break;
            case "<":
                if ($csvRow[$value_1] < $csvRow[$value_2])
                    return true;
                else {
                    $csvRow['message']  = $value_1 .  " must be less than " . $value_2;
                    $this->errors['errors'][] = $csvRow;
                }
                break;

            case ">":
                if ($csvRow[$value_1] > $csvRow[$value_2])
                    return true;
                else {
                    $csvRow['message']  = $value_1 .  " must be greater than " . $value_2;
                    $this->errors['errors'][] = $csvRow;
                }
                break;

            case "<=":

                if ($csvRow[$value_1] <= $csvRow[$value_2])
                    return true;
                else {
                    $csvRow['message']  = $value_1 .  " must be less than or equal to " . $value_2;
                    $this->errors['errors'][] = $csvRow;
                }
                break;

            case ">=":
                if ($csvRow[$value_1] >= $csvRow[$value_2])
                    return true;
                else {
                    $csvRow['message']  = $value_1 .  " must be greater than or equal to " . $value_2;
                    $this->errors['errors'][] = $csvRow;
                }
                break;
        }
    }

    private function writeToFile()
    {
        $rows_style = (new StyleBuilder())
            ->setShouldWrapText()
            ->build();
        $path = Storage::disk("private")->path($this->storagePath . '_invalid_data.xlsx');
        $test = $this->errors['errors'];
        (new FastExcel($test))
            ->rowsStyle($rows_style)
            ->export($path);
        return $path;
    }
}
