<aside class="w-64 h-screen fixed left-0 bg-white shadow-lg" id='pkay'>
    <div class="flex flex-col h-full">
        <!-- Dashboard Header -->
    <div class="p-6 border-b">
            <h1 class="text-xl font-semibold text-gray-800" >
                <span style="font-size:1.6rem;">📚</span> Library System
            </h1>
        </div>
        
        <!-- Navigation Menu -->
        <nav class="flex-1 p-4">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-4">Main</p>
            
            <!-- Dashboard Link -->
            <a href="index.php" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-indigo-600 rounded-lg mb-2">
                <span style="font-size:1.3rem; margin-right:0.75rem;">🏠</span>
                Dashboard
            </a>

            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mt-6 mb-4">Management</p>
                         <!-- Inventory Link -->
            <a href="Inventory.php" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-indigo-600 rounded-lg transition-colors duration-150">
                <span style="font-size:1.3rem; margin-right:0.75rem;">📚</span>
                Course
            </a>
        
            <a href="student.php" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-indigo-600 rounded-lg mb-2 transition-colors duration-150">
                <span style="font-size:1.3rem; margin-right:0.75rem;">🎓</span>
                Student_List
            </a>
            <!-- Add Books Link -->
            <a href="Add_Books.php" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-indigo-600 rounded-lg mb-2 transition-colors duration-150">
                <span style="font-size:1.3rem; margin-right:0.75rem;">📘</span>
                Add Books
            </a>

            <!-- Borrowers Link -->
            <a href="Borrow.php" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-indigo-600 rounded-lg mb-2 transition-colors duration-150">
                <span style="font-size:1.3rem; margin-right:0.75rem;">👥</span>
                Borrowers
            </a>

            <!-- Returns Link -->
            <a href="Return.php" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-indigo-600 rounded-lg mb-2 transition-colors duration-150">
                <span style="font-size:1.3rem; margin-right:0.75rem;">🔄</span>
                Returns
            </a>
<!-- Divider for System Menu -->
<p class="text-xs font-medium text-gray-400 uppercase tracking-wider mt-6 mb-4">System</p>

<!-- Profile Link -->
<a href="profile.php" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-indigo-600 rounded-lg mb-2 transition-colors duration-150">
    <span style="font-size:1.3rem; margin-right:0.75rem;">👤</span>
    Profile
</a>



<!-- Settings Link -->
<a href="setting.php" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-indigo-600 rounded-lg mb-2 transition-colors duration-150">
    <span style="font-size:1.3rem; margin-right:0.75rem;">⚙️</span>
    Settings
</a>

<a href="logout.php" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-red-600 rounded-lg mb-2 transition-colors duration-150">
    <span style="font-size:1.3rem; margin-right:0.75rem;">🚪</span>
    Logout
</a>



        </nav>
        </div>
    </div>
</aside>

<style>

    /* Active state styling */
    .sidebar-link.active {
        background-color: #EEF2FF;
        color: #4F46E5;
    }

    /* Hover state styling */
    .sidebar-link:hover {
        background-color: #F9FAFB;
    }

    /* Transition effects */
    .sidebar-link {
        transition: all 0.2s ease;
    }

    /* Custom scrollbar for sidebar */
    aside {
        scrollbar-width: thin;
        scrollbar-color: #E5E7EB transparent;
    }

    aside::-webkit-scrollbar {
        width: 4px;
    }

    aside::-webkit-scrollbar-track {
        background: transparent;
    }

    aside::-webkit-scrollbar-thumb {
        background-color: #E5E7EB;
        border-radius: 20px;
    }

    #pkay {
        width: 250px;
        /* Example additional styles: */
        /* background: #f8fafc; */
        /* border-right: 2px solid #e5e7eb; */
    }

    nav.flex-1.p-4 {
        background: #f5f3ff; /* Soft pastel lavender for library feel */
        border-radius: 16px;
        margin: 1.2rem 0;
        box-shadow: 0 4px 16px rgba(136, 96, 208, 0.07); /* Subtle purple shadow */
        border: 1.5px solid #e9d8fd; /* Light purple border */
        padding: 2.2rem 1.7rem;
        transition: background 0.3s, box-shadow 0.3s;
    }
    nav.flex-1.p-4:hover {
        background: #ede9fe; /* Slightly deeper pastel on hover */
        box-shadow: 0 8px 24px rgba(136, 96, 208, 0.13);
    }
</style>