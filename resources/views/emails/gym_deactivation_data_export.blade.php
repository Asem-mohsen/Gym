<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Deactivation - Data Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .footer {
            margin-top: 20px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            font-size: 14px;
            color: #6c757d;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Gym Deactivation Notice</h1>
        <p><strong>Gym:</strong> {{ $gym->gym_name ?? 'N/A' }}</p>
        <p><strong>Date:</strong> {{ now()->format('F j, Y') }}</p>
    </div>

    <div class="content">
        <p>Dear Gym Owner,</p>

        <p>We regret to inform you that your gym account has been deactivated. As part of our deactivation process, we have prepared a comprehensive data export containing all your gym's information.</p>

        <div class="warning">
            <h3>⚠️ Important Information</h3>
            <ul>
                <li>Your gym data will be permanently deleted after 30 days from the deactivation date.</li>
                <li>All user accounts associated with your gym will be deactivated within 2 days.</li>
                <li>Please download and save the attached Excel file for your records.</li>
            </ul>
        </div>

        <div class="info">
            <h3>📊 Data Export Contents</h3>
            <p>The attached Excel file contains the following data in separate sheets:</p>
            <ul>
                <li><strong>Gym Information:</strong> Basic gym details and settings</li>
                <li><strong>Users:</strong> All member and staff accounts</li>
                <li><strong>Branches:</strong> All branch locations and details</li>
                <li><strong>Services:</strong> All services offered by your gym</li>
                <li><strong>Classes:</strong> All class schedules and information</li>
                <li><strong>Memberships:</strong> All membership plans and features</li>
                <li><strong>Offers:</strong> All promotional offers and deals</li>
                <li><strong>Payments:</strong> All payment transactions</li>
                <li><strong>Invitations:</strong> All sent and used invitations</li>
                <li><strong>Blog Posts:</strong> All blog content and comments</li>
                <li><strong>Bookings:</strong> All class and service bookings</li>
                <li><strong>Contacts:</strong> All contact form submissions</li>
                <li><strong>And more...</strong> Complete data export of all gym activities</li>
            </ul>
        </div>

        <p>If you have any questions about this deactivation or need assistance with your data, please contact our support team immediately.</p>

        <p>Thank you for using our platform.</p>

        <p>Best regards,<br>
        The Gym Management Team</p>
    </div>

    <div class="footer">
        <p><strong>Timeline Summary:</strong></p>
        <ul>
            <li><strong>Immediate:</strong> Data export sent (this email)</li>
            <li><strong>2 days:</strong> All accounts will be deactivated</li>
            <li><strong>30 days:</strong> All data will be permanently deleted</li>
        </ul>
        
        <p style="margin-top: 20px; font-size: 12px;">
            This is an automated message. Please do not reply to this email.
        </p>
    </div>
</body>
</html>
