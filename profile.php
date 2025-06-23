<?php
require_once 'session_check.php';
include 'components/head.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library User Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts for a modern look -->
  
    <style>
        body {
            font-family: 'Nunito', Arial, sans-serif;
      
            margin: 0;
            padding: 0;
        }
        .profile-container {
            max-width: 1150px;
            margin: 2rem auto;
            padding: 2rem;
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 4px 16px rgba(44, 62, 80, 0.08);
            margin-left: 300px;
        }
        .profile-header {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
            margin-bottom: 2rem;
        }
        .profile-info input {
            font-family: inherit;
            font-size: 1rem;
            border: 1px solid #cbd5e1;
            border-radius: 0.4rem;
            padding: 0.3rem 0.7rem;
            margin-bottom: 0.5rem;
            width: 100%;
       
        }
        .profile-info input:focus {
            border-color: #4299e1;
            outline: none;
        }
        .profile-actions {
            margin-top: 0.5rem;
        }
        .profile-btn {
            background: #4299e1;
            color: #fff;
            font-weight: 700;
            border: none;
            border-radius: 0.4rem;
            padding: 0.4rem 1.2rem;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        .profile-btn:hover {
            background: #2b6cb0;
        }
        .simple-dashboard {
            margin-top: 2rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
        }
        .simple-card {
       
            border: 1px solid #e2e8f0;
            border-radius: 0.7rem;
            padding: 1.2rem 1.5rem;
            min-width: 220px;
            max-width: 300px;
            flex: 1 1 220px;
            box-sizing: border-box;
        }
        .simple-card strong {
            display: block;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            color: #2d3748;
        }
        .simple-card p {
            margin: 0;
            color: #4a5568;
            font-size: 1rem;
        }
        .success-message {
            display: none;
            margin-top: 0.7rem;
            padding: 0.7rem;
            background: #d1fae5;
            color: #065f46;
            border-radius: 0.4rem;
            text-align: center;
            font-size: 1rem;
        }
        @media (max-width: 700px) {
            .profile-container {
                padding: 1rem;
            }
            .simple-dashboard {
                flex-direction: column;
                gap: 1rem;
            }
        }
        .about-section {
            margin-top: 2.5rem;
            background: #f9fafb;
            border: 1px solid #e2e8f0;
            border-radius: 0.7rem;
            padding: 1.5rem 2rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            box-sizing: border-box;
        }
        .about-section h2 {
            margin-top: 0;
            color: #4299e1;
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 1rem;
            letter-spacing: 1px;
        }
        .about-section p {
            color: #374151;
            font-size: 1rem;
            margin-bottom: 1rem;
            line-height: 1.6;
        }
        .about-section a {
            color: #4299e1;
            text-decoration: none;
        }
        .about-section a:hover {
            text-decoration: underline;
        }
        .student-logo {
            display: flex;
            justify-content: center;
            margin-top: 1.5rem;
        }
        .student-logo img {
            width: 208px;
            height: 248px;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(44, 62, 80, 0.10);
            background: #fff;
        }
    </style>
</head>
<body>
<?php include 'components/sidebar.php'; ?>
    
    <div class="ml-64">
        <?php 
        include 'components/dashboard_stats.php';
     //       include 'components/top_bar.php';
        ?>
    </div>
    <div class="profile-container">
    
    <div class="about-section">
    <div class="student-logo">
     <!-- Example SVG icon, you can use your own image/logo here -->
     <img src="https://cdn-icons-png.flaticon.com/512/3135/3135768.png" alt="Student Logo" width="150" height="150">
 </div>
    <h2>📖 About Us</h2>
    <p>
        Welcome to <strong>Central City Library</strong>!<br>
        Our mission is to foster a love of reading and lifelong learning in our community. 
        We offer a diverse collection of books, digital resources, and engaging programs for all ages.
    </p>
    <p>
        <strong>Opening Hours:</strong> Monday - Saturday, 8:00 AM - 8:00 PM<br>
        <strong>Address:</strong> 123 Main St, Anytown<br>
        <strong>Contact:</strong> (123) 456-7890 &nbsp;|&nbsp; <a href="mailto:library@centralcity.com">library@centralcity.com</a>
    </p>
    <p>
        Whether you're a student, a researcher, or just looking for your next great read, our friendly staff is here to help you!
    </p>
   
   
   
   
</div>
        <!-- Simple Dashboard Cards with Content -->
        <div class="simple-dashboard">
            <div class="simple-card">
                <strong>📚 Total Books</strong>
                <p>Our library offers a diverse collection of books across all genres and subjects.</p>
            </div>
            <div class="simple-card">
                <strong>✅ Available Books</strong>
                <p>Most books are available for borrowing. Check the catalog for real-time status.</p>
            </div>
            <div class="simple-card">
                <strong>📅 Books Borrowed Today</strong>
                <p>Stay updated with the latest books borrowed by our members each day.</p>
            </div>
            <div class="simple-card">
                <strong>👥 Registered Members</strong>
                <p>Join our community of readers and enjoy exclusive member benefits.</p>
            </div>
            
            
            
            
        </div>
        <!-- About Us Section -->
        
        
        
    </div>
    <script>
        function saveProfile() {
            document.getElementById('successMessage').style.display = 'block';
            setTimeout(() => {
                document.getElementById('successMessage').style.display = 'none';
            }, 2000);
        }
    </script>
     <?php include 'components/top_bar.php'; ?>
</body>
</html>