<?php

namespace App\Scheduler;

use App\Services\{CriteriaService, GroupService, StatusService};
use Illuminate\Support\Carbon;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Support\Facades\Log;

class UpdateCriteriaAndGroup {

    public function __invoke()
    {
        $message      = 'Command Run successfully';
        $currentDate  = Carbon::now()->startOfDay()->toDateString();

        $statusService  = new StatusService();
        $criteriaStatus = $statusService->fetchStatusByModelSlug('criteria', 'started');
        $criteriaStatusExp = $statusService->fetchStatusByModelSlug('criteria', 'expired');
        $groupStatus    = $statusService->fetchStatusByModelSlug('group', 'started');
        $groupStatusExp    = $statusService->fetchStatusByModelSlug('group', 'expired');

        //group status change to start
        $groupService = new GroupService();
        $groupService->updateEndingGroup($currentDate, $groupStatusExp->id);
        $groupService->updateStartingGroup($currentDate, $groupStatus->id);

        //criteria status change to start
        $criteriaService = new CriteriaService();
        $criteriaService->updateEndingCriteria($currentDate, $criteriaStatusExp->id);
        $criteriaService->updateStartingCriteria($currentDate, $criteriaStatus->id);

        $output = new ConsoleOutput();
        $output->writeln("<info>" . $message . "</info>");

    }
}
