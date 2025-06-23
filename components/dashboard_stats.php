<div class="stats-row flex justify-between items-center mb-8 bg-white p-6 rounded-lg shadow-sm border border-gray-100">
    <!-- Logo Section -->
    <div class="stat-item text-center px-8">
       <img src="images/logo.png" alt="Library Management System" class="h-20 w-auto object-contain">
    </div>
    
    <!-- Welcome User Section -->
    <div class="flex items-center space-x-6">
        <!-- Welcome Message -->
        <div class="text-right" style="font-family: 'Khmer OS Siemreap', Arial, sans-serif;">
            <?php  
            // session_start(); 
            require_once 'db.php';
            if(isset($_SESSION['fullname'])){ 
                $fullname = htmlspecialchars($_SESSION['fullname']);
                echo '<div class="text-sm text-gray-500 mb-1">សូមស្វាគមន៍</div>';
                echo '<div class="text-lg font-semibold text-gray-800">' . $fullname . '</div>';
                echo '<div class="text-xs text-gray-400 mt-1">អ្នកគ្រប់គ្រង</div>';
            } else { 
                header("Location:login.php"); 
            } 
            ?> 
        </div>
        
        <!-- User Avatar -->
        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        
        <!-- Notification Icon -->
        <div class="notification-icon relative group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 hover:text-blue-600 transition-colors duration-200 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <!-- Notification Badge -->
            <div class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full">

            </div>
            <!-- Tooltip -->
            <div class="absolute right-0 top-8 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-10">
                Notifications
            </div>
        </div>
        
        <!-- User Profile/Settings Icon -->
        <div class="user-profile-icon relative group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 hover:text-green-600 transition-colors duration-200 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <!-- Tooltip -->
            <div class="absolute right-0 top-8 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-10">
                Profile Settings
            </div>
        </div>
    </div>
</div>
