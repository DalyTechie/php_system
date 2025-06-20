<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Library System Settings</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php
 // Include session check
 require_once 'session_check.php';
 include 'components/head.php';
 ?>
        <!-- Google Fonts for a modern look -->
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
                font-family: 'Montserrat', Arial, sans-serif;
                background: #f3f4f6;
                margin: 0;
                padding: 0;
            }
            .settings-container {
                max-width: 1000px;
                margin: 3rem auto;
                padding: 2.5rem 2rem;
                background: #fff;
                border-radius: 1.2rem;
                box-shadow: 0 4px 24px rgba(44, 62, 80, 0.08);
                margin-left:400px;
            }
            .settings-title {
                font-size: 2rem;
                font-weight: 700;
                color: #2d3748;
                text-align: center;
                margin-bottom: 2rem;
                letter-spacing: 1px;
                background: linear-gradient(90deg, #4fd1c5 0%, #4299e1 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            .settings-section {
                margin-bottom: 2.5rem;
                border-bottom: 1px solid #e2e8f0;
                padding-bottom: 2rem;
            }
            .settings-section:last-child {
                border-bottom: none;
            }
            .section-title {
                font-size: 1.2rem;
                font-weight: 600;
                color: #4299e1;
                margin-bottom: 1.2rem;
            }
            .form-group {
                margin-bottom: 1.2rem;
            }
            label {
                display: block;
                font-size: 1rem;
                color: #374151;
                margin-bottom: 0.4rem;
            }
            input[type="text"],
            input[type="number"],
            input[type="email"] {
                width: 100%;
                padding: 0.7rem 1rem;
                border: 1px solid #cbd5e1;
                border-radius: 0.5rem;
                font-size: 1rem;
                background: #f9fafb;
                transition: border 0.2s;
            }
            input:focus {
                border-color: #4299e1;
                outline: none;
            }
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
                vertical-align: middle;
        }
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .toggle-slider {
            position: absolute;
            cursor: pointer;
                top: 0; left: 0; right: 0; bottom: 0;
            background-color: #ccc;
            transition: .4s;
                border-radius: 24px;
        }
        .toggle-slider:before {
            position: absolute;
            content: "";
                height: 16px; width: 16px;
                left: 4px; bottom: 4px;
            background-color: white;
            transition: .4s;
                border-radius: 50%;
        }
        input:checked + .toggle-slider {
                background-color: #4fd1c5;
            }
        input:checked + .toggle-slider:before {
            transform: translateX(20px);
        } 
            .settings-actions {
                text-align: right;
            }
            .settings-btn {
                background: linear-gradient(90deg, #4fd1c5 0%, #4299e1 100%);
                color: #fff;
                font-weight: 700;
                border: none;
                border-radius: 0.5rem;
                padding: 0.7rem 2rem;
                font-size: 1rem;
                cursor: pointer;
                transition: background 0.2s;
            }
            .settings-btn:hover {
                background: linear-gradient(90deg, #4299e1 0%, #4fd1c5 100%);
            }
            @media (max-width: 600px) {
                .settings-container {
                    padding: 1rem;
                }
            }
    </style>
</head>
    <body>
    <?php include 'components/sidebar.php'; ?>
<div class="ml-64">
    <?php 
    include 'components/dashboard_stats.php';

    ?>
</div>
        <div class="settings-container">
            <div class="settings-title">Library System Settings</div>
            <form>
                <div class="settings-section">
                    <div class="section-title">Library Information</div>
                    <div class="form-group">
                        <label for="libraryName">Library Name</label>
                        <input type="text" id="libraryName" placeholder="e.g., Central City Library" value="Library System">
                        </div>
                    <div class="form-group">
                        <label for="libraryAddress">Library Address</label>
                        <input type="text" id="libraryAddress" placeholder="e.g., 123 Main St, Anytown">
                        </div>
                    <div class="form-group">
                        <label for="defaultLoanPeriod">Default Loan Period (Days)</label>
                        <input type="number" id="defaultLoanPeriod" placeholder="e.g., 14" value="14">
                        </div>
                    <div class="form-group">
                        <label for="overdueFineRate">Overdue Fine Rate (per day)</label>
                        <input type="number" step="0.01" id="overdueFineRate" placeholder="e.g., 0.25" value="0.25">
                        </div>
                    <div class="form-group">
                        <label for="currencySymbol">Currency Symbol</label>
                        <input type="text" id="currencySymbol" placeholder="e.g., $" value="$">
                        </div>
                    <div class="form-group" style="display: flex; align-items: center; justify-content: space-between;">
                        <span>Enable Member Registration</span>
                            <label class="toggle-switch">
                            <input type="checkbox" id="memberRegistrationToggle" checked>
                                <span class="toggle-slider"></span>
                            </label>
                    </div>
                </div>
                <div class="settings-section">
                    <div class="section-title">Account Settings</div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" placeholder="your.email@example.com" value="admin@example.com">
                        </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" placeholder="yourusername" value="administrator">
                        </div>
                    <div class="form-group">
                        <button type="button" class="settings-btn" style="background: #fff; color: #4299e1; border: 1px solid #4299e1;">Change Password</button>
                    </div>
                </div>
                <div class="settings-section">
                    <div class="section-title">Notification Settings</div>
                    <div class="form-group" style="display: flex; align-items: center; justify-content: space-between;">
                        <span>Email Notifications</span>
                            <label class="toggle-switch">
                            <input type="checkbox" id="emailNotifications" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    <div class="form-group" style="display: flex; align-items: center; justify-content: space-between;">
                        <span>Push Notifications</span>
                            <label class="toggle-switch">
                            <input type="checkbox" id="pushNotifications">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    <div class="form-group" style="display: flex; align-items: center; justify-content: space-between;">
                        <span>SMS Alerts</span>
                            <label class="toggle-switch">
                            <input type="checkbox" id="smsAlerts">
                                <span class="toggle-slider"></span>
                            </label>
                    </div>
                </div>
                <div class="settings-actions">
                    <button type="submit" class="settings-btn">Save Changes</button>
                </div>
            </form>
        </div>
        <?php include 'components/top_bar.php'; ?>
        <script src="js/charts.js"></script>
    </body>
    </html>
    