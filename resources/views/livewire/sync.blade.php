<div class="card">
    <div class="card-header">
        <h4 class="card-title">Sync policy data</h4>
    </div>
    <form wire:submit.prevent="save">
        <input type="hidden" wire:model.lazy='type' value="sync">
        <div class="card-body">
            <p>Syncronize transactional data to update the portal. Last syncronization was done
                on {{$lastSync}}
                <span class="text-primary"></span>
            </p>
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4 position-relative pt-1">
                    <button type="submit" class="btn btn-primary">
                        <div wire:ignore>
                            <i data-feather='refresh-cw'></i>
                            <span>Sync</span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

