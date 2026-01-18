@extends('all.layouts.app-all')

@section('title', 'Daftar Kuis')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-5 text-center">Quiz Interaktif</h2>

        <div class="row g-4">
            @forelse($quizzes as $quiz)
                <div class="col-md-6">
                    <div class="card shadow-lg rounded-4 h-100 border-0 overflow-hidden position-relative">
                        <div class="card-body d-flex flex-column">
                            {{-- Judul & deskripsi --}}
                            <h5 class="card-title fw-bold">{{ $quiz->title }}</h5>
                            <p class="card-text text-muted mb-3">{{ Str::limit($quiz->description, 100) }}</p>

                            {{-- Tombol aksi --}}
                            @auth
                                @php
                                    $userResult = $quiz
                                        ->results()
                                        ->where('user_id', auth()->id())
                                        ->first();
                                @endphp

                                @if ($userResult)
                                    <a href="{{ route('quiz.show', $quiz->slug) }}" class="btn btn-outline-warning mb-3">Kerjakan
                                        Ulang</a>
                                    <div class="mb-3">
                                        <small class="text-success fw-semibold">Skor terakhir:
                                            {{ $userResult->score }}/{{ $userResult->total_questions }}</small>
                                        <div class="progress mt-1" style="height:8px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: {{ $userResult->total_questions > 0 ? round(($userResult->score / $userResult->total_questions) * 100) : 0 }}%">
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <a href="{{ route('quiz.show', $quiz->slug) }}" class="btn btn-primary mb-3">Mulai Kuis</a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-warning mb-3">Login untuk Mulai Kuis</a>
                            @endauth

                            {{-- Leaderboard --}}
                            @php
                                $lb = $leaderboards[$quiz->id];
                            @endphp

                            <h6 class="fw-semibold mt-auto">Leaderboard</h6>

                            @if ($lb['topScores']->isEmpty())
                                <p class="text-muted small">Belum ada skor.</p>
                            @else
                                <div class="table-responsive">
                                    <table
                                        class="table table-borderless table-hover align-middle mb-0 rounded-4 overflow-hidden shadow-sm leaderboard-table">
                                        <thead class="bg-light">
                                            <tr>
                                                <th style="width:40px;">#</th>
                                                <th>User</th>
                                                <th style="width:90px;">Skor</th>
                                                <th style="width:120px;">Persentase</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lb['topScores'] as $index => $result)
                                                @php
                                                    $rank = $index + 1;
                                                    $isCurrentUser =
                                                        auth()->check() && auth()->id() == $result->user_id;
                                                    $percent =
                                                        $result->total_questions > 0
                                                            ? round(($result->score / $result->total_questions) * 100)
                                                            : 0;
                                                    $badgeClass = match ($rank) {
                                                        1 => 'bg-warning text-dark',
                                                        2 => 'bg-secondary text-white',
                                                        3 => 'bg-info text-white',
                                                        default => 'bg-light text-muted',
                                                    };
                                                @endphp
                                                <tr
                                                    @if ($isCurrentUser) class="table-success fw-semibold" @endif>
                                                    <td class="align-middle text-center">
                                                        <span class="badge {{ $badgeClass }} rounded px-2 py-1"
                                                            style="font-size:0.85rem;">
                                                            {{ $rank }}
                                                        </span>
                                                    </td>
                                                    <td class="align-middle" style="font-size:0.88rem;">
                                                        {{ $result->user->name }}
                                                        @if ($isCurrentUser)
                                                            <span class="text-primary fw-bold ms-1"
                                                                title="Ini Anda">★</span>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle text-center" style="font-size:0.88rem;">
                                                        {{ $result->score }}/{{ $result->total_questions }}
                                                    </td>
                                                    <td class="align-middle">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-grow-1 me-2">
                                                                <div class="progress"
                                                                    style="height:6px; border-radius:3px;">
                                                                    <div class="progress-bar bg-primary" role="progressbar"
                                                                        style="width: {{ $percent }}%"></div>
                                                                </div>
                                                            </div>
                                                            <small class="text-muted fw-semibold"
                                                                style="font-size:0.85rem;">{{ $percent }}%</small>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            {{-- User login di luar top 10 --}}
                                            @if ($lb['userScore'] && $lb['userRank'] > 10)
                                                @php
                                                    $percent =
                                                        $lb['userScore']->total_questions > 0
                                                            ? round(
                                                                ($lb['userScore']->score /
                                                                    $lb['userScore']->total_questions) *
                                                                    100,
                                                            )
                                                            : 0;
                                                @endphp
                                                <tr class="table-success fw-semibold">
                                                    <td><span
                                                            class="badge bg-success rounded-pill px-2">{{ $lb['userRank'] }}</span>
                                                    </td>
                                                    <td>{{ $lb['userScore']->user->name }} <span
                                                            class="badge bg-success ms-1">Anda</span></td>
                                                    <td>{{ $lb['userScore']->score }}/{{ $lb['userScore']->total_questions }}
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-grow-1 me-2">
                                                                <div class="progress"
                                                                    style="height:6px; border-radius:3px;">
                                                                    <div class="progress-bar bg-success" role="progressbar"
                                                                        style="width: {{ $percent }}%"></div>
                                                                </div>
                                                            </div>
                                                            <small
                                                                class="text-muted fw-semibold">{{ $percent }}%</small>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">Belum ada kuis.</p>
            @endforelse
        </div>
    </div>

    <style>
        /* Card hover */
        .card:hover {
            transform: translateY(-5px);
            transition: 0.3s;
        }

        /* Progress bar animasi */
        .progress-bar {
            transition: width 0.6s ease;
        }

        /* Badge kecil */
        .badge {
            font-size: 0.rem;
            font-weight: 500;
        }

        /* Rounded table */
        table.table {
            border-radius: 12px;
            overflow: hidden;
            font-size: 0.9rem;
            /* font lebih proporsional */
        }

        /* Table header */
        table.table thead th {
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* Hover effect tabel leaderboard */
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
            transition: 0.3s;
        }

        /* Progress bar di tabel lebih tipis */
        .leaderboard-table .progress {
            height: 6px;
        }

        /* Teks persen lebih kecil dan proporsional */
        .leaderboard-table small {
            font-size: 0.8rem;
        }

        /* Responsive table */
        .table-responsive {
            overflow-x: auto;
        }
    </style>
@endsection
