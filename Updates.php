<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System Updates</title>
    <?php
// Include session check
require_once 'session_check.php';
include 'components/head.php';
?>
    <!-- Google Fonts for a modern look -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;400&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background: #f7fafc;
            margin: 0;
            padding: 0;
        }
        .updates-container {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 2rem;
            background: #fff;
            border-radius: 1.2rem;
            box-shadow: 0 4px 24px rgba(44, 62, 80, 0.08);
            margin-left: 300px;
        }
        .updates-title {
            font-size: 2.2rem;
            font-weight: 700;
            text-align: center;
            color: #2d3748;
            margin-bottom: 2rem;
            letter-spacing: 1px;
            background: linear-gradient(90deg, #4fd1c5 0%, #4299e1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .update-card {
            background: #f1f5f9;
            border-left: 6px solid #4fd1c5;
            border-radius: 0.7rem;
            margin-bottom: 1.5rem;
            padding: 1.2rem 1.5rem;
            box-shadow: 0 2px 8px rgba(44, 62, 80, 0.06);
        }
        .update-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #4299e1;
            margin-bottom: 0.5rem;
        }
        .update-message {
            color: #2d3748;
            margin-bottom: 0.5rem;
        }
        .update-date {
            font-size: 0.9rem;
            color: #718096;
            text-align: right;
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
    <div class="updates-container">
        <div class="updates-title">Library System Updates</div>
        
        <div class="update-card">
            <div class="update-title">📚 New Books Added!</div>
            <div class="update-message">We have added 50+ new titles to our collection, including the latest bestsellers and academic resources.</div>
            <div class="update-date">June 10, 2024</div>
        </div>
        
        <div class="update-card">
            <div class="update-title">🛠️ System Maintenance</div>
            <div class="update-message">The library system will be under maintenance on June 15th from 1:00 AM to 3:00 AM. Please plan your activities accordingly.</div>
            <div class="update-date">June 8, 2024</div>
        </div>
        
        <div class="update-card">
            <div class="update-title">📢 Summer Reading Program</div>
            <div class="update-message">Join our summer reading challenge and win exciting prizes! Registration is open at the front desk.</div>
            <div class="update-date">June 1, 2024</div>
        </div>
        
        <!-- Add more static updates as needed -->
    </div>


     <?php 

       include 'components/top_bar.php';
     ?>
 </div>
</body>
</html>