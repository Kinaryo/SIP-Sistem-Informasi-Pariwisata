@extends('admin.layouts.app-admin')

@section('title', 'Quiz Management')
@section('page-title', 'Quiz Management')

@section('content')
<div class="row g-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5>Daftar Quiz</h5>
                <button class="btn btn-primary rounded" id="addQuizBtn">Tambah Quiz</button>
            </div>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Active</th>
                        <th>Jumlah Pertanyaan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quizzes as $index => $quiz)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $quiz->title }}</td>
                            <td>{{ $quiz->is_active ? 'Yes' : 'No' }}</td>
                            <td>{{ $quiz->questions_count }}</td>
                            <td>
                                <a href="{{ route('admin.quiz.show', $quiz->id) }}" class="btn btn-sm btn-info">Show</a>
                                <button class="btn btn-sm btn-warning" onclick="editQuiz({{ $quiz->id }}, '{{ addslashes($quiz->title) }}', '{{ addslashes($quiz->description ?? '') }}', {{ $quiz->is_active ? 1 : 0 }})">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteQuiz({{ $quiz->id }})">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada quiz.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Tambah/Edit Quiz --}}
<div class="modal fade" id="quizModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title" id="quizModalTitle">Tambah Quiz</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    <div class="mb-3 form-check">
                        <input type="checkbox" id="quizIsActive" class="form-check-input">
                        <label class="form-check-label">Active</label>
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
// Tambah Quiz
document.getElementById('addQuizBtn').addEventListener('click', () => {
    document.getElementById('quizModalTitle').innerText = 'Tambah Quiz';
    document.getElementById('quizForm').reset();
    document.getElementById('quizId').value = '';
    new bootstrap.Modal(document.getElementById('quizModal')).show();
});

// Edit Quiz
function editQuiz(id, title, description, isActive) {
    document.getElementById('quizModalTitle').innerText = 'Edit Quiz';
    document.getElementById('quizId').value = id;
    document.getElementById('quizTitle').value = title;
    document.getElementById('quizDescription').value = description;
    document.getElementById('quizIsActive').checked = isActive ? true : false;

    new bootstrap.Modal(document.getElementById('quizModal')).show();
}

// Submit form (Tambah/Update)
document.getElementById('quizForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('quizId').value;
    const url = id ? `/admin/quiz/${id}` : "{{ route('admin.quiz.store') }}";
    const method = id ? 'PUT' : 'POST';

    const data = {
        title: document.getElementById('quizTitle').value,
        description: document.getElementById('quizDescription').value,
        is_active: document.getElementById('quizIsActive').checked ? 1 : 0
    };

    Swal.fire({
        title: 'Mohon Tunggu',
        text: 'Sedang menyimpan data...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        body: JSON.stringify(data)
    })
    .then(async res => {
        const json = await res.json();
        if (res.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: json.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => location.reload());
        } else {
            let errorMsg = json.message;
            if (json.errors) {
                errorMsg = Object.values(json.errors).flat().join('<br>');
            }
            throw new Error(errorMsg);
        }
    })
    .catch(err => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            html: err.message || 'Terjadi kesalahan sistem'
        });
    });
});

// Delete Quiz
function deleteQuiz(id) {
    Swal.fire({
        title: 'Hapus Quiz?',
        text: "Data quiz dan pertanyaannya akan terhapus!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/quiz/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            })
            .then(async res => {
                const json = await res.json();
                if (res.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: json.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    throw new Error(json.message || 'Gagal menghapus quiz');
                }
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: err.message || 'Terjadi kesalahan sistem'
                });
            });
        }
    });
}
</script>
@endpush
