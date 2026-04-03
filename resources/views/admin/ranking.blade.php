@extends('layouts.admin')

@section('content')

<div class="container-fluid mt-3">

    <h4 class="mb-4">@section('page-title')
🏆 Top Applicants Ranking
@endsection
    </h4>

    <div class="card shadow-sm border-0 p-3">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>Rank</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Position</th>
                        <th>Total Score</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($applications as $app)

                    <tr>

                        <td>
                            @if($app->rank == 1)
                                🥇
                            @elseif($app->rank == 2)
                                🥈
                            @elseif($app->rank == 3)
                                🥉
                            @else
                                #{{ $app->rank }}
                            @endif
                        </td>

                        <td>{{ $app->name }}</td>

                        <td class="text-muted small">{{ $app->email }}</td>

                        <td>{{ $app->position_applied ?? 'N/A' }}</td>

                        <td>
                            <strong>{{ $app->total_score }}</strong>
                        </td>

                        <td>
                           @if(empty($app->total_score))
                                <span class="text-secondary">● NOT EVALUATED</span>
                            @elseif($app->final_result == 'qualified')
                                <span class="text-success">● Qualified</span>
                            @else
                                <span class="text-danger">● Not Qualified</span>
                            @endif
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection