<?php

namespace App\Console\Commands;

use App\Services\{CriteriaService, GroupService};
use Symfony\Component\Console\Output\ConsoleOutput;
use App\Services\StatusService;
use Illuminate\Console\Command;
use Carbon\Carbon;


class CreteriaAndGroupStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'criteria-and-group:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will change status of group and criteria to start';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
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
