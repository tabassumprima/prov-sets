<?php

namespace App\Helpers;

use Aws\Laravel\AwsFacade;
use Aws\DynamoDb\DynamoDbClient;
use Exception;
use Illuminate\Support\Facades\{Auth, Log};

class AwsHelper
{
    public static function invokeLambda($payload, $lamda_name)
    {
        try {
            $lambda = AwsFacade::createClient('sfn');
            $response = $lambda->startExecution(array(
                'stateMachineArn' => env('PROVISION_ARN'),
                'input' => json_encode($payload)
            ));
            return $response->toArray();
        } catch (\Exception $e) {
            Log::error($e);
            return $e->getMessage();
        }
    }

    public static function invokeGroupCodeMapping($payload)
    {
        try {
            $lambda = AwsFacade::createClient('lambda');
            Log::info('creating');
            //executing processing lambda
            $response = $lambda->invoke(array(
                'FunctionName' => 'delta-backend-dev-GroupCode',
                'Payload' => $payload
            ));
            Log::info('invoking');
            return $response->toArray();
        } catch (\Exception $e) {
            Log::error($e);
            return $e->getMessage();
        }
    }

    public static function invokeImport($payload)
    {
        try {
            $lambda = AwsFacade::createClient('sfn');
            $response = $lambda->startExecution(array(
                'stateMachineArn' => env('IMPORT_ARN'),
                'input' => json_encode($payload)
            ));
            return $response->toArray();
        } catch (\Exception $e) {
            Log::error($e);
            return $e->getMessage();
        }
    }

    public static function dynamoInsert($item)
    {
        try{
            $dynamoDbClient = new DynamoDbClient([]);

            $table_name = config('app.dynamo_table');

            $putItemResult = $dynamoDbClient->putItem([
                'TableName' => $table_name,
                'Item' => $item
            ]);

            if (isset($putItemResult['ConsumedCapacity'])) {
                Log::info("Item added successfully!");
            } else {
                Log::error("Error adding item");
            }
        }
        catch(Exception $e){
            Log::error($e);
        }
    }

    public static function invokeoOpeningLambda($payload)
    {
        try {
            $lambda = AwsFacade::createClient('lambda');
            Log::info('creating');
            //executing processing lambda
            $response = $lambda->invoke(array(
                'FunctionName' => env('DATA_IMPORT_ROLL_BACK_FUNCTION'),
                'Payload' => json_encode($payload)
            ));
            Log::info('invoking');
            return $response->toArray();
        } catch (\Exception $e) {
            Log::error($e);
            return $e->getMessage();
        }
    }

    // Function to roll back data import files and revert back data from db
    public static function invokeRollBack($payload)
    {
        try {
            $lambda = AwsFacade::createClient('lambda');
            Log::info('creating');
            //executing processing lambda
            $lambda->invoke(array(
                'FunctionName' =>   env('DATA_IMPORT_ROLL_BACK_FUNCTION'),
                'Payload' => $payload,
                'InvocationType' => 'Event' // This makes the invocation asynchronous
            ));
            Log::info('invoking');
            return true;
        } catch (\Exception $e) {
            Log::error($e);
            return $e->getMessage();
        }
    }
}
