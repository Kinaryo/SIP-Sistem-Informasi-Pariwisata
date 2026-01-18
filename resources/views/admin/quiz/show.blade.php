@extends('admin.layouts.app-admin')

@section('title', 'Detail Quiz')
@section('page-title', 'Detail Quiz: ' . $quiz->title)

@section('content')
    <div class="row g-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-lg rounded-4 p-4">
                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold text-primary">Detail Quiz</h4>
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.quiz.index') }}" class="btn btn-outline-secondary rounded me-2">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('admin.quiz.leaderboard', $quiz->id) }}"
                            class="btn btn-outline-info rounded me-2">
                            <i class="bi bi-trophy"></i> Leaderboard
                        </a>
                        <button class="btn btn-primary rounded me-2" id="addQuestionBtn">
                            <i class="bi bi-plus-lg"></i> Tambah Soal
                        </button>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="p-3 border rounded-3 shadow-sm h-100">
                            <h6 class="text-muted mb-1">Judul Quiz</h6>
                            <p class="fw-semibold mb-0">{{ $quiz->title }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded-3 shadow-sm h-100">
                            <h6 class="text-muted mb-1">Slug</h6>
                            <p class="mb-0">{{ $quiz->slug }}</p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div
                            class="p-3 border rounded-3 shadow-sm h-100 d-flex flex-column justify-content-center align-items-start">
                            <h6 class="text-muted mb-1">Status</h6>
                            <span class="badge {{ $quiz->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $quiz->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                     <div class="col-md-2">
                        <div class="p-3 border rounded-3 shadow-sm h-100">
                            <h6 class="text-muted mb-1">Jumlah Pertanyaan</h6>
                            <p class="mb-0">{{ $quiz->questions->count() }}</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="p-3 border rounded-3 shadow-sm h-100">
                            <h6 class="text-muted mb-1">Deskripsi</h6>
                            <p class="mb-0">{{ $quiz->description ?? '-' }}</p>
                        </div>
                    </div>
                </div>


                <hr>

                {{-- Daftar Pertanyaan --}}
                <h5 class="mb-3 text-primary fw-bold">Daftar Pertanyaan</h5>
                <div id="questionsContainer">
                    @forelse($quiz->questions as $index => $question)
                        <div class="card mb-3 shadow-sm rounded-3" id="questionCard-{{ $question->id }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Pertanyaan {{ $index + 1 }}:</h6>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-warning"
                                            onclick="editQuestion({{ $question->id }}, '{{ addslashes($question->question) }}', {{ json_encode($question->options) }}, '{{ $question->correct_answer }}')">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteQuestion({{ $question->id }})">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                                <p class="mb-2">{{ $question->question }}</p>

                                @if(is_array($question->options) && count($question->options))
                                    <ul class="list-group list-group-flush">
                                        @foreach($question->options as $option)
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center
                                                                        {{ $question->correct_answer === $option ? 'bg-success text-white' : '' }}">
                                                {{ $option }}
                                                @if($question->correct_answer === $option)
                                                    <span class="badge bg-light text-dark">Benar</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">Belum ada pertanyaan untuk quiz ini.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- Modal Tambah/Edit Question --}}
    <div class="modal fade" id="questionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title" id="questionModalTitle">Tambah Soal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="questionForm">
                    @csrf
                    <input type="hidden" id="questionId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pertanyaan</label>
                            <textarea id="questionText" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pilihan Jawaban</label>
                            <div id="optionsContainer">
                                <input type="text" class="form-control mb-2 optionInput" placeholder="Jawaban 1" required>
                                <input type="text" class="form-control mb-2 optionInput" placeholder="Jawaban 2" required>
                            </div>
                            <button type="button" class="btn btn-sm btn-secondary mb-2" onclick="addOption()">Tambah
                                Pilihan</button>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jawaban Benar</label>
                            <select id="correctAnswer" class="form-control" required></select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary rounded">Simpan</button>
                        <button type="button" class="btn btn-secondary rounded" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const quizId = {{ $quiz->id }};

        // Tambah Pilihan Jawaban
        function addOption(value = '') {
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control mb-2 optionInput';
            input.placeholder = 'Jawaban';
            input.value = value;
            input.required = true;
            input.addEventListener('input', updateCorrectAnswerOptions); // Sinkronisasi dropdown
            document.getElementById('optionsContainer').appendChild(input);
            updateCorrectAnswerOptions();
        }

        // Update dropdown jawaban benar
        function generateCorrectAnswerOptions(options, selected = '') {
            const select = document.getElementById('correctAnswer');
            select.innerHTML = '';
            options.forEach(opt => {
                const option = document.createElement('option');
                option.value = opt;
                option.textContent = opt;
                if (opt === selected) option.selected = true;
                select.appendChild(option);
            });
        }

        // Sinkronisasi dropdown jawaban benar saat input berubah
        function updateCorrectAnswerOptions() {
            const options = Array.from(document.querySelectorAll('.optionInput')).map(i => i.value);
            const current = document.getElementById('correctAnswer').value;
            generateCorrectAnswerOptions(options, current);
        }

        // Modal tambah soal
        document.getElementById('addQuestionBtn').addEventListener('click', () => {
            document.getElementById('questionModalTitle').innerText = 'Tambah Soal';
            document.getElementById('questionForm').reset();
            document.getElementById('questionId').value = '';
            document.getElementById('optionsContainer').innerHTML = '';
            addOption();
            addOption();
            updateCorrectAnswerOptions();
            new bootstrap.Modal(document.getElementById('questionModal')).show();
        });

        // Modal edit soal
        function editQuestion(id, text, options, correct) {
            document.getElementById('questionModalTitle').innerText = 'Edit Soal';
            document.getElementById('questionId').value = id;
            document.getElementById('questionText').value = text;
            document.getElementById('optionsContainer').innerHTML = '';
            options.forEach(opt => addOption(opt));
            generateCorrectAnswerOptions(options, correct);
            new bootstrap.Modal(document.getElementById('questionModal')).show();
        }

        // Submit tambah/update soal
        document.getElementById('questionForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const id = document.getElementById('questionId').value;
            const url = id ? `/admin/quiz/${quizId}/question/${id}` : `/admin/quiz/${quizId}/question`;
            const method = id ? 'PUT' : 'POST';

            const options = Array.from(document.querySelectorAll('.optionInput')).map(i => i.value);

            const data = {
                question: document.getElementById('questionText').value,
                options: options,
                correct_answer: document.getElementById('correctAnswer').value
            };

            Swal.fire({ title: 'Mohon Tunggu', text: 'Sedang menyimpan data...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify(data)
            }).then(async res => {
                const json = await res.json();
                if (res.ok) {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: json.message, timer: 1500, showConfirmButton: false }).then(() => location.reload());
                } else {
                    throw new Error(json.message || 'Terjadi kesalahan');
                }
            }).catch(err => {
                Swal.fire({ icon: 'error', title: 'Gagal', text: err.message || 'Terjadi kesalahan' });
            });
        });

        // Hapus soal
        function deleteQuestion(id) {
            Swal.fire({
                title: 'Hapus Soal?',
                text: 'Data soal akan terhapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`/admin/quiz/${quizId}/question/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
                    }).then(async res => {
                        const json = await res.json();
                        if (res.ok) {
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: json.message, timer: 1500, showConfirmButton: false }).then(() => location.reload());
                        } else {
                            throw new Error(json.message || 'Gagal menghapus soal');
                        }
                    }).catch(err => {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: err.message || 'Terjadi kesalahan' });
                    });
                }
            });
        }
    </script>
@endpush