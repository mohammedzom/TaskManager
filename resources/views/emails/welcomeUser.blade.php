<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to TaskManager</title>
    <style>
        /* Base styles */
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f4f7;
            color: #51545e;
            margin: 0;
            padding: 0;
            width: 100%;
            -webkit-text-size-adjust: none;
        }

        /* Container */
        .email-wrapper {
            width: 100%;
            background-color: #f4f4f7;
            padding: 30px 0;
        }

        .email-content {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        /* Header */
        .email-header {
            background-color: #4f46e5;
            /* Indigo-600 */
            padding: 30px;
            text-align: center;
        }

        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        /* Body */
        .email-body {
            padding: 40px 30px;
            line-height: 1.6;
        }

        .email-body h2 {
            color: #333333;
            font-size: 20px;
            margin-top: 0;
            margin-bottom: 20px;
        }

        .email-body p {
            margin-bottom: 20px;
            color: #51545e;
        }

        /* Button */
        .action-button {
            display: inline-block;
            background-color: #4f46e5;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }

        /* Footer */
        .email-footer {
            background-color: #f9fafb;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-content {
                width: 100% !important;
                border-radius: 0 !important;
            }

            .email-body {
                padding: 20px !important;
            }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-content">
            <!-- Header -->
            <div class="email-header">
                <h1>TaskManager</h1>
            </div>

            <!-- Body -->
            <div class="email-body">
                <h2>Hello, {{ $user->name }}! 👋</h2>

                <p>Welcome to <strong>TaskManager</strong>! We're thrilled to have you on board.</p>

                <p>Get started by organizing your tasks, setting priorities, and boosting your productivity today.</p>

                <div style="text-align: center;">
                    <a href="{{ config('app.url') }}" class="action-button">Go to Dashboard</a>
                </div>

                <p>If you have any questions, feel free to reply to this email. We're here to help!</p>

                <p>Best regards,<br>The TaskManager Team</p>
            </div>

            <!-- Footer -->
            <div class="email-footer">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                <p>You received this email because you signed up for TaskManager.</p>
            </div>
        </div>
    </div>
</body>

</html>
