{{-- resources/views/layouts/partials/sidebar.blade.php --}}
<nav class="pc-sidebar">
    <div class="navbar-wrapper">

        {{-- LOGO --}}
        <div class="py-2 text-center">
            <a href="{{ url('/') }}"
                class="b-brand text-primary d-inline-flex align-items-center justify-content-center">
                <img src="{{ asset('assets/logo_gasing.jpg') }}" alt="Logo" class="img-fluid logo-lg"
                    style="max-height: 60px;">
                <span class="fw-bold fs-2 d-none d-md-inline ms-2 text-black">GASING</span>
            </a>
        </div>

        <div class="navbar-content">
            @php
                $userRole = Auth::user()->role_id ?? null;

                // List of permission keys we will ask for
                $permissionKeys = [
                    'User' => 'PermissionUser',
                    'Role' => 'PermissionRole',
                    'Batch' => 'PermissionBatch',
                    'Category' => 'PermissionCategory',
                    'Article' => 'PermissionArticle',
                    'Document' => 'PermissionDocument',
                    'ReferenceNumber' => 'PermissionReferenceNumber',
                    'LetterType' => 'PermissionLetterType',
                    'Gallery' => 'PermissionGallery',
                    'About' => 'PermissionAbout',
                    'Organization Structure' => 'PermissionOrgStructure',
                    'Organization Member' => 'PermissionOrgMember',
                    'Session Log' => 'PermissionSessionLog',
                    'Teacher Event' => 'PermissionTeacherEvent',
                    'Add Teacher Event Kepala Sekolah' => 'PermissionAddTeacherEvent',
                    'Student Event' => 'PermissionStudentEvent',
                    'Add Student Event Kepala Sekolah' => 'PermissionAddStudentEvent',
                    'User Guru Les Gasing' => 'PermissionUserGuruLesGasing',
                    'Student Course' => 'PermissionStudentCourse',
                    'Lesson Schedule' => 'PermissionLessonSchedule',
                    'Subject' => 'PermissionSubject',
                    'Assign Teacher' => 'PermissionAssignTeacher',
                    'Assessments' => 'PermissionAssessments',
                    'Attendance' => 'PermissionAttendance',

                    // Tambahan permission
                    'Footer' => 'PermissionFooter',
                    'Assign Teacher Event' => 'PermissionAssignTeacherEvent',
                    'Event Schedule' => 'PermissionEventSchedule',
                    'Event Batch' => 'PermissionEventBatch',
                ];

                // Fetch permissions into local variables dynamically
                foreach ($permissionKeys as $permName => $varName) {
                    ${$varName} = App\Models\PermissionRole::getPermission($permName, $userRole);
                }

                // isActive helper (safe if included multiple times)
                if (!function_exists('isActive')) {
                    function isActive($patterns = [], $byRoute = [])
                    {
                        foreach ($byRoute as $r) {
                            if (request()->routeIs($r)) {
                                return 'active';
                            }
                        }
                        foreach ($patterns as $p) {
                            if (request()->is($p)) {
                                return 'active';
                            }
                        }
                        return '';
                    }
                }
            @endphp

            <ul class="pc-navbar">
                {{-- DASHBOARD --}}
                <li class="pc-item">
                    <a href="{{ url('dashboard') }}" class="pc-link {{ isActive(['dashboard', 'dashboard/*']) }}">
                        <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                        <span class="pc-mtext">Beranda</span>
                    </a>
                </li>

                {{-- SESSION LOGS --}}
                @if ($PermissionSessionLog)
                    <li class="pc-item">
                        <a href="{{ route('session.logs') }}"
                            class="pc-link {{ isActive(['session-logs*'], ['session.logs']) }} ">
                            <span class="pc-micon"><i class="ti ti-history"></i></span>
                            <span class="pc-mtext">Log Aktivitas</span>
                        </a>
                    </li>
                @endif

                {{-- USER & PERMISSION --}}
                @if ($PermissionUser || $PermissionRole)
                    <li class="pc-divider mt-3 mb-1 text-uppercase text-muted small fw-bold ps-3">Akses Pengguna</li>
                @endif

                @if ($PermissionUser)
                    <li class="pc-item">
                        <a href="{{ url('user') }}" class="pc-link {{ isActive(['user*']) }}">
                            <span class="pc-micon"><i class="ti ti-user"></i></span>
                            <span class="pc-mtext">Pengguna</span>
                        </a>
                    </li>
                @endif

                @if ($PermissionRole)
                    <li class="pc-item">
                        <a href="{{ url('role') }}" class="pc-link {{ isActive(['role*']) }}">
                            <span class="pc-micon"><i class="ti ti-shield-check"></i></span>
                            <span class="pc-mtext">Hak Akses</span>
                        </a>
                    </li>
                @endif

                {{-- MEDIA PUBLIKASI --}}
                @if ($PermissionCategory || $PermissionArticle || $PermissionGallery)
                    <li class="pc-divider mt-3 mb-1 text-uppercase text-muted small fw-bold ps-3">Media Publikasi</li>
                @endif

                @if ($PermissionCategory)
                    <li class="pc-item">
                        <a href="{{ url('category') }}" class="pc-link {{ isActive(['category*']) }}">
                            <span class="pc-micon"><i class="ti ti-folders"></i></span>
                            <span class="pc-mtext">Kategori</span>
                        </a>
                    </li>
                @endif

                @if ($PermissionArticle)
                    <li class="pc-item">
                        <a href="{{ url('article') }}" class="pc-link {{ isActive(['article*']) }}">
                            <span class="pc-micon"><i class="ti ti-news"></i></span>
                            <span class="pc-mtext">Artikel</span>
                        </a>
                    </li>
                @endif

                @if ($PermissionGallery)
                    <li class="pc-item">
                        <a href="{{ route('gallery.list') }}"
                            class="pc-link {{ isActive(['gallery*'], ['gallery.*']) }}">
                            <span class="pc-micon"><i class="ti ti-photo"></i></span>
                            <span class="pc-mtext">Dokumentasi</span>
                        </a>
                    </li>
                @endif

                {{-- SEKRETARIAT --}}
                @if ($PermissionDocument || $PermissionReferenceNumber || $PermissionLetterType)
                    <li class="pc-divider mt-3 mb-1 text-uppercase text-muted small fw-bold ps-3">Sekretariat</li>
                @endif

                @if ($PermissionDocument)
                    <li class="pc-item">
                        <a href="{{ url('document') }}" class="pc-link {{ isActive(['document*']) }}">
                            <span class="pc-micon"><i class="ti ti-file-text"></i></span>
                            <span class="pc-mtext">Dokumen</span>
                        </a>
                    </li>
                @endif

                @if ($PermissionReferenceNumber)
                    <li class="pc-item">
                        <a href="{{ route('referencenumber.list') }}"
                            class="pc-link {{ isActive(['referencenumber*'], ['referencenumber.*']) }}">
                            <span class="pc-micon"><i class="ti ti-hash"></i></span>
                            <span class="pc-mtext">Nomor Referensi</span>
                        </a>
                    </li>
                @endif

                @if ($PermissionLetterType)
                    <li class="pc-item">
                        <a href="{{ url('lettertype') }}" class="pc-link {{ isActive(['lettertype*']) }}">
                            <span class="pc-micon"><i class="ti ti-mail-opened"></i></span>
                            <span class="pc-mtext">Jenis Surat</span>
                        </a>
                    </li>
                @endif

                {{-- TENTANG KAMI --}}
                @if ($PermissionAbout || $PermissionFooter)
                    <li class="pc-divider mt-3 mb-1 text-uppercase text-muted small fw-bold ps-3">Tentang Kami</li>
                @endif

                @if ($PermissionAbout)
                    <li class="pc-item">
                        <a href="{{ route('about.list') }}" class="pc-link {{ isActive(['about*'], ['about.*']) }}">
                            <span class="pc-micon"><i class="ti ti-info-circle"></i></span>
                            <span class="pc-mtext">Profil Website</span>
                        </a>
                    </li>
                @endif

                @if ($PermissionFooter)
                    <li class="pc-item">
                        <a href="{{ route('footer.list') }}"
                            class="pc-link {{ isActive(['footer*'], ['footer.*']) }}">
                            <span class="pc-micon"><i class="ti ti-layout-navbar"></i></span>
                            <span class="pc-mtext">Footer Website</span>
                        </a>
                    </li>
                @endif

                {{-- STRUKTUR ORGANISASI --}}
                @if ($PermissionOrgStructure || $PermissionOrgMember)
                    <li class="pc-divider mt-3 mb-1 text-uppercase text-muted small fw-bold ps-3">Struktur Organisasi
                    </li>
                @endif

                @if ($PermissionOrgStructure)
                    <li class="pc-item">
                        <a href="{{ route('organization.structure.list') }}"
                            class="pc-link {{ isActive(['organization/structure*'], ['organization.structure.*']) }}">
                            <span class="pc-micon"><i class="ti ti-briefcase"></i></span>
                            <span class="pc-mtext">Posisi / Jabatan</span>
                        </a>
                    </li>
                @endif

                @if ($PermissionOrgMember)
                    <li class="pc-item">
                        <a href="{{ route('organization.member.list') }}"
                            class="pc-link {{ isActive(['organization/member*'], ['organization.member.*']) }}">
                            <span class="pc-micon"><i class="ti ti-users"></i></span>
                            <span class="pc-mtext">Daftar Anggota</span>
                        </a>
                    </li>
                @endif

                {{-- EVENT GASING --}}
                @if ($PermissionTeacherEvent || $PermissionStudentEvent)
                    <li class="pc-divider mt-3 mb-1 text-uppercase text-muted small fw-bold ps-3">Event Gasing</li>

                    @if ($PermissionTeacherEvent)
                        <li class="pc-item">
                            <a href="{{ route('teacher_event.list') }}"
                                class="pc-link {{ isActive(['teacher-event*'], ['teacher_event.*']) }}">
                                <span class="pc-micon"><i class="ti ti-calendar-event"></i></span>
                                <span class="pc-mtext">Event Guru</span>
                            </a>
                        </li>
                    @endif

                    @if ($PermissionStudentEvent)
                        <li class="pc-item">
                            <a href="{{ route('student_event.list') }}"
                                class="pc-link {{ isActive(['student-event*'], ['student_event.*']) }}">
                                <span class="pc-micon"><i class="ti ti-calendar-stats"></i></span>
                                <span class="pc-mtext">Event Siswa</span>
                            </a>
                        </li>
                    @endif
                @endif

                {{-- REGISTRASI EVENT --}}
                @if ($PermissionAddTeacherEvent || $PermissionAddStudentEvent)
                    <li class="pc-divider mt-3 mb-1 text-uppercase text-muted small fw-bold ps-3">Registrasi Event</li>

                    @if ($PermissionAddTeacherEvent)
                        <li class="pc-item">
                            <a href="{{ route('teacher_event.formregistration') }}"
                                class="pc-link {{ isActive(['teacher-event/formregistration'], ['teacher_event.formregistration']) }}">
                                <span class="pc-micon"><i class="ti ti-user-plus"></i></span>
                                <span class="pc-mtext">Registrasi Guru</span>
                            </a>
                        </li>

                        <li class="pc-item">
                            <a href="{{ route('teacher_event.my_registration') }}"
                                class="pc-link {{ isActive(['teacher-event/my-registration'], ['teacher_event.my_registration']) }}">
                                <span class="pc-micon"><i class="ti ti-clipboard-list"></i></span>
                                <span class="pc-mtext">Daftar Registrasi Guru</span>
                            </a>
                        </li>
                    @endif

                    @if ($PermissionAddStudentEvent)
                        <li class="pc-item">
                            <a href="{{ route('student_event.formregistration') }}"
                                class="pc-link {{ isActive(['student-event/formregistration'], ['student_event.formregistration']) }}">
                                <span class="pc-micon"><i class="ti ti-user-plus"></i></span>
                                <span class="pc-mtext">Registrasi Siswa</span>
                            </a>
                        </li>

                        <li class="pc-item">
                            <a href="{{ route('student_event.my_registration') }}"
                                class="pc-link {{ isActive(['student-event/my-registration'], ['student_event.my_registration']) }}">
                                <span class="pc-micon"><i class="ti ti-clipboard-list"></i></span>
                                <span class="pc-mtext">Daftar Registrasi Siswa</span>
                            </a>
                        </li>
                    @endif
                @endif

                {{-- MANAJEMEN EVENT GASING (RELASI & JADWAL) --}}
                @if ($PermissionAssignTeacherEvent || $PermissionEventSchedule || $PermissionEventBatch)
                    <li class="pc-divider mt-3 mb-1 text-uppercase text-muted small fw-bold ps-3">Manajemen Event
                        Gasing</li>

                    @if ($PermissionAssignTeacherEvent)
                        <li class="pc-item">
                            <a href="{{ route('event-assign.index') }}"
                                class="pc-link {{ isActive(['event-assign*'], ['event-assign.*']) }}">
                                <span class="pc-micon"><i class="ti ti-arrows-left-right"></i></span>
                                <span class="pc-mtext">Relasi Guru & Siswa</span>
                            </a>
                        </li>
                    @endif

                    @if ($PermissionEventSchedule)
                        <li class="pc-item">
                            <a href="{{ route('event_schedule.list') }}"
                                class="pc-link {{ isActive(['event-schedule*'], ['event_schedule.*']) }}">
                                <span class="pc-micon"><i class="ti ti-calendar"></i></span>
                                <span class="pc-mtext">Jadwal Event</span>
                            </a>
                        </li>
                    @endif

                    @if ($PermissionEventBatch)
                        <li class="pc-item">
                            <a href="{{ route('event_batch.list') }}"
                                class="pc-link {{ isActive(['event-batch*'], ['event_batch.*']) }}">
                                <span class="pc-micon"><i class="ti ti-stack-2"></i></span>
                                <span class="pc-mtext">Angkatan Event</span>
                            </a>
                        </li>
                    @endif
                @endif

                {{-- LES GASING (ADMIN) --}}
                @if (
                    $PermissionAssignTeacher ||
                        $PermissionUserGuruLesGasing ||
                        $PermissionStudentCourse ||
                        $PermissionLessonSchedule ||
                        $PermissionAssessments ||
                        $PermissionAttendance)
                    <li class="pc-divider mt-3 mb-1 text-uppercase text-muted small fw-bold ps-3">Les Gasing (Admin)
                    </li>
                @endif

                @if ($PermissionAssignTeacher)
                    <li class="pc-item">
                        <a href="{{ route('assign.index') }}"
                            class="pc-link {{ isActive(['assign*'], ['assign.*']) }}">
                            <span class="pc-micon"><i class="ti ti-user-check"></i></span>
                            <span class="pc-mtext">Penugasan Guru</span>
                        </a>
                    </li>
                @endif

                @if ($PermissionUserGuruLesGasing)
                    <li class="pc-item">
                        <a href="{{ route('user.list-guru-les-gasing') }}"
                            class="pc-link {{ isActive(['user/list-guru-les-gasing*'], ['user.list-guru-les-gasing']) }}">
                            <span class="pc-micon"><i class="ti ti-users"></i></span>
                            <span class="pc-mtext">Guru Les Gasing</span>
                        </a>
                    </li>
                @endif

                @if ($PermissionStudentCourse)
                    <li class="pc-item">
                        <a href="{{ route('student_course.list') }}"
                            class="pc-link {{ isActive(['student-course*'], ['student_course.*']) }}">
                            <span class="pc-micon"><i class="ti ti-users"></i></span>
                            <span class="pc-mtext">Kelas Siswa</span>
                        </a>
                    </li>
                @endif

                @if ($PermissionLessonSchedule)
                    <li class="pc-item">
                        <a href="{{ route('lesson_schedule.list') }}"
                            class="pc-link {{ isActive(['lesson-schedule*'], ['lesson_schedule.*']) }}">
                            <span class="pc-micon"><i class="ti ti-calendar-time"></i></span>
                            <span class="pc-mtext">Jadwal Pelajaran</span>
                        </a>
                    </li>
                @endif

                @if ($PermissionAssessments)
                    <li class="pc-item">
                        <a href="{{ route('assessments.index') }}"
                            class="pc-link {{ isActive(['assessments*'], ['assessments.*']) }}">
                            <span class="pc-micon"><i class="ti ti-file-check"></i></span>
                            <span class="pc-mtext">Penilaian</span>
                        </a>
                    </li>
                @endif

                @if ($PermissionAttendance)
                    <li class="pc-item">
                        <a href="{{ route('attendance.index') }}"
                            class="pc-link {{ isActive(['attendance*'], ['attendance.*']) }}">
                            <span class="pc-micon"><i class="ti ti-calendar-time"></i></span>
                            <span class="pc-mtext">Absensi</span>
                        </a>
                    </li>
                @endif

                {{-- LES GASING (GURU / UMUM) --}}
                @if ($PermissionSubject)
                    <li class="pc-divider mt-3 mb-1 text-uppercase text-muted small fw-bold ps-3">Les Gasing</li>

                    <li class="pc-item">
                        <a href="{{ route('subjects.index') }}"
                            class="pc-link {{ isActive(['subjects*', 'subject*'], ['subjects.*', 'subject.*']) }}">
                            <span class="pc-micon"><i class="ti ti-book"></i></span>
                            <span class="pc-mtext">Mata Pelajaran</span>
                        </a>
                    </li>
                @endif

                <div class="py-3"></div>

            </ul>
        </div>
    </div>
</nav>
