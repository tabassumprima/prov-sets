<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Folder\Request;
use App\Services\{FolderService, SummaryService, ImportFileService, FolderImportDetailService};
use App\Helpers\{RouterHelper,CustomHelper, AwsHelper};
use Illuminate\Support\Facades\{ DB,Log};
use App\Http\Requests\Folder\FilesRequest;
use Exception;

class FolderController extends Controller
{
    private $folderService, $routerHelper;

    public function __construct(FolderService $folderService)
    {
        $this->middleware('prevent_transaction', ['except' => ['index','show']]);
        $this->folderService = $folderService;
        $this->routerHelper = new RouterHelper;
    }

    public function index()
    {
        $folders = $this->folderService->getAllFoldersData();
        return view('user.folder.index', compact('folders'));
    }

    public function create()
    {
        return view('user.folder.create');
    }

    public function store(Request $request)  // Create new folder
    {
        $summaryService = new SummaryService;

        $allStartedSummary = $summaryService->fetchAllStartedSummary();

        // Check already pending summaries
        if( count($allStartedSummary) > 0 )
            return $this->routerHelper->redirectBack('error', 'Only one folder can be created at a time. You must approve the pending summaries first.' );

        $error = false;
        $request->validated();
        DB::beginTransaction();

        try
        {
            $this->folderService->storeFolder($request);
            DB::commit();
        }
        catch(Exception $e)
        {
            DB::rollBack();
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }

        if ($error)
            return $this->routerHelper->redirectBack($error, 'Failed to add folder.' );

        return redirect()->route('data-import.index')->with('success', 'Data import has been created!');
    }

    public function showImportFiles($folderId)
    {
        $folderService = new FolderService;
        $folderData = $this->folderService->getFoldersStatus($folderId); // summary status with folder
        $folderStatus = $folderData->summary?->status->slug;

        $Status = $this->folderService->getFolderCurrentStatus($folderId); // folder status

        $data_import_files = $this->folderService->listFiles($folderId);
        $tables = config('constant.import_table_types');
        return view('user.folder.show',compact('data_import_files','folderId','folderStatus', 'tables','Status'));
    }

    // function to store data import files
    public function storeImportFile(FilesRequest $request)
    {
        $error = false;
        $request->validated();
        try
        {
            // Store file at AWS S3
            $fileData  = $this->folderService->storeFiles($request->all());

            if($fileData)
                $this->folderService->changeFolderStatus($request->id);

        }
        catch(Exception $e)
        {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);

            // if data not inserted in db have some error than delete file from s3 which is uploaded
            $this->folderService->deleteFiles( $fileData['path'], $fileData['name'] );
        }

        if ($error)
            return redirect()->back()->with('error', 'Failed to add file.'.$message);

        $folderId = CustomHelper::encode($request->id) ;

        return $folderId;
    }

    public function getFile($id, $fileName)
    {
        $filePath =  $this->folderService->getFullPath($id) ;

        return $this->folderService->downloadFiles($filePath, $fileName);
    }

    function deleteFile($filename, $import_detail_id)
    {
        $parts                      =   explode('_', $filename);
        $table_name                 =   $parts[0] . '_' . $parts[1];
        $folderImportDetailService  =   new FolderImportDetailService();
        $DependantTableList         =   $folderImportDetailService->getDependentTables($table_name);
        $isTableDependant           =   $folderImportDetailService->checkIfTablesExistForOrg( $DependantTableList, $import_detail_id );
        // $import_id                  =   $folderImportDetailService->getImportId( $folderId, $table_name );


        if($isTableDependant)
            return redirect()->back()->withErrors(['error' => 'You must first delete the dependent record before proceeding']);

        $preparedRollBackPayload =  $this->folderService->prepareRollBackPayload($import_detail_id, $table_name);

        $result =  AwsHelper::invokeRollBack($preparedRollBackPayload);

        if($result)
          return redirect()->route('data-import-files', ['import_id' => $import_detail_id])->with('success', 'file deleted successfully!');
    }

    function getFolderStatus($folder_id)
    {
        $folderStatus = $this->folderService->getPresentStatus($folder_id);

        $RunningStatus        =  CustomHelper::fetchStatus('running' , 'default');
        $completedStatus      =  CustomHelper::fetchStatus('started' , 'default');
        $failedStatus         =  CustomHelper::fetchStatus('failed'  , 'default');
        $failedPendingStatus  =  CustomHelper::fetchStatus('pending_import' , 'default');

        if(in_array($folderStatus, [$RunningStatus, $completedStatus]))
            return ['status' => 'success', 'message' => 'Folder is running'];
        else if($folderStatus == $failedStatus)
            return ['status' => 'failed', 'message' => 'Folder is failed'];
        else
            return ['status' => 'error', 'message' => 'Folder is not running'];
    }
}
