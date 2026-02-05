<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Application Status</title>
</head>
<body>
    <p>Dear Applicant,</p>

    <p>Thank you for applying for {{ $position }}. After careful evaluation of your qualifications based on the CSC-Approved QS, we regret to inform you that your application did not meet some of the requirements.</p>

    <p><strong>Details of evaluation:</strong></p>
    <ul>
        @foreach ($remarks as $remark)
            <li>{{ $remark }}</li>
        @endforeach
    </ul>

    <p>You may review your application and qualifications for future opportunities.</p>

    <p>Thank you for your interest and effort.</p>

    <p>Best regards,<br>
    HR Department</p>
</body>
</html>
