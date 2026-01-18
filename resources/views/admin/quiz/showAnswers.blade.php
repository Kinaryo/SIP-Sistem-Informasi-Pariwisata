@extends('admin.layouts.app-admin')

@section('title', 'Jawaban Peserta')
@section('page-title', 'Jawaban Peserta: ' . ($user->name ?? 'Guest') . ' - Quiz: ' . $quiz->title)

@section('content')
    <div class="row g-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-lg rounded-4 p-4">
                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-primary">Jawaban Peserta</h5>
                    <a href="{{ route('admin.quiz.leaderboard', $quiz->id) }}" class="btn btn-secondary rounded-0">
                        <i class="bi bi-arrow-left"></i> Kembali ke Leaderboard
                    </a>
                </div>

                {{-- Info Peserta dalam card mini --}}
                @php
                    $totalQuestions = $quiz->questions->count();
                    $totalPercent = $totalQuestions > 0 ? round(($result->score / $totalQuestions) * 100) : 0;
                @endphp
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="p-3 border rounded-3 shadow-sm h-100">
                            <h6 class="text-muted mb-1">Nama Peserta</h6>
                            <p class="fw-semibold mb-0">{{ $user->name ?? 'Guest' }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border rounded-3 shadow-sm h-100">
                            <h6 class="text-muted mb-1">Waktu Selesai</h6>
                            <p class="mb-0">{{ $result->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="p-3 border rounded-3 shadow-sm h-100">
                            <h6 class="text-muted mb-1">Persentase Total</h6>
                            <div class="progress mb-2" style="height:6px; border-radius:3px;">
                                <div class="progress-bar bg-primary" role="progressbar"
                                    style="width: {{ $totalPercent }}%;"></div>
                            </div>
                            <p class="fw-semibold mb-0">{{ $totalPercent }}%</p>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="p-3 border rounded-3 shadow-sm h-100">
                            <h6 class="text-muted mb-1">Skor</h6>
                            <p class="fw-semibold mb-0">{{ $result->score }}/{{ $totalQuestions }}</p>
                        </div>
                    </div>
                </div>

                <hr>

                {{-- Jawaban Peserta --}}
                <h5 class="mb-3 fw-bold text-primary">Jawaban Peserta</h5>
                <ol class="list-group list-group-numbered">
                    @foreach($answers as $a)
                        @php
                            $percent = $a['is_correct'] ? 100 : 0;
                        @endphp
                        <li class="list-group-item mb-3">
                            <strong>{{ $a['question'] }}</strong>
                            <div class="mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span>Jawaban Peserta:</span>
                                    <span class="{{ $a['is_correct'] ? 'text-success' : 'text-danger' }}">
                                        {{ $a['answer'] ?? '-' }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span>Jawaban Benar:</span>
                                    <span class="text-success">{{ $a['correct_answer'] }}</span>
                                </div>
                                {{-- Progress bar per soal --}}
                                <div class="progress" style="height:6px; border-radius:3px;">
                                    <div class="progress-bar {{ $a['is_correct'] ? 'bg-success' : 'bg-danger' }}"
                                        role="progressbar" style="width: {{ $percent }}%;"></div>
                                </div>
                                <small class="text-muted fw-semibold mt-1">{{ $percent }}%</small>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </div>

    @push('scripts')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <style>
            /* Card shadow & hover */
            .card:hover {
                transform: translateY(-5px);
                transition: 0.3s;
            }

            /* Progress bar animasi */
            .progress-bar {
                transition: width 0.6s ease;
            }

            /* List group item spacing */
            .list-group-item {
                border-radius: 0.5rem;
                padding: 1rem;
            }

            /* Badge & small text */
            .badge {
                font-size: 0.85rem;
                font-weight: 500;
            }
        </style>
    @endpush
@endsection