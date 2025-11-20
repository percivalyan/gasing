@extends('panel.layouts.app')

@section('content')
@php
  $roleName = strtolower(optional(auth()->user()->role)->name ?? '');
  $isAdmin  = $roleName === 'administrator';
@endphp

<style>
  .welcome-wrap{
    position:relative;overflow:hidden;border-radius:1rem;
    background:#0b1020 url('{{ asset('assets/logo_gasing.jpg') }}') center/200px no-repeat;
    min-height: 52vh;
  }
  .welcome-overlay{
    position:absolute;inset:0;background:radial-gradient(600px 200px at 10% 10%, rgba(255,255,255,.08), transparent),
                        radial-gradient(800px 400px at 90% 20%, rgba(255,255,255,.06), transparent),
                        linear-gradient(135deg,#0b1020 0%, #12213a 100%);
    transition:all .35s ease;
  }
  .welcome-wrap:hover .welcome-overlay{
    filter:brightness(1.15);
    backdrop-filter: blur(1px);
  }
  .welcome-content{
    position:relative;z-index:2;color:#fff;padding:3rem;
  }
  .welcome-title{
    font-weight:800;letter-spacing:.5px;
  }
  .welcome-badge{
    background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);
    color:#fff;backdrop-filter:blur(2px);
  }
</style>

<div class="pc-container">
  <div class="pc-content">
    @include('panel.layouts.breadcrumb')

    @if($isAdmin)
      {{-- ======== ADMIN DASHBOARD PENUH ======== --}}
      <div class="row g-3">
        {{-- Kartu Statistik Utama --}}
        @php
          $cards = [
            ['title' => 'Users', 'key' => 'users', 'route' => url('user')],
            ['title' => 'Roles', 'key' => 'roles', 'route' => url('role')],
            ['title' => 'Categories', 'key' => 'categories', 'route' => url('category')],
            ['title' => 'Articles', 'key' => 'articles', 'route' => url('article')],
            ['title' => 'Documents', 'key' => 'documents', 'route' => url('document')],
            ['title' => 'Ref Numbers', 'key' => 'ref_numbers', 'route' => route('referencenumber.list')],
            ['title' => 'Letter Types', 'key' => 'letter_types', 'route' => url('lettertype')],
            ['title' => 'Galleries', 'key' => 'galleries', 'route' => route('gallery.list')],
            ['title' => 'About', 'key' => 'about', 'route' => route('about.list')],
            ['title' => 'Org. Structures', 'key' => 'org_structs', 'route' => route('organization.structure.list')],
            ['title' => 'Org. Members', 'key' => 'org_members', 'route' => route('organization.member.list')],
            ['title' => 'Teacher Events', 'key' => 'teacher_events', 'route' => route('teacher_event.list')],
            ['title' => 'Student Events', 'key' => 'student_events', 'route' => route('student_event.list')],
            ['title' => 'Student Courses', 'key' => 'student_courses', 'route' => route('student_course.list')],
            ['title' => 'Lesson Schedules', 'key' => 'lesson_scheds', 'route' => route('lesson_schedule.list')],
            ['title' => 'Assigns', 'key' => 'assigns', 'route' => route('assign.index')],
          ];
        @endphp

        @foreach ($cards as $c)
          <div class="col-12 col-sm-6 col-md-4 col-xl-3">
            <a href="{{ $c['route'] }}" class="text-decoration-none">
              <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">{{ $c['title'] }}</h6>
                    <span class="badge bg-primary">Total</span>
                  </div>
                  <div class="display-6 fw-bold">{{ $counts[$c['key']] ?? 0 }}</div>
                </div>
              </div>
            </a>
          </div>
        @endforeach
      </div>

      <div class="row mt-4 g-3">
        {{-- Recent Articles --}}
        <div class="col-12 col-xl-6">
          <div class="card shadow-sm h-100">
            <div class="card-header"><strong>Artikel Terbaru</strong></div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-striped mb-0">
                  <thead>
                    <tr>
                      <th>Judul</th>
                      <th>Kategori</th>
                      <th>Dibuat</th>
                      <th class="text-end">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($recentArticles as $a)
                      <tr>
                        <td>{{ $a->title ?? '-' }}</td>
                        <td>{{ optional($a->category)->name ?? '-' }}</td>
                        <td>{{ optional($a->created_at)->format('d M Y H:i') }}</td>
                        <td class="text-end">
                          <a href="{{ url('article/edit/'.($a->id ?? '')) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                      </tr>
                    @empty
                      <tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        {{-- Recent Documents --}}
        <div class="col-12 col-xl-6">
          <div class="card shadow-sm h-100">
            <div class="card-header"><strong>Dokumen Terbaru</strong></div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-striped mb-0">
                  <thead>
                    <tr>
                      <th>Nama</th>
                      <th>Tipe</th>
                      <th>Dibuat</th>
                      <th class="text-end">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($recentDocuments as $d)
                      <tr>
                        <td>{{ $d->name ?? '-' }}</td>
                        <td>{{ $d->type ?? '-' }}</td>
                        <td>{{ optional($d->created_at)->format('d M Y H:i') }}</td>
                        <td class="text-end">
                          <a href="{{ url('document/edit/'.($d->id ?? '')) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                      </tr>
                    @empty
                      <tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        {{-- Recent Users --}}
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
              <strong>User Terbaru</strong>
              @if(($can['User'] ?? false))
                <a href="{{ url('user/add') }}" class="btn btn-sm btn-primary">Tambah User</a>
              @endif
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead>
                    <tr>
                      <th>Nama</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th>Dibuat</th>
                      <th class="text-end">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($recentUsers as $u)
                      <tr>
                        <td>{{ $u->name ?? '-' }}</td>
                        <td>{{ $u->email ?? '-' }}</td>
                        <td>{{ optional($u->role)->name ?? '-' }}</td>
                        <td>{{ optional($u->created_at)->format('d M Y H:i') }}</td>
                        <td class="text-end">
                          <a href="{{ url('user/edit/'.($u->id ?? '')) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                      </tr>
                    @empty
                      <tr><td colspan="5" class="text-center text-muted">Belum ada data</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-4 g-3">
        {{-- Event Mendatang --}}
        <div class="col-12 col-xl-6">
          <div class="card shadow-sm h-100">
            <div class="card-header"><strong>Event Guru (Mendatang)</strong></div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-sm mb-0">
                  <thead><tr><th>Nama</th><th>Tanggal</th><th class="text-end">Aksi</th></tr></thead>
                  <tbody>
                    @forelse($upcomingTeacherEvents as $e)
                      <tr>
                        <td>{{ $e->name ?? '-' }}</td>
                        <td>{{ optional($e->event_date)->format('d M Y') }}</td>
                        <td class="text-end">
                          <a class="btn btn-sm btn-outline-primary" href="{{ route('teacher_event.edit', $e->id ?? '') }}">Kelola</a>
                        </td>
                      </tr>
                    @empty
                      <tr><td colspan="3" class="text-center text-muted">Tidak ada event</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-xl-6">
          <div class="card shadow-sm h-100">
            <div class="card-header"><strong>Event Siswa (Mendatang)</strong></div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-sm mb-0">
                  <thead><tr><th>Nama</th><th>Tanggal</th><th class="text-end">Aksi</th></tr></thead>
                  <tbody>
                    @forelse($upcomingStudentEvents as $e)
                      <tr>
                        <td>{{ $e->name ?? '-' }}</td>
                        <td>{{ optional($e->event_date)->format('d M Y') }}</td>
                        <td class="text-end">
                          <a class="btn btn-sm btn-outline-primary" href="{{ route('student_event.edit', $e->id ?? '') }}">Kelola</a>
                        </td>
                      </tr>
                    @empty
                      <tr><td colspan="3" class="text-center text-muted">Tidak ada event</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-4 g-3">
        {{-- Jadwal Hari Ini --}}
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
              <strong>Jadwal Pelajaran Hari Ini</strong>
              @if(($can['Lesson'] ?? false))
                <a href="{{ route('lesson_schedule.add') }}" class="btn btn-sm btn-primary">Tambah Jadwal</a>
              @endif
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead>
                    <tr>
                      <th>Guru</th>
                      <th>Kelas/Topik</th>
                      <th>Jam</th>
                      <th>Lokasi</th>
                      <th class="text-end">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($todaySchedules as $s)
                      <tr>
                        <td>{{ optional($s->teacher)->name ?? '-' }}</td>
                        <td>{{ $s->topic ?? $s->class_name ?? '-' }}</td>
                        <td>
                          @if(isset($s->start_time) || isset($s->end_time))
                            {{ isset($s->start_time) ? \Carbon\Carbon::parse($s->start_time)->format('H:i') : '?' }} -
                            {{ isset($s->end_time) ? \Carbon\Carbon::parse($s->end_time)->format('H:i') : '?' }}
                          @else
                            -
                          @endif
                        </td>
                        <td>{{ $s->location ?? '-' }}</td>
                        <td class="text-end">
                          <a href="{{ route('lesson_schedule.edit', $s->id ?? '') }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                      </tr>
                    @empty
                      <tr><td colspan="5" class="text-center text-muted">Tidak ada jadwal untuk hari ini</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      {{-- ======== /ADMIN DASHBOARD PENUH ======== --}}

    @else
      {{-- ======== NON-ADMIN: HANYA SELAMAT DATANG ======== --}}
      <div class="row">
        <div class="col-12">
          <div class="welcome-wrap shadow-sm">
            <div class="welcome-overlay"></div>
            <div class="welcome-content">
              <span class="badge welcome-badge mb-3 px-3 py-2">Yayasan Gasing</span>
              <h2 class="welcome-title mb-2 text-white">Selamat Datang, {{ auth()->user()->name ?? 'Pengguna' }} 👋</h2>
              <p class="mb-0 text-white-50">
                Anda masuk sebagai <strong class="text-white">{{ optional(auth()->user()->role)->name ?? 'User' }}</strong>.
                Hubungi Admin jika memiliki masalah.
              </p>
            </div>
          </div>
        </div>
      </div>
      {{-- ======== /NON-ADMIN ======== --}}
    @endif

  </div>
</div>
@endsection
