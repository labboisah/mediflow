<div class="card">

    <div class="card-header">

        <h4>
            Data Synchronization
        </h4>

    </div>

    <div class="card-body">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <table class="table table-bordered">

            <thead>

                <tr>

                    <th>Model</th>

                    <th>Table</th>

                    <th>Pending</th>

                    <th>Failed</th>

                    <th>Queued</th>

                    <th>Action</th>

                </tr>

            </thead>

            <tbody>

            @foreach($models as $model)

                <tr>

                    <td>
                        {{ $model['name'] }}
                    </td>

                    <td>
                        {{ $model['table'] }}
                    </td>

                    <td>

                        @if(
                            $model['pending']
                        )

                            <span
                            class="badge bg-warning">

                                {{ $model['pending'] }}

                            </span>

                        @else

                            <span
                            class="badge bg-success">

                                0

                            </span>

                        @endif

                    </td>

                    <td>
                        @if($model['failed'])
                            <span class="badge bg-danger">
                                {{ $model['failed'] }}
                            </span>
                        @else
                            <span class="badge bg-success">
                                0
                            </span>
                        @endif
                    </td>

                    <td>
                        <span class="badge bg-info">
                            {{ $model['queued'] }}
                        </span>
                    </td>

                    <td>
                        @if($model['pending'] || $model['failed'])
                            
                        <button
                            class="btn btn-primary btn-sm"
                            wire:click="sync(@js($model['class']))"
                            wire:loading.attr="disabled"
                            wire:target="sync">

                            <span wire:loading.remove wire:target="sync">
                                Sync
                            </span>

                            <span wire:loading wire:target="sync">
                                Queuing...
                            </span>

                        </button>
                        @else
                            <span class="text-success">
                                Synchronized
                            </span>
                        @endif

                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

</div>
