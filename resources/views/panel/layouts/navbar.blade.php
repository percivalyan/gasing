<div class="header-wrapper"> <!-- [Mobile Media Block] start -->
    <div class="me-auto pc-mob-drp">
        <ul class="list-unstyled">
            <!-- ======= Menu collapse Icon ===== -->
            <li class="pc-h-item pc-sidebar-collapse">
                <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                    <i class="ti ti-menu-2"></i>
                </a>
            </li>
            <li class="pc-h-item pc-sidebar-popup">
                <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                    <i class="ti ti-menu-2"></i>
                </a>
            </li>

            {{-- <!-- Mobile Search Dropdown -->
            <li class="dropdown pc-h-item d-inline-flex d-md-none">
                <a class="pc-head-link dropdown-toggle arrow-none m-0" data-bs-toggle="dropdown" href="#"
                    role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="ti ti-search"></i>
                </a>
                <div class="dropdown-menu pc-h-dropdown drp-search p-2" style="min-width: 200px;">
                    <form class="px-0 position-relative">
                        <div class="form-group mb-0 d-flex align-items-center">
                            <i data-feather="search" class="me-1"></i>
                            <input type="search" id="mobileSearchInput" class="form-control border-0 shadow-none"
                                placeholder="Search here...">
                        </div>
                        <div id="mobileSearchResults" class="dropdown-menu w-100 mt-1"
                            style="display:none; position:absolute; top:100%; left:0; z-index:1000;"></div>
                    </form>
                </div>
            </li>

            <!-- Desktop Search -->
            <li class="pc-h-item d-none d-md-inline-flex position-relative">
                <form class="header-search position-relative">
                    <i data-feather="search" class="icon-search me-1"></i>
                    <input type="search" id="desktopSearchInput" class="form-control" placeholder="Search here...">
                    <div id="desktopSearchResults" class="dropdown-menu w-100 mt-1"
                        style="display:none; position:absolute; top:100%; left:0; z-index:1000;"></div>
                </form>
            </li>

            <script>
                document.addEventListener('DOMContentLoaded', function() {

                    // Daftar fitur / menu yang bisa dicari
                    const features = [{
                            name: 'Dashboard',
                            url: '/dashboard'
                        },
                        {
                            name: 'User',
                            url: '/user'
                        },
                        {
                            name: 'Role',
                            url: '/role'
                        },
                        {
                            name: 'Article',
                            url: '/article'
                        },
                        {
                            name: 'Category',
                            url: '/category'
                        },
                        {
                            name: 'Gallery',
                            url: '/gallery'
                        },
                        {
                            name: 'Letter',
                            url: '/letter'
                        },
                        {
                            name: 'Session Log',
                            url: '/session-log'
                        },
                        {
                            name: 'About',
                            url: '/about'
                        }
                    ];

                    function setupSearch(inputId, resultId) {
                        const input = document.getElementById(inputId);
                        const resultContainer = document.getElementById(resultId);

                        input.addEventListener('input', function() {
                            const query = input.value.toLowerCase().trim();
                            let results = '';

                            if (query.length > 0) {
                                const filtered = features.filter(f => f.name.toLowerCase().includes(query));
                                if (filtered.length > 0) {
                                    results = filtered.map(f =>
                                        `<a href="${f.url}" class="dropdown-item">${f.name}</a>`).join('');
                                } else {
                                    results = '<span class="dropdown-item text-muted">No results found</span>';
                                }
                                resultContainer.innerHTML = results;
                                resultContainer.style.display = 'block';
                            } else {
                                resultContainer.style.display = 'none';
                            }
                        });

                        // Tutup dropdown saat klik di luar
                        document.addEventListener('click', function(e) {
                            if (!input.contains(e.target) && !resultContainer.contains(e.target)) {
                                resultContainer.style.display = 'none';
                            }
                        });
                    }

                    // Setup untuk desktop & mobile
                    setupSearch('desktopSearchInput', 'desktopSearchResults');
                    setupSearch('mobileSearchInput', 'mobileSearchResults');
                });
            </script> --}}

        </ul>
    </div>
    <!-- [Mobile Media Block end] -->
    <div class="ms-auto">
        <ul class="list-unstyled">
            <li class="dropdown pc-h-item">
                <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                    role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="ti ti-mail"></i>
                </a>
                @php
                    use App\Models\Session;
                    use App\Models\User;
                    use Illuminate\Support\Facades\Auth;

                    $isAdmin = optional(Auth::user()->role)->name === 'Administrator';

                    $systemLogs = $isAdmin
                        ? Session::with('user')->orderByDesc('last_activity')->limit(5)->get()
                        : collect();
                @endphp

                <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
                    <div class="dropdown-header d-flex align-items-center justify-content-between">
                        <h5 class="m-0">{{ $isAdmin ? 'System Logs' : 'Message' }}</h5>
                        <a href="#!" class="pc-head-link bg-transparent">
                            <i class="ti ti-x text-danger"></i>
                        </a>
                    </div>

                    <div class="dropdown-divider"></div>

                    <div class="dropdown-header px-0 text-wrap header-notification-scroll position-relative"
                        style="max-height: calc(100vh - 215px)">
                        <div class="list-group list-group-flush w-100">
                            @if ($isAdmin && $systemLogs->count() > 0)
                                @foreach ($systemLogs as $log)
                                    @php
                                        $userName =
                                            $log->user->name ??
                                            (preg_match(
                                                '/User\s+([A-Za-z\s]+)\s+berhasil/',
                                                $log->description,
                                                $matches,
                                            )
                                                ? $matches[1]
                                                : 'Sistem');
                                    @endphp
                                    <a class="list-group-item list-group-item-action">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <img src="{{ $log->user && $log->user->avatar
                                                    ? asset('storage/' . $log->user->avatar)
                                                    : url('assets/images/user/avatar-2.jpg') }}"
                                                    alt="user-image" class="user-avtar">
                                            </div>
                                            <div class="flex-grow-1 ms-1">
                                                <span class="float-end text-muted">
                                                    {{ \Carbon\Carbon::createFromTimestamp($log->last_activity)->diffForHumans() }}
                                                </span>
                                                <p class="text-body mb-1">
                                                    <b>{{ $userName }}</b> melakukan
                                                    <b>{{ strtoupper($log->action) }}</b><br>
                                                    <small class="text-muted">{{ $log->description ?? '-' }}</small>
                                                </p>
                                                <span class="text-muted">{{ $log->ip_address ?? 'No IP' }}</span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @elseif ($isAdmin)
                                <div class="text-center py-3 text-muted">
                                    Tidak ada aktivitas sistem terbaru.
                                </div>
                            @else
                                {{-- Pesan default jika bukan admin --}}
                                <a class="list-group-item list-group-item-action">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <img src="{{ url('assets/images/user/avatar-3.jpg') }}" alt="user-image"
                                                class="user-avtar">
                                        </div>
                                        <div class="flex-grow-1 ms-1">
                                            <p class="text-body mb-1">
                                                <b>Hi {{ Auth::user()->name }}</b>, selamat datang di sistem 👋
                                            </p>
                                            <span class="text-muted">Tidak ada pesan baru.</span>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="dropdown-divider"></div>
                    <div class="text-center py-2">
                        @if ($isAdmin)
                            <a href="{{ url('session-logs') }}" class="link-primary">Lihat semua log</a>
                        @else
                            <a href="#!" class="link-primary">View all</a>
                        @endif
                    </div>
                </div>

            </li>
            <li class="dropdown pc-h-item header-user-profile">
                <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                    role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
                    <img src="{{ url('') }}/assets/images/user/avatar-2.jpg" alt="user-image" class="user-avtar">
                    <span>{{ Auth::user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                    <div class="dropdown-header">
                        <div class="d-flex mb-1">
                            <div class="flex-shrink-0">
                                <img src="{{ url('') }}/assets/images/user/avatar-2.jpg" alt="user-image"
                                    class="user-avtar wid-35">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ Auth::user()->name }}</h6>
                                <span>{{ optional(Auth::user()->role)->name }}</span>
                            </div>
                            <a href="{{ url('logout') }}" class="pc-head-link bg-transparent"><i
                                    class="ti ti-power text-danger"></i></a>
                        </div>
                    </div>
                    <ul class="nav drp-tabs nav-fill nav-tabs" id="mydrpTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="drp-t1" data-bs-toggle="tab"
                                data-bs-target="#drp-tab-1" type="button" role="tab" aria-controls="drp-tab-1"
                                aria-selected="true"><i class="ti ti-user"></i>
                                Profile</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="drp-t2" data-bs-toggle="tab" data-bs-target="#drp-tab-2"
                                type="button" role="tab" aria-controls="drp-tab-2" aria-selected="false"><i
                                    class="ti ti-settings"></i> Setting</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="mysrpTabContent">
                        <div class="tab-pane fade show active" id="drp-tab-1" role="tabpanel"
                            aria-labelledby="drp-t1" tabindex="0">
                            <a href="{{ route('user.edit-profile', Auth::user()->id) }}" class="dropdown-item">
                                <i class="ti ti-edit-circle"></i>
                                <span>Edit Profile</span>
                            </a>
                            {{-- <a href="#!" class="dropdown-item">
                                <i class="ti ti-user"></i>
                                <span>View Profile</span>
                            </a>
                            <a href="#!" class="dropdown-item">
                                <i class="ti ti-clipboard-list"></i>
                                <span>Social Profile</span>
                            </a>
                            <a href="#!" class="dropdown-item">
                                <i class="ti ti-wallet"></i>
                                <span>Billing</span>
                            </a> --}}
                            <a href="{{ url('logout') }}" class="dropdown-item">
                                <i class="ti ti-power"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                        <div class="tab-pane fade" id="drp-tab-2" role="tabpanel" aria-labelledby="drp-t2"
                            tabindex="0">
                            <a href="#!" class="dropdown-item">
                                <i class="ti ti-help"></i>
                                <span>Support</span>
                            </a>
                            <a href="#!" class="dropdown-item">
                                <i class="ti ti-user"></i>
                                <span>Account Settings</span>
                            </a>
                            <a href="#!" class="dropdown-item">
                                <i class="ti ti-lock"></i>
                                <span>Privacy Center</span>
                            </a>
                            <a href="#!" class="dropdown-item">
                                <i class="ti ti-messages"></i>
                                <span>Feedback</span>
                            </a>
                            <a href="#!" class="dropdown-item">
                                <i class="ti ti-list"></i>
                                <span>History</span>
                            </a>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
