@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div
                class="card border-0 shadow-lg overflow-hidden"
                style="
                    border-radius: 28px;
                    background: rgba(255,255,255,0.75);
                    backdrop-filter: blur(14px);
                "
            >

                {{-- Header --}}
                <div
                    class="p-4 text-white position-relative"
                    style="
                        background: linear-gradient(135deg, #0f172a, #1e293b, #2563eb);
                    "
                >

                    <div class="d-flex align-items-center justify-content-between">

                        <div>

                            <h2 class="fw-bold mb-1">
                                System Updater
                            </h2>

                            <p class="mb-0 opacity-75">
                                Manage and install latest application updates
                            </p>

                        </div>

                        <div
                            class="d-flex align-items-center justify-content-center rounded-circle"
                            style="
                                width:70px;
                                height:70px;
                                background: rgba(255,255,255,0.15);
                                font-size:30px;
                            "
                        >
                            <i class="fas fa-cloud-download-alt"></i>
                        </div>

                    </div>

                </div>

                {{-- Body --}}
                <div class="card-body p-4 p-lg-5">

                    @if(! empty($connectionError))
                        <div class="alert alert-warning border-0 shadow-sm" style="border-radius:18px;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ $connectionError }}
                        </div>
                    @endif

                    {{-- Version Cards --}}
                    <div class="row g-4 mb-4">

                        <div class="col-md-6">

                            <div
                                class="border-0 shadow-sm h-100 p-4"
                                style="
                                    border-radius:20px;
                                    background:#f8fafc;
                                "
                            >

                                <div class="d-flex align-items-center mb-3">

                                    <div
                                        class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                        style="
                                            width:50px;
                                            height:50px;
                                            background:#dbeafe;
                                            color:#2563eb;
                                        "
                                    >
                                        <i class="fas fa-laptop-code"></i>
                                    </div>

                                    <div>

                                        <small class="text-muted d-block">
                                            Offline Version
                                        </small>

                                        <strong>
                                            Current System
                                        </strong>

                                    </div>

                                </div>

                                <code
                                    class="d-block p-3"
                                    style="
                                        border-radius:12px;
                                        background:#0f172a;
                                        color:#38bdf8;
                                        word-break: break-all;
                                    "
                                >
                                    {{ $local ?? 'Unavailable' }}
                                </code>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div
                                class="border-0 shadow-sm h-100 p-4"
                                style="
                                    border-radius:20px;
                                    background:#f8fafc;
                                "
                            >

                                <div class="d-flex align-items-center mb-3">

                                    <div
                                        class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                        style="
                                            width:50px;
                                            height:50px;
                                            background:#dcfce7;
                                            color:#16a34a;
                                        "
                                    >
                                        <i class="fas fa-server"></i>
                                    </div>

                                    <div>

                                        <small class="text-muted d-block">
                                            Online Version
                                        </small>

                                        <strong>
                                            GitHub Repository
                                        </strong>

                                    </div>

                                </div>

                                <code
                                    class="d-block p-3"
                                    style="
                                        border-radius:12px;
                                        background:#0f172a;
                                        color:#4ade80;
                                        word-break: break-all;
                                    "
                                >
                                    {{ $remote ?? 'Unavailable' }}
                                </code>

                            </div>

                        </div>

                    </div>

                    {{-- Update Status --}}
                    @if($hasUpdate)

                        <div
                            class="alert border-0 shadow-sm d-flex align-items-center"
                            style="
                                border-radius:18px;
                                background:#fff7ed;
                                color:#c2410c;
                            "
                        >

                            <div
                                class="me-3 d-flex align-items-center justify-content-center rounded-circle"
                                style="
                                    width:50px;
                                    height:50px;
                                    background:#fed7aa;
                                "
                            >
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>

                            <div>

                                <h6 class="fw-bold mb-1">
                                    New Update Available
                                </h6>

                                <small>
                                    Your system can now be upgraded to the latest version.
                                </small>

                            </div>

                        </div>

                        {{-- Progress --}}
                        <div class="mt-4">

                            <div class="d-flex justify-content-between mb-2">

                                <span class="fw-semibold">
                                    Updating Progress
                                </span>

                                <span id="progressText">
                                    0%
                                </span>

                            </div>

                            <div
                                class="progress overflow-hidden"
                                style="
                                    height:18px;
                                    border-radius:30px;
                                    background:#e2e8f0;
                                "
                            >

                                <div
                                    id="updateProgressBar"
                                    class="progress-bar progress-bar-striped progress-bar-animated"
                                    role="progressbar"
                                    style="
                                        width:0%;
                                        border-radius:30px;
                                        background: linear-gradient(90deg,#2563eb,#38bdf8);
                                    "
                                ></div>

                            </div>

                        </div>

                        {{-- Logs --}}
                        <div
                            id="updateLogs"
                            class="mt-4 p-4 text-light"
                            style="
                                height:300px;
                                overflow:auto;
                                border-radius:20px;
                                background:#020617;
                                font-family: monospace;
                                font-size:14px;
                            "
                        >

                            <div class="text-secondary">
                                Waiting for update process...
                            </div>

                        </div>

                        {{-- Button --}}
                        <div class="mt-4">

                            <button
                                id="startUpdateBtn"
                                class="btn btn-lg text-white px-5 py-3 shadow"
                                style="
                                    border-radius:18px;
                                    background: linear-gradient(135deg,#2563eb,#0ea5e9);
                                    border:none;
                                "
                            >

                                <i class="fas fa-download me-2"></i>

                                Install Update

                            </button>

                        </div>

                    @else

                        <div
                            class="alert border-0 shadow-sm d-flex align-items-center"
                            style="
                                border-radius:18px;
                                background:#ecfdf5;
                                color:#166534;
                            "
                        >

                            <div
                                class="me-3 d-flex align-items-center justify-content-center rounded-circle"
                                style="
                                    width:50px;
                                    height:50px;
                                    background:#bbf7d0;
                                "
                            >
                                <i class="fas fa-check-circle"></i>
                            </div>

                            <div>

                                <h6 class="fw-bold mb-1">
                                    System Up To Date
                                </h6>

                                <small>
                                    Your application is already running the latest version.
                                </small>

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@if($hasUpdate)

<script>

document.getElementById('startUpdateBtn')
.addEventListener('click', async function () {

    const button = this;

    const progressBar = document.getElementById('updateProgressBar');

    const progressText = document.getElementById('progressText');

    const logs = document.getElementById('updateLogs');

    button.disabled = true;

    button.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2"></span>
        Updating System...
    `;

    logs.innerHTML = '';

    try {

        const response = await fetch(
            "{{ route('admin.system.update.run') }}",
            {
                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            }
        );

        const data = await response.json();

        if(data.results){

            data.results.forEach(result => {

                progressBar.style.width = result.progress + '%';

                progressText.innerHTML = result.progress + '%';

                logs.innerHTML += `
                    <div class="mb-4">

                        <div class="text-info fw-bold mb-2">
                            ➜ ${result.title}
                        </div>

                        <pre class="text-light mb-0">${result.output.join("\n")}</pre>

                    </div>
                `;

                logs.scrollTop = logs.scrollHeight;

            });

        }

        if(data.success){

            logs.innerHTML += `
                <div class="alert alert-success border-0 mt-3">
                    ✅ System updated successfully
                </div>
            `;

            button.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                Update Completed
            `;

        } else {

            logs.innerHTML += `
                <div class="alert alert-danger border-0 mt-3">
                    ❌ Update failed
                </div>
            `;

            button.disabled = false;

            button.innerHTML = `
                <i class="fas fa-download me-2"></i>
                Retry Update
            `;
        }

    } catch(error){

        logs.innerHTML += `
            <div class="alert alert-danger border-0 mt-3">
                ${error.message}
            </div>
        `;

        button.disabled = false;

    }

});

</script>

@endif

@endsection
