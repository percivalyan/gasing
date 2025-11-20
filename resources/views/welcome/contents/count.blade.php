<section id="counts" class="section counts light-background">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4">

            {{-- Siswa Les Gasing (student_courses) --}}
            <div class="col-lg-4 col-md-6">
                <div class="stats-item text-center w-100 h-100">
                    <span data-purecounter-start="0" data-purecounter-end="{{ $countSiswaLesGasing ?? 0 }}"
                        data-purecounter-duration="1" class="purecounter">
                    </span>
                    <p>Siswa Les Gasing</p>
                </div>
            </div><!-- End Stats Item -->

            {{-- Siswa Gasing (student_events) --}}
            <div class="col-lg-4 col-md-6">
                <div class="stats-item text-center w-100 h-100">
                    <span data-purecounter-start="0" data-purecounter-end="{{ $countSiswaGasingEvent ?? 0 }}"
                        data-purecounter-duration="1" class="purecounter">
                    </span>
                    <p>Siswa Gasing</p>
                </div>
            </div><!-- End Stats Item -->

            {{-- Guru Gasing (teacher_events) --}}
            <div class="col-lg-4 col-md-6">
                <div class="stats-item text-center w-100 h-100">
                    <span data-purecounter-start="0" data-purecounter-end="{{ $countGuruGasing ?? 0 }}"
                        data-purecounter-duration="1" class="purecounter">
                    </span>
                    <p>Guru Gasing</p>
                </div>
            </div><!-- End Stats Item -->

        </div>
    </div>
</section>
