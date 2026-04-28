<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('backend.students_report_pdf.page_title') }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h2 {
            color: #00aaff;
            text-align: center;
            margin-bottom: 10px;
        }

        p.total {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            word-wrap: break-word;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        .status-active { background-color: #d4edda; color: #155724; font-weight: bold; }
        .status-inactive { background-color: #f8d7da; color: #721c24; font-weight: bold; }
    </style>
</head>
<body>

    <h2>{{ __('backend.students_report_pdf.heading') }}</h2>
    <p class="total">{{ __('backend.students_report_pdf.total_students', ['count' => $students->count()]) }}</p>

    <table>
        <thead>
            <tr>
                <th>{{ __('backend.students_report_pdf.table_number') }}</th>
                <th>{{ __('backend.students_report_pdf.name') }}</th>
                <th>{{ __('backend.students_report_pdf.email') }}</th>
                <th>{{ __('backend.students_report_pdf.major') }}</th>
                <th>{{ __('backend.students_report_pdf.status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->student->major ?? __('backend.students_report_pdf.empty_value') }}</td>
                    <td class="
                        @if(strtolower($student->status) == 'active') status-active
                        @elseif(strtolower($student->status) == 'inactive') status-inactive
                        @else '' @endif">
                        {{ $student->status }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>