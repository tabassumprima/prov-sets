<?php

namespace App\Jobs;

use App\Helpers\AwsHelper;
use App\Helpers\CustomHelper;
use App\Services\ImportDetailService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\{Str};
class ImportDetail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $payload;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
        $this->onQueue('import');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $start = microtime(true);
        try{
            CustomHelper::log("Job is processing");

            $lambda_response = AwsHelper::invokeLambda($this->payload, 'delta-import-test');
            CustomHelper::log($lambda_response);
            $response = json_decode($lambda_response['Payload']->getContents(), true);

            //Looks For Errors In Response
            if($lambda_response['FunctionError'] != "" || $response['statusCode'] != 200 )
                throw new Exception($response['errorMessage'], 500);



        }catch(Exception $e)
        {
            Log::error($e->getMessage());
        }
        $time_elapsed_secs = microtime(true) - $start;
        CustomHelper::log("Job finised in " . $time_elapsed_secs . " seconds");

    }
}
