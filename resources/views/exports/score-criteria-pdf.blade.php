<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Score Criteria Assessment Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4472C4;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #4472C4;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #4472C4;
            color: white;
            padding: 12px 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        td {
            padding: 10px 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        .checkbox {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }
        .points {
            text-align: center;
            font-weight: bold;
        }
        .type-achievement {
            background-color: #d4edda;
            color: #155724;
        }
        .type-penalty {
            background-color: #f8d7da;
            color: #721c24;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
        .instructions {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #4472C4;
        }
        .instructions h3 {
            margin-top: 0;
            color: #4472C4;
        }
        .instructions ul {
            margin: 10px 0;
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Gym Score Assessment Form</h1>
        <p>Branch: _________________________________</p>
        <p>Date: _________________________________</p>
        <p>Assessed By: _________________________________</p>
    </div>

    <div class="instructions">
        <h3>Instructions:</h3>
        <ul>
            <li>Review each criterion and mark ✓ if your gym meets the requirement</li>
            <li>Leave blank if the criterion is not applicable or not met</li>
            <li>Add notes in the Notes column for any clarifications</li>
            <li>Submit this form along with supporting documents for review</li>
        </ul>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25%;">Criteria Name</th>
                <th style="width: 35%;">Description</th>
                <th style="width: 8%;">Points</th>
                <th style="width: 10%;">Type</th>
                <th style="width: 8%;">Achieved</th>
                <th style="width: 14%;">Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($criteria as $criterion)
            <tr>
                <td>
                    <strong>{{ $criterion->getTranslation('name', 'en') }}</strong><br>
                    <small>{{ $criterion->getTranslation('name', 'ar') }}</small>
                </td>
                <td>
                    {{ $criterion->getTranslation('description', 'en') }}<br>
                    <small>{{ $criterion->getTranslation('description', 'ar') }}</small>
                </td>
                <td class="points">
                    {{ $criterion->points > 0 ? '+' . $criterion->points : $criterion->points }}
                </td>
                <td class="type-{{ $criterion->is_negative ? 'penalty' : 'achievement' }}">
                    {{ $criterion->is_negative ? 'Penalty' : 'Achievement' }}
                </td>
                <td class="checkbox">☐</td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px;">
        <h3>Summary</h3>
        <table style="width: 50%;">
            <tr>
                <td><strong>Total Positive Points:</strong></td>
                <td>_________________</td>
            </tr>
            <tr>
                <td><strong>Total Negative Points:</strong></td>
                <td>_________________</td>
            </tr>
            <tr>
                <td><strong>Final Score:</strong></td>
                <td>_________________</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p><strong>Important Notes:</strong></p>
        <ul>
            <li>This form must be completed accurately and honestly</li>
            <li>Supporting documents (photos, certificates, etc.) should be attached</li>
            <li>False information may result in score penalties</li>
            <li>Submit this form to request a physical review of your gym</li>
        </ul>
        
        <p style="margin-top: 20px;">
            <strong>Contact Information:</strong><br>
            Email: support@gymnetwork.com<br>
            Phone: +1234567890<br>
            Address: 123 Fitness Street, City, Country
        </p>
    </div>
</body>
</html>
