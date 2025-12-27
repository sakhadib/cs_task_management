<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Assignment Notification</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f3f4f6;
            padding: 40px 20px;
            line-height: 1.6;
            color: #111827;
        }
        
        .email-wrapper {
            max-width: 680px;
            margin: 0 auto;
        }
        
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
        }
        
        .header {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            padding: 48px 40px;
            text-align: center;
            border-bottom: 4px solid #000000;
        }
        
        .logo-container {
            margin-bottom: 24px;
        }
        
        .logo-text {
            font-size: 32px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }
        
        .logo-subtitle {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        .header-title {
            font-size: 20px;
            color: #ffffff;
            font-weight: 600;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .content {
            padding: 40px;
        }
        
        .greeting {
            font-size: 18px;
            color: #111827;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .intro-text {
            font-size: 15px;
            color: #4b5563;
            margin-bottom: 32px;
            line-height: 1.7;
        }
        
        .task-card {
            background: linear-gradient(to bottom, #ffffff 0%, #f9fafb 100%);
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 28px;
            margin-bottom: 32px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        
        .task-header {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .task-label {
            font-size: 11px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 8px;
        }
        
        .task-title {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            line-height: 1.3;
            margin-bottom: 0;
        }
        
        .task-description {
            font-size: 15px;
            color: #4b5563;
            margin-bottom: 24px;
            line-height: 1.7;
            padding: 16px;
            background-color: #f9fafb;
            border-left: 3px solid #d1d5db;
            border-radius: 4px;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: 140px 1fr;
            gap: 16px 20px;
            margin-top: 20px;
        }
        
        .detail-label {
            font-size: 13px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .detail-value {
            font-size: 14px;
            color: #111827;
            font-weight: 500;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 14px;
            background-color: #111827;
            color: #ffffff;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }
        
        .cta-section {
            text-align: center;
            margin: 32px 0;
            padding: 28px;
            background-color: #f9fafb;
            border-radius: 8px;
        }
        
        .cta-button {
            display: inline-block;
            background-color: #111827;
            color: #ffffff;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 700;
            text-align: center;
            letter-spacing: 0.3px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
        }
        
        .cta-button:hover {
            background-color: #000000;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
            transform: translateY(-1px);
        }
        
        .cta-text {
            font-size: 13px;
            color: #6b7280;
            margin-top: 12px;
        }
        
        .help-text {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.6;
            padding: 20px;
            background-color: #f9fafb;
            border-radius: 6px;
            border-left: 3px solid #d1d5db;
        }
        
        .footer {
            background-color: #111827;
            padding: 32px 40px;
            text-align: center;
        }
        
        .footer-brand {
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 6px;
            letter-spacing: -0.3px;
        }
        
        .footer-org {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 20px;
            line-height: 1.5;
        }
        
        .footer-divider {
            height: 1px;
            background-color: rgba(255, 255, 255, 0.2);
            margin: 20px 0;
        }
        
        .footer-links {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .footer-link {
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
            margin: 0 8px;
            transition: opacity 0.2s;
        }
        
        .footer-link:hover {
            opacity: 0.8;
        }
        
        a {
            color: #1f2937;
            text-decoration: none;
        }
        
        a:hover {
            color: #000000;
        }
        
        @media only screen and (max-width: 640px) {
            body {
                padding: 20px 10px;
            }
            
            .email-container {
                border-radius: 0;
            }
            
            .header {
                padding: 32px 24px;
            }
            
            .content {
                padding: 28px 20px;
            }
            
            .task-card {
                padding: 20px;
            }
            
            .details-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .detail-label {
                margin-bottom: 4px;
            }
            
            .cta-section {
                padding: 20px;
            }
            
            .footer {
                padding: 28px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="header">
                <div class="logo-container">
                    <div class="logo-text">IUTCS</div>
                    <div class="logo-subtitle">Internal System</div>
                </div>
                <div class="header-title">Task Assignment Notification</div>
            </div>
            
            <!-- Content -->
            <div class="content">
                <div class="greeting">Dear {{ $userName }},</div>
                
                <p class="intro-text">
                    A new task has been assigned to you in the IUTCS Internal System. Please review the details below and take the necessary action at your earliest convenience.
                </p>
                
                <!-- Task Card -->
                <div class="task-card">
                    <div class="task-header">
                        <div class="task-label">Task Assignment</div>
                        <div class="task-title">{{ $taskTitle }}</div>
                    </div>
                    
                    @if($taskDescription)
                    <div class="task-description">
                        {{ $taskDescription }}
                    </div>
                    @endif
                </div>
                
                <!-- CTA Section -->
                <div class="cta-section">
                    <a href="{{ $taskUrl }}" class="cta-button">VIEW TASK DETAILS</a>
                    <div class="cta-text">Click the button above to access the full task information</div>
                </div>
                
                <div class="help-text">
                    <strong>Need assistance?</strong> If you have any questions regarding this task assignment, please contact your team coordinator for guidance.
                </div>
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <div class="footer-brand">IUTCS Internal System</div>
                <div class="footer-org">Islamic University of Technology - Computer Society</div>
                
                <div class="footer-divider"></div>
                
                <div class="footer-links">
                    <a href="{{ url('/') }}" class="footer-link">Dashboard</a>
                    <span style="color: rgba(255, 255, 255, 0.3);">|</span>
                    <a href="{{ $taskUrl }}" class="footer-link">View Task</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
