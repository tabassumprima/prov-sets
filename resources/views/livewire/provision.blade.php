<div class="card">
    <div class="card-header">
        <h4 class="card-title">Provision calculation</h4>
    </div>
    <form wire:submit.prevent="save">
        <input type="hidden" wire:model.lazy='type' value="provision">
        <div class="card-body">
            <p>Calculate provision as at the specified date. Last calculation was carried out on
                <span class="text-primary">{{ $lastProvision }}</span>
            </p>
            <div class="row">
                <div class="col-12 col-lg-8 pt-1 position-relative">
                    <input type="text" id="pd-disable" class="form-control pickadate-disable"
                        wire:model="valuation_date" placeholder="{{ $lastSync }}" />
                </div>
                <div class="col-12 col-lg-4 position-relative pt-1">

                    <button type="submit" class="btn btn-primary" data-toggle="modal"
                        id="onshownbtn" data-target="#onshown"
                        {{ $provisionAllowed ? '' : 'disabled' }}>
                        <div wire:ignore>
                        <i data-feather='refresh-cw'></i>
                        &nbsp; Run
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
