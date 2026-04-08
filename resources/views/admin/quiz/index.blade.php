@extends('admin.layouts.app-admin')

@section('title', 'Quiz Management')
@section('page-title', 'Quiz Management')

@section('content')

    <style>
        .pagination {
            gap: 6px;
        }

        .page-item .page-link {
            border-radius: 8px;
            border: none;
            color: #0d6efd;
            font-weight: 500;
            padding: 8px 14px;
        }

        .page-item:hover .page-link {
            background-color: #0d6efd;
            color: #fff;
        }

        .page-item.active .page-link {
            background-color: #0d6efd;
            color: #fff;
        }

        td.aksi {
            white-space: nowrap;
        }

        td.aksi .btn {
            padding: 4px 8px;
        }
    </style>

    <div class="row g-4">
        <div class="col-md-12">

            <div class="card border-0 shadow-sm rounded-4 p-4">

                <!-- HEADER -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">

                    <div>
                        <h5 class="mb-0 fw-bold">Daftar Quiz</h5>
                        <small class="text-muted">Kelola quiz & pencarian realtime</small>
                    </div>

                    <div class="d-flex flex-wrap gap-2 align-items-center">

                        <!-- SEARCH -->
                        <div class="input-group" style="width:220px;">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Cari quiz...">
                        </div>

                        <!-- FILTER STATUS -->
                        <select id="filterStatus" class="form-select" style="width:160px;">
                            <option value="">Semua Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>

                        <!-- TAMBAH -->
                        <button class="btn btn-primary d-flex align-items-center gap-1 px-3" id="addQuizBtn">
                            <i class="bi bi-plus-lg"></i>
                            <span class="d-none d-md-inline">Tambah</span>
                        </button>

                    </div>
                </div>

                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="tableQuiz">

                        <thead class="table-light text-center">
                            <tr>
                                <th style="width:60px;">No</th>
                                <th>Quiz</th>
                                <th>Status</th>
                                <th>Jumlah Pertanyaan</th>
                                <th style="width:220px;">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($quizzes as $quiz)
                                <tr data-status="{{ $quiz->is_active }}">

                                    <!-- NO -->
                                    <td class="text-center">
                                        {{ $loop->iteration + $quizzes->firstItem() - 1 }}
                                    </td>

                                    <!-- TITLE -->
                                    <td class="nama">
                                        <strong>{{ $quiz->title }}</strong><br>
                                        <small class="text-muted">
                                            {!! Str::limit($quiz->description, 60) !!}
                                        </small>
                                    </td>

                                    <!-- STATUS -->
                                    <td class="text-center">
                                        <span class="badge px-3 py-2 bg-{{ $quiz->is_active ? 'success' : 'secondary' }}">
                                            {{ $quiz->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>

                                    <!-- JUMLAH -->
                                    <td class="text-center">
                                        {{ $quiz->questions_count }}
                                    </td>

                                    <!-- AKSI -->
                                    <td class="text-center aksi">
                                        <div class="d-flex flex-wrap justify-content-center gap-1">

                                            <!-- VIEW -->
                                            <a href="{{ route('admin.quiz.show', $quiz->id) }}" class="btn btn-sm btn-info"
                                                data-bs-toggle="tooltip" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <!-- EDIT -->
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit"
                                                onclick="editQuiz({{ $quiz->id }}, '{{ addslashes($quiz->title) }}', '{{ addslashes($quiz->description ?? '') }}', {{ $quiz->is_active ? 1 : 0 }})">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <!-- TOGGLE ACTIVE -->
                                            <button class="btn btn-sm {{ $quiz->is_active ? 'btn-success' : 'btn-secondary' }}"
                                                data-bs-toggle="tooltip"
                                                title="{{ $quiz->is_active ? 'Nonaktifkan Quiz' : 'Aktifkan Quiz' }}" onclick="confirmToggle(
                                                                            '{{ route('admin.quiz.toggleActive', $quiz->id) }}',
                                                                            {{ $quiz->is_active ? 'true' : 'false' }}
                                                                        )">
                                                <i class="bi {{ $quiz->is_active ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                            </button>
                                            <!-- DELETE -->
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Hapus"
                                                onclick="deleteQuiz({{ $quiz->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada quiz</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                <!-- PAGINATION -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $quizzes->links() }}
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div class="modal fade" id="quizModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title" id="quizModalTitle">Tambah Quiz</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="quizForm">
                    @csrf
                    <div class="modal-body">

                        <input type="hidden" id="quizId">

                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" id="quizTitle" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea id="quizDescription" class="form-control"></textarea>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" id="quizIsActive" class="form-check-input">
                            <label class="form-check-label">Active</label>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // ================= TOOLTIP =================
            function initTooltip() {
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                    new bootstrap.Tooltip(el)
                });
            }
            initTooltip();

            // ================= SEARCH + FILTER =================
            document.querySelectorAll('#searchInput, #filterStatus')
                .forEach(el => el.addEventListener('input', filterTable));

            function filterTable() {
                let search = document.getElementById('searchInput').value.toLowerCase();
                let status = document.getElementById('filterStatus').value;

                document.querySelectorAll('#tableQuiz tbody tr').forEach(row => {
                    let nama = row.querySelector('.nama').innerText.toLowerCase();
                    let rowStatus = row.dataset.status;

                    let matchSearch = nama.includes(search);
                    let matchStatus = status === '' || status === rowStatus;

                    row.style.display = (matchSearch && matchStatus) ? '' : 'none';
                });
            }

            // ================= TAMBAH =================
            document.getElementById('addQuizBtn').addEventListener('click', function () {

                document.getElementById('quizModalTitle').innerText = 'Tambah Quiz';
                document.getElementById('quizId').value = '';
                document.getElementById('quizTitle').value = '';
                document.getElementById('quizDescription').value = '';
                document.getElementById('quizIsActive').checked = true;

                new bootstrap.Modal(document.getElementById('quizModal')).show();
            });

            // ================= SUBMIT FORM =================
            document.getElementById('quizForm').addEventListener('submit', function (e) {
                e.preventDefault();

                let id = document.getElementById('quizId').value;
                let title = document.getElementById('quizTitle').value;
                let description = document.getElementById('quizDescription').value;
                let is_active = document.getElementById('quizIsActive').checked ? 1 : 0;

                let url = id ? `/admin/quiz/${id}` : `/admin/quiz`;
                let method = id ? 'PUT' : 'POST';

                Swal.fire({
                    title: 'Menyimpan Data...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        title: title,
                        description: description,
                        is_active: is_active
                    })
                })
                    .then(res => res.json())
                    .then(res => {

                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.message || 'Data berhasil disimpan',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: res.message || 'Terjadi kesalahan'
                            });
                        }

                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Server!',
                            text: 'Tidak dapat terhubung ke server'
                        });
                    });
            });

        });

        // ================= EDIT (GLOBAL) =================
        window.editQuiz = function (id, title, description, isActive) {

            document.getElementById('quizModalTitle').innerText = 'Edit Quiz';
            document.getElementById('quizId').value = id;
            document.getElementById('quizTitle').value = title;
            document.getElementById('quizDescription').value = description;
            document.getElementById('quizIsActive').checked = isActive ? true : false;

            new bootstrap.Modal(document.getElementById('quizModal')).show();
        }

        // ================= DELETE =================
        window.deleteQuiz = function (id) {

            Swal.fire({
                title: 'Yakin hapus quiz?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Sedang memproses',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    fetch(`/admin/quiz/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        }
                    })
                        .then(res => res.json())
                        .then(res => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.message || 'Data berhasil dihapus',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        })
                        .catch(() => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Tidak dapat menghapus data'
                            });
                        });

                }
            });
        }

        // ================= TOGGLE =================
        window.confirmToggle = function (url, currentStatus) {

            let text = currentStatus
                ? 'Quiz akan dinonaktifkan'
                : 'Quiz akan diaktifkan';

            Swal.fire({
                title: 'Konfirmasi',
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, lanjutkan',
                cancelButtonText: 'Batal'
            }).then(result => {

                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        }
                    })
                        .then(res => res.json())
                        .then(res => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.message || 'Status berhasil diubah',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        })
                        .catch(() => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Tidak dapat mengubah status'
                            });
                        });

                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection