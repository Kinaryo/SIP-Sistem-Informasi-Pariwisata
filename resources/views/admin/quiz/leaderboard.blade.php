@extends('admin.layouts.app-admin')

@section('title', 'Leaderboard Quiz')
@section('page-title', 'Leaderboard: ' . $quiz->title)

@section('content')
<div class="row g-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5>Leaderboard Quiz</h5>
                <a href="{{ route('admin.quiz.show', $quiz->id) }}" class="btn btn-secondary rounded">
                    <i class="bi bi-arrow-left"></i> Kembali ke Quiz
                </a>
            </div>

            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Peserta</th>
                        <th>Skor</th>
                        <th>Total Soal</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $index => $result)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $result->user->name ?? 'Guest' }}</td>
                            <td>{{ $result->score }}</td>
                            <td>{{ $result->total_questions }}</td>
                            <td>{{ $result->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.quiz.result.answers', $result->id) }}" class="btn btn-sm btn-info" title="Lihat Jawaban">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada hasil untuk quiz ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Pastikan sudah include Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endpush
