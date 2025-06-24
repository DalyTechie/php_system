<?php
require_once 'session_check.php';
require_once 'db.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require("db.php");
    
    try {
        // File upload handling
        $target_dir = "uploads/students/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Check if image file is actual image
        if(getimagesize($_FILES["photo"]["tmp_name"]) === false) {
            throw new Exception("File is not an image.");
        }
        
        // Check file size (5MB max)
        if ($_FILES["photo"]["size"] > 5000000) {
            throw new Exception("File is too large. Maximum size is 5MB.");
        }
        
        // Allow certain file formats
        if($file_extension != "jpg" && $file_extension != "jpeg" && $file_extension != "png") {
            throw new Exception("Only JPG, JPEG & PNG files are allowed.");
        }
        
        // Upload file
        if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            throw new Exception("Failed to upload file.");
        }
        
        // Prepare and execute SQL statement
        $stmt = $conn->prepare("INSERT INTO tblstudent (student_id, firstname, lastname, course_id, photo, email, phone, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $student_id = $_POST['studentId'];
        $firstname = $_POST['firstName'];
        $lastname = $_POST['lastName'];
        $course_id = $_POST['courseId'];
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        
        $stmt->bind_param("sssissss", 
            $student_id,
            $firstname,
            $lastname,
            $course_id,
            $target_file,
            $email,
            $phone,
            $address
        );
        
        if ($stmt->execute()) {
            echo "<script>
                    alert('Student added successfully!');
                    window.location.href = 'student.php';
                  </script>";
        } else {
            throw new Exception("Error inserting record: " . $stmt->error);
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        echo "<script>
                alert('Error: " . addslashes($e->getMessage()) . "');
                window.location.href = 'student.php';
              </script>";
    }
    
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    require_once 'session_check.php';
    ?>
    <title>Student Management</title>
    <?php include 'components/head.php'; ?>
    <style>
        /* Base styles for full width layout */
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-color: #f3f4f6;
            font-family: 'Khmer OS Siemreap', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        .font-khmer h1{
            font-family: 'Khmer OS Muol Light', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        /* Main container styles */
        .main-container {
            margin-left: 16rem;  /* Match sidebar width */
            min-height: 50vh;
            padding: 2rem;
            width: calc(100% - 16rem);  /* Full width minus sidebar */
            box-sizing: border-box;
            background-color: #f3f4f6;
        }

        /* Ensure sidebar is fixed */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 16rem;
            height: 100vh;
            background-color: #ffffff;
            box-shadow: 4px 0 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 50;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
        }
         /* .btn-edit {
            background: #2563eb;
            color: #2563eb;
            border: 1.5px solid #2563eb;
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: border-color 0.2s, color 0.2s;
        } */
        
        /* .btn-edit:hover {
            background: transparent;
            color: #174ea6;
            border-color: #174ea6;
        }
        .btn-info {
            border: 1.5px solid rgb(51, 196, 41);
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: border-color 0.2s, color 0.2s;
        }
        .btn-info:hover{
            background: transparent;
            border-color: rgb(51, 196, 41);
        }
        
        .btn-delete {
            background:  #e74c3c;
            color: #e74c3c;
            border: 1.5px solid #e74c3c;
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: border-color 0.2s, color 0.2s;
        }

        .btn-delete:hover {
            background: transparent;
            color: #b91c1c;
            border-color: #b91c1c;
        } */


        .search-input {
            padding: 0.5rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            width: 300px;
            outline: none;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: #4f46e5;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background-color: #4338ca;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: #9333ea;
            color: white;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #7e22ce;
            transform: translateY(-1px);
        }

        .btn-danger {
            background-color: #dc2626;
            color: white;
            border: none;
        }

        .btn-danger:hover {
            background-color: #b91c1c;
        }
        .button-group {
            display: flex;
            gap: 0.5rem;
            margin-top: 1.5rem;
        }
        .btn-cancel {
            background-color: #e2e8f0;
            color: #4a5568;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .btn-cancel:hover {
            background-color: #cbd5e0;
        }
        .btn-submit {
            background-color: #4f46e5;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .btn-submit:hover {
            background-color: #4338ca;
        }

        .floating-add-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background-color: #4f46e5;
            color: white;
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            cursor: pointer;
            transition: all 0.2s;
        }

        .floating-add-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .table-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .students-table {
            width: 100%;
            border-collapse: collapse;
        }

        .students-table th,
        .students-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .students-table th {
            background-color: #f9fafb;
            font-weight: 600;
            color: #374151;
        }

        .students-table tr:hover {
            background-color: #f9fafb;
        }

        .student-photo {
            width: 60px;                   /* Standard size for table view */
            height: 60px;
            border-radius: 50%;            /* Circle shape for a modern profile look */
            object-fit: cover;             /* Ensures photo fills the container without distortion */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);  /* Softer, more elevated shadow */
            border: 2px solid #fff;        /* Adds contrast if used on a colored background */
            background-color: #f0f0f0;     /* Light gray background in case image doesn't load */
        }

        .student-details-photo {
            width: 120px;                  /* Larger size for detail view */
            height: 120px;
            border-radius: 8px;            /* Slightly rounded corners for detail view */
            object-fit: cover;             /* Ensures photo fills the container without distortion */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);  /* Consistent shadow with student-photo */
            border: 1px solid #e5e7eb;     /* Subtle border */
            background-color: #f0f0f0;     /* Consistent background with student-photo */
        }

        .photo-container {
            flex: 0 0 120px;              /* Match the width of student-details-photo */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .course-badge {
            background-color: #e0e7ff;
            color: #4338ca;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background-color: white;
            border-top: 1px solid #e5e7eb;
        }

        .page-info {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .page-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .page-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            background-color: white;
            color: #374151;
            cursor: pointer;
        }

        .page-btn:hover {
            background-color: #f9fafb;
        }

        .page-btn.active {
            background-color: #4f46e5;
            color: white;
            border-color: #4f46e5;
        }

        /* Modal and Form Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            overflow-y: auto;
            padding: 2rem 1rem;
        }

        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            width: 100%;
            max-width: 600px;
            position: relative;
            margin: auto;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6b7280;
            cursor: pointer;
            padding: 0.5rem;
            margin: -0.5rem;
            border-radius: 0.375rem;
            transition: all 0.2s;
        }

        .close-btn:hover {
            color: #111827;
            background-color: #f3f4f6;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 0.625rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.5;
            color: #111827;
            background-color: #ffffff;
            background-clip: padding-box;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus {
            color: #111827;
            background-color: #ffffff;
            border-color: #4f46e5;
            outline: 0;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.25);
        }

        select.form-control {
            padding-right: 2rem;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        /* Current Photo Preview */
        #currentPhotoPreview {
            margin-top: 1rem;
        }

        #currentPhoto {
            border-radius: 0.375rem;
            border: 1px solid #d1d5db;
            padding: 0.25rem;
            background-color: #f9fafb;
        }

        /* Form Submit Button */
        form .btn-primary {
            width: 100%;
            padding: 0.75rem 1.5rem;
            margin-top: 1rem;
            font-size: 1rem;
        }

        /* Modal Animation */
        .modal.active {
            display: flex;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .modal-content {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Responsive Adjustments */
        @media (max-width: 640px) {
            .modal-content {
                padding: 1.5rem;
            }

            .form-group {
                margin-bottom: 1rem;
            }

            .modal {
                padding: 1rem;
            }
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
        }

        .search-input {
            padding: 0.5rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            width: 300px;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: #4f46e5;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background-color: #4338ca;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: #9333ea;
            color: white;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #7e22ce;
            transform: translateY(-1px);
        }

        .btn-danger {
            background-color: #dc2626;
            color: white;
            border: none;
        }

        .btn-danger:hover {
            background-color: #b91c1c;
        }

        .floating-add-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background-color: #4f46e5;
            color: white;
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            cursor: pointer;
            transition: all 0.2s;
        }

        .floating-add-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .table-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .students-table {
            width: 100%;
            border-collapse: collapse;
        }

        .students-table th,
        .students-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .students-table th {
            background-color: #f9fafb;
            font-weight: 600;
            color: #374151;
        }

        .students-table tr:hover {
            background-color: #f9fafb;
        }

        .student-photo {
            width: 60px;                   /* Standard size for table view */
            height: 60px;
            border-radius: 50%;            /* Circle shape for a modern profile look */
            object-fit: cover;             /* Ensures photo fills the container without distortion */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);  /* Softer, more elevated shadow */
            border: 2px solid #fff;        /* Adds contrast if used on a colored background */
            background-color: #f0f0f0;     /* Light gray background in case image doesn't load */
        }

        .student-details-photo {
            width: 120px;                  /* Larger size for detail view */
            height: 120px;
            border-radius: 8px;            /* Slightly rounded corners for detail view */
            object-fit: cover;             /* Ensures photo fills the container without distortion */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);  /* Consistent shadow with student-photo */
            border: 1px solid #e5e7eb;     /* Subtle border */
            background-color: #f0f0f0;     /* Consistent background with student-photo */
        }

        .photo-container {
            flex: 0 0 120px;              /* Match the width of student-details-photo */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .course-badge {
            background-color: #e0e7ff;
            color: #4338ca;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background-color: white;
            border-top: 1px solid #e5e7eb;
        }

        .page-info {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .page-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .page-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            background-color: white;
            color: #374151;
            cursor: pointer;
        }

        .page-btn:hover {
            background-color: #f9fafb;
        }

        .page-btn.active {
            background-color: #4f46e5;
            color: white;
            border-color: #4f46e5;
        }

        /* Additional styles for the enhanced table */
        .student-name {
            font-weight: 500;
            color: #111827;
        }

        .student-email {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .phone-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #4f46e5;
            text-decoration: none;
            font-weight: 500;
        }

        .phone-link:hover {
            text-decoration: underline;
        }

        .address {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-borrowed {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .status-available {
            background-color: #dcfce7;
            color: #16a34a;
        }

        /* Make the table more compact but still readable */
        .students-table td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
        }

        /* Add hover effect to the entire row */
        .students-table tr:hover {
            background-color: #f8fafc;
        }

        /* Style for the action buttons container */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: nowrap;
        }

        /* Make buttons smaller in the table */
        .action-buttons .btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .student-details {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .student-photo-container {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .student-details-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #e5e7eb;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .detail-item label {
            font-weight: 600;
            color: #4b5563;
            font-size: 0.875rem;
        }

        .detail-item span {
            color: #111827;
            font-size: 1rem;
        }

        @media (max-width: 640px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
        }

        /* .btn-info, .btn-primary, .btn-delete {
            background: transparent !important;
            border-width: 1.5px;
            border-style: solid;
            border-radius: 0.375rem;
            font-weight: 500;
            font-size: 0.95em;
            padding: 0.375rem 0.9rem;
            transition: border-color 0.2s, color 0.2s;
            cursor: pointer;
            outline: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3em;
        } */
        .btn-info {
            color: #0dcaf0;
            border-color: #0dcaf0;
        }
        .btn-info:hover {
            color: #0a8ca0;
            border-color: #0a8ca0;
            background: transparent !important;
        }
        .btn-primary {
            color: #2563eb;
            border-color: #2563eb;
        }
        .btn-primary:hover {
            color: #174ea6;
            border-color: #174ea6;
            /* background: transparent !important; */
        }
        .btn-delete {
            color: #e74c3c;
            border-color: #e74c3c;
        }
        .btn-delete:hover {
            color: #b91c1c;
            border-color: #b91c1c;
            background: transparent !important;
        }

        /* Modal overlay */
        #viewDetailsModal.modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0; top: 0; width: 100vw; height: 100vh;
            background: rgba(30, 41, 59, 0.45);
            justify-content: center;
            align-items: center;
            transition: background 0.3s;
        }
        #viewDetailsModal.modal.active {
            display: flex;
            animation: fadeIn 0.3s;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        /* Modal content */
        #viewDetailsModal .modal-content {
            background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%);
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(30,41,59,0.18);
            padding: 36px 32px;
            max-width: 540px;
            width: 100%;
            position: relative;
            animation: slideIn 0.4s;
        }
        @keyframes slideIn {
            from { transform: translateY(-40px); opacity: 0; }
            to   { transform: translateY(0); opacity: 1; }
        }

        /* Close button */
        #viewDetailsModal .close-btn {
            position: absolute;
            top: 18px; right: 18px;
            background: none;
            border: none;
            font-size: 2rem;
            color: #64748b;
            cursor: pointer;
            transition: color 0.2s;
        }
        #viewDetailsModal .close-btn:hover {
            color: #1e293b;
        }

        /* Photo styling */
        #viewDetailsModal .photo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 18px;
        }
        #viewDetailsModal .student-details-photo {
            width: 120px; height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #e0e7ef;
            box-shadow: 0 4px 16px rgba(30,41,59,0.10);
            background: #f1f5f9;
        }

        /* Details grid */
        #viewDetailsModal .details-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px 32px;
            margin-bottom: 0;
        }
        @media (max-width: 600px) {
            #viewDetailsModal .modal-content { padding: 18px 6px; }
            #viewDetailsModal .details-container { grid-template-columns: 1fr; gap: 12px 0; }
        }

        /* Detail items */
        #viewDetailsModal .detail-item label {
            font-weight: 700;
            color: #334155;
            font-size: 1em;
            margin-bottom: 2px;
            display: block;
            letter-spacing: 0.01em;
        }
        #viewDetailsModal .detail-item span {
            color: #0f172a;
            font-size: 1.08em;
            margin-top: 2px;
            display: block;
            word-break: break-word;
        }

        /* Status badge */
        #viewDetailsModal .status-badge {
            padding: 0.3em 1.1em;
            border-radius: 9999px;
            font-size: 1em;
            font-weight: 600;
            display: inline-block;
            margin-top: 2px;
            letter-spacing: 0.01em;
        }
        #viewDetailsModal .status-borrowed {
            background: linear-gradient(90deg, #fee2e2 60%, #fecaca 100%);
            color: #b91c1c;
            border: 1.5px solid #fca5a5;
        }
        #viewDetailsModal .status-available {
            background: linear-gradient(90deg, #dcfce7 60%, #bbf7d0 100%);
            color: #15803d;
            border: 1.5px solid #86efac;
        }
        .header-title-btn-bg {
            background-color: #4f46e5;
            color: #fff;
            padding: 0.5rem 1.5rem;
            border-radius: 0.375rem;
            display: inline-block;
        }
    </style>
</head>
<body>
    <?php include 'components/sidebar.php'; ?>
    <div class="ml-64">
        <?php include 'components/dashboard_stats.php'; ?>
    </div>
    <div class="main-container">
        <div class="page-header">
            <h1 class="text-2xl font-khmer header-title-btn-bg">ចុះឈ្មោះនិស្សិត</h1>
            <div class="header-actions">
                <div style="position: relative; display: flex; align-items: center;">
                    <input type="text" placeholder="ស្វែងរក..." class="search-input" id="searchInput" style="padding-right: 2.5rem;">
                    <span style="position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); background-color: #4f46e5; color: #fff; border-radius: 50%; width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center; pointer-events: none; box-shadow: 0 1px 4px rgba(0,0,0,0.07);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                        </svg>
                    </span>
                </div>
                <button class="btn btn-primary" style="  background-color:rgb(25, 80, 231); color: #fff; border: none;"onclick="openAddStudentModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 0a1 1 0 0 1 1 1v6h6a1 1 0 1 1 0 2H9v6a1 1 0 1 1-2 0V9H1a1 1 0 0 1 0-2h6V1a1 1 0 0 1 1-1z"/>
                    </svg>
                   បញ្ចូលឈ្មោះនិស្សិត
                </button>
            </div>
        </div>

        <div class="table-container">
            <table class="students-table">
                <thead>
                    <tr>
                        <th>រូបភាព</th>
                        <th>លេខកូដកាត</th>
                        <th>ឈ្មោះ</th>
                        <th>ដេប៉ាទីម៉ង</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require("db.php");
                    
                    $sql = "SELECT *, c.course_name,
                           (SELECT COUNT(*) FROM tblborrower b 
                            WHERE b.student_id = s.student_id AND b.return_date IS NULL) as active_borrows
                           FROM tblstudent s 
                           LEFT JOIN tblcourse c ON s.course_id = c.course_id 
                           ORDER BY s.student_id";
                    
                    $result = $conn->query($sql);
                    
                    if ($result && $result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            // Determine borrowing status
                            $borrowStatus = $row['active_borrows'] > 0 ? 
                                          '<span class="status-badge status-borrowed">កំពុងខ្ចី (' . $row['active_borrows'] . ')</span>' : 
                                          '<span class="status-badge status-available">មិនមានការខ្ចី</span>';

                            echo "<tr>";
                            echo "<td><img src='" . htmlspecialchars($row['photo']) . "' alt='Student photo' class='student-photo'></td>";
                            echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                            echo "<td>
                                    <div class='student-name'>" . 
                                        htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) . 
                                    "</div>
                                    <div class='student-email'>" . 
                                        htmlspecialchars($row['email']) . 
                                    "</div>
                                  </td>";
                            echo "<td><span class='course-badge'>" . htmlspecialchars($row['course_name']) . "</span></td>";
                            echo "<td>" . $borrowStatus . "</td>";
                            echo "<td class='actions'>
                                    <button onclick='viewDetails(\"" . htmlspecialchars($row['student_id']) . "\")' class='btn-info'>
                                        <span style='font-size:1.1em;'>👁️</span> 
                                    </button>
                                    <button onclick='editStudent(\"" . htmlspecialchars($row['student_id']) . "\")' class='btn-edit '>
                                        <span style='font-size:1.1em;'>✏️</span> 
                                    </button>
                                    <button onclick='deleteStudent(\"" . htmlspecialchars($row['student_id']) . "\")' class='btn-delete '>
                                        <span style='font-size:1.1em;'>🗑️</span> 
                                    </button>
                                </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center py-4'>No students found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Floating Add Button -->
        
    </div>

    <!-- Add Student Modal -->
    <div id="addStudentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">បញ្ចូលឈ្មោះនិស្សិត</h2>
                <button class="close-btn" onclick="closeAddStudentModal()">&times;</button>
            </div>
            <form id="addStudentForm" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="studentId">លេខកូដកាត</label>
                    <input type="text" id="studentId" name="studentId" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="firstName">នាមត្រកូល</label>
                    <input type="text" id="firstName" name="firstName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="lastName">នាមខ្លួន</label>
                    <input type="text" id="lastName" name="lastName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">អ៊ីមែល</label>
                    <input type="email" id="email" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <label for="courseId">*ដេប៉ាទីម៉ង</label>
                    <select id="courseId" name="courseId" class="form-control" required>
                        <!-- <option value=""></option> -->
                        <?php
                        $courseSql = "SELECT course_id, course_name FROM tblcourse ORDER BY course_name";
                        $courseResult = $conn->query($courseSql);
                        if ($courseResult && $courseResult->num_rows > 0) {
                            while($course = $courseResult->fetch_assoc()) {
                                echo "<option value='" . $course['course_id'] . "'>" . 
                                     htmlspecialchars($course['course_name']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="photo">រូបភាព</label>
                    <input type="file" id="photo" name="photo" class="form-control" accept="image/*" required>
                </div>
                <div class="button-group">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save mr-2"></i>រក្សាទុក
                    </button>
                    <button type="button" class="btn-cancel" onclick="closeModal()">
                        <i class="fas fa-times mr-2"></i>បោះបង់
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Student Modal -->
    <div id="editStudentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">កែប្រែឈ្មោះនិស្សិត</h2>
                <button class="close-btn" onclick="closeEditStudentModal()">&times;</button>
            </div>
            <form id="editStudentForm" method="post" enctype="multipart/form-data">
                <input type="hidden" id="editStudentId" name="student_id">
                <div class="form-group">
                    <label for="editFirstName">នាមត្រកូល</label>
                    <input type="text" id="editFirstName" name="firstName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="editLastName">នាមខ្លួន</label>
                    <input type="text" id="editLastName" name="lastName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="editEmail">អ៊ីមែល</label>
                    <input type="email" id="editEmail" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <label for="editCourseId">*ដេប៉ាទីម៉ង</label>
                    <select id="editCourseId" name="courseId" class="form-control" required>
                        <!-- <option value="">Select Course</option> -->
                        <?php
                        $courseSql = "SELECT course_id, course_name FROM tblcourse ORDER BY course_name";
                        $courseResult = $conn->query($courseSql);
                        if ($courseResult && $courseResult->num_rows > 0) {
                            while($course = $courseResult->fetch_assoc()) {
                                echo "<option value='" . $course['course_id'] . "'>" . 
                                     htmlspecialchars($course['course_name']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="editPhoto">(ទុកទទេ​ដើម្បី​រក្សា​រូបភាព)</label>
                    <input type="file" id="editPhoto" name="photo" class="form-control" accept="image/*">
                    <div id="currentPhotoPreview" class="mt-2">
                        <img id="currentPhoto" src="" alt="Current photo" style="max-width: 100px; display: none;">
                    </div>
                </div>
                <div class="button-group">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save mr-2"></i>កែប្រែ
                    </button>
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">
                        <i class="fas fa-times mr-2"></i>បោះបង់
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php 
 include 'components/top_bar.php';
?>
    <!-- View Details Modal -->
    <div id="viewDetailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 style="display:flex;align-items:center;gap:8px;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user" style="margin-right:4px;"><circle cx="12" cy="7" r="4"/><path d="M5.5 21a7.5 7.5 0 0 1 13 0"/></svg>
                    ព័ត៌មានលម្អិតនិស្សិត
                </h2>
                <button class="close-btn" onclick="closeViewDetailsModal()">&times;</button>
            </div>
                <div class="photo-container">
                    <img id="detailsPhoto" src="" alt="Student photo" class="student-details-photo">
                </div>
                <div class="details-container">
                    <div class="detail-item">
                    <label><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-hash" style="vertical-align:middle;margin-right:4px;"><text x="2" y="13" font-size="12" font-family="Arial">#</text></svg> លេខសម្គាល់និស្សិត</label>
                        <span id="detailsStudentId"></span>
                    </div>
                    <div class="detail-item">
                    <label><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user" style="vertical-align:middle;margin-right:4px;"><circle cx="8" cy="5" r="4"/><path d="M2.5 15a7.5 7.5 0 0 1 11 0"/></svg> ឈ្មោះពេញ</label>
                        <span id="detailsFullName"></span>
                    </div>
                    <div class="detail-item">
                    <label><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book" style="vertical-align:middle;margin-right:4px;"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M4 4.5A2.5 2.5 0 0 1 6.5 2H20v15H6.5A2.5 2.5 0 0 1 4 14.5z"/></svg> ដេប៉ាទីម៉ង</label>
                        <span id="detailsCourse"></span>
                    </div>
                    <div class="detail-item">
                    <label><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail" style="vertical-align:middle;margin-right:4px;"><rect x="2" y="4" width="12" height="8" rx="2"/><path d="M2 4l6 4 6-4"/></svg> អ៊ីមែល</label>
                        <span id="detailsEmail"></span>
                    </div>
                    <div class="detail-item">
                    <label><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone" style="vertical-align:middle;margin-right:4px;"><path d="M22 16.92V21a2 2 0 0 1-2.18 2A19.72 19.72 0 0 1 3 5.18 2 2 0 0 1 5 3h4.09a2 2 0 0 1 2 1.72c.13 1.13.37 2.23.72 3.28a2 2 0 0 1-.45 2.11l-1.27 1.27a16 16 0 0 0 6.29 6.29l1.27-1.27a2 2 0 0 1 2.11-.45c1.05.35 2.15.59 3.28.72A2 2 0 0 1 21 18.91V21z"/></svg> លេខទូរស័ព្ទ</label>
                        <span id="detailsPhone"></span>
                    </div>
                    <div class="detail-item">
                    <label><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home" style="vertical-align:middle;margin-right:4px;"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg> អាសយដ្ឋាន</label>
                        <span id="detailsAddress"></span>
                    </div>
                    <div class="detail-item">
                    <label><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar" style="vertical-align:middle;margin-right:4px;"><rect x="3" y="4" width="13" height="13" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/></svg> កាលបរិច្ឆេទចុះឈ្មោះ</label>
                        <span id="detailsCreatedAt"></span>
                    </div>
                    <div class="detail-item">
                    <label><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle" style="vertical-align:middle;margin-right:4px;"><circle cx="8" cy="8" r="7"/><polyline points="5 8 7 10 11 6"/></svg> ស្ថានភាពខ្ចី</label>
                        <span id="detailsBorrowStatus"></span>
                </div>
            </div>
        </div>
    </div>



    <script>
        // Search functionality
        document.querySelector('.search-input').addEventListener('input', function(e) {
            const searchText = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.students-table tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchText) ? '' : 'none';
            });
        });

        // Modal functions
        function openAddStudentModal() {
            document.getElementById('addStudentModal').classList.add('active');
            document.body.classList.add('modal-open');
        }

        function closeAddStudentModal() {
            document.getElementById('addStudentModal').classList.remove('active');
            document.body.classList.remove('modal-open');
        }

        function editStudent(studentId) {
            // Fetch student details
            fetch(`edit_student.php?student_id=${studentId}`)
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.error || `HTTP error! status: ${response.status}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.success || !data.data) {
                        throw new Error('Failed to load student data');
                    }

                    const student = data.data;
                    
                    // Populate the form
                    document.getElementById('editStudentId').value = student.student_id;
                    document.getElementById('editFirstName').value = student.firstname;
                    document.getElementById('editLastName').value = student.lastname;
                    document.getElementById('editEmail').value = student.email || '';
                    document.getElementById('editCourseId').value = student.course_id;

                    // Show current photo
                    const currentPhoto = document.getElementById('currentPhoto');
                    if (student.photo) {
                        currentPhoto.src = student.photo;
                        currentPhoto.style.display = 'block';
                    } else {
                        currentPhoto.style.display = 'none';
                    }

                    // Show the modal
                    document.getElementById('editStudentModal').classList.add('active');
                    document.body.classList.add('modal-open');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load student details: ' + error.message);
                });
        }

        function closeEditStudentModal() {
            document.getElementById('editStudentModal').classList.remove('active');
            document.body.classList.remove('modal-open');
        }

        function viewDetails(studentId) {
            fetch(`get_student_details.php?student_id=${studentId}`)
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.error || `HTTP error! status: ${response.status}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data) throw new Error('No data received from server');
                    
                    // Photo with fallback
                    const detailsPhoto = document.getElementById('detailsPhoto');
                    detailsPhoto.src = (data.photo && data.photo.trim() !== '') ? data.photo : 'images/default-avatar.png';

                    document.getElementById('detailsStudentId').textContent = data.student_id;
                    document.getElementById('detailsFullName').textContent = `${data.firstname} ${data.lastname}`;
                    document.getElementById('detailsCourse').textContent = data.course_name;
                    document.getElementById('detailsEmail').textContent = data.email || 'Not provided';
                    document.getElementById('detailsPhone').textContent = data.phone || 'Not provided';
                    document.getElementById('detailsAddress').textContent = data.address || 'Not provided';

                    // Format date
                    const date = new Date(data.created_at);
                    document.getElementById('detailsCreatedAt').textContent = date.toLocaleDateString('en-US', {
                        year: 'numeric', month: 'long', day: 'numeric'
                    });

                    // Borrowing status badge
                    const borrowStatus = document.getElementById('detailsBorrowStatus');
                    if (data.active_borrows > 0) {
                        borrowStatus.textContent = `កំពុងខ្ចី (${data.active_borrows} កំណត់ត្រា)`;
                        borrowStatus.className = 'status-badge status-borrowed';
                    } else {
                        borrowStatus.textContent = 'មិនមានការខ្ចី';
                        borrowStatus.className = 'status-badge status-available';
                    }
                    
                    // Show the modal
                    document.getElementById('viewDetailsModal').classList.add('active');
                    document.body.classList.add('modal-open');
                })
                .catch(error => {
                    console.error('Error details:', error);
                    alert('Failed to load student details: ' + error.message);
                });
        }

        function closeViewDetailsModal() {
            document.getElementById('viewDetailsModal').classList.remove('active');
            document.body.classList.remove('modal-open');
        }

        // Handle edit form submission
        document.getElementById('editStudentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);

            fetch('edit_student.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.error || `HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('ការកែប្រែបានជោគជ័យ');
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'ការកែប្រែមិនបានជោគជ័យ');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update student: ' + error.message);
            });
        });

        function deleteStudent(studentId) {
            if (!confirm('តើអ្នកប្រាកដជាចង់លុបឈ្មោះនិស្សិតនេះមែនទេ?')) {
                return;
            }

            const formData = new FormData();
            formData.append('student_id', studentId);

            fetch('delete_student.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.error || `HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('ការលុបបានជោគជ័យ');
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'ការលុបមិនបានជោគជ័យ');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to delete student: ' + error.message);
            });
        }

        // Preview image before upload for both add and edit forms
        document.getElementById('photo').addEventListener('change', function(e) {
            previewImage(this, 'photoPreview');
        });

        document.getElementById('editPhoto').addEventListener('change', function(e) {
            previewImage(this, 'currentPhotoPreview');
        });

        function previewImage(input, previewId) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '100px';
                    img.style.marginTop = '10px';
                    
                    const previewDiv = document.getElementById(previewId);
                    previewDiv.innerHTML = '';
                    previewDiv.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        }
    </script> 
    
</body>
</html>