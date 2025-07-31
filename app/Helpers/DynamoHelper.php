<?php

namespace App\Helpers;

use Aws\DynamoDb\DynamoDbClient;
use Illuminate\Support\Facades\Log;
use Exception;

class DynamoHelper
{
    protected $dynamoDbClient;
    protected $dynamoTable;

    public function __construct()
    {
        $this->dynamoTable = config('app.dynamo_table');
    }

    public function dynamoInsert($item)
    {
        try {
            $dynamoDbClient = new DynamoDbClient([
                'region' => env('AWS_DEFAULT_REGION'),
                'version' => 'latest'
            ]);

            $putItemResult = $dynamoDbClient->putItem([
                'TableName' => $this->dynamoTable,
                'Item' => $item
            ]);

            if (isset($putItemResult)) {
                Log::info("Item added successfully!");
            } else {
                Log::error("Error adding item");
            }
        } catch (Exception $e) {
            Log::error($e);
            throw new Exception($e->getMessage());
        }
    }

    public function getItem($key)
    {
        $dynamoDbClient = new DynamoDbClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest'
        ]);

        try {
            $result = $dynamoDbClient->getItem([
                'TableName' => $this->dynamoTable,
                'Key' => [
                        'tenant_id' => [
                            'S' => $key,
                        ],
                    ],
            ]);

            if (isset($result['Item'])) {
                return $result['Item'];
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Item not found'
                ]);
            }
        } catch (Exception $e) {
            Log::error($e);
            throw new Exception($e->getMessage());

        }
    }

    public function itemMapping($tenant_id, $organization_shortcode = null, $access_token = null, $access_token_expiry = null, $bucket = null, $rds_db_name = null, $rds_host = null, $rds_port = null , $rds_password = null, $rds_user = null )
    {
        $mapping = [
            'tenant_id'                 => ['S' => $tenant_id],
            'organization_short_code'   => ['S' => $organization_shortcode],
            'access_token'              => ['S' => $access_token ?? ""],
            'bucket'                    => ['S' => $bucket ?? config('constant.aws_bucket')],
            'rds_db_name'               => ['S' => $rds_db_name ?? config('constant.rds_db_name')],
            'rds_host'                  => ['S' => $rds_host ?? config('constant.rds_host') ],
            'rds_password'              => ['S' => $rds_password ?? config('constant.rds_password')],
            'rds_user'                  => ['S' => $rds_user ?? config('constant.rds_user')],
            'access_token_expiry_date'  => ['S' => $access_token_expiry ?? ""],
            'rds_port'                  => ['S' => $rds_port ?? config('constant.rds_port')]
        ];
        return $mapping;
    }
}
