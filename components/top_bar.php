<!-- Footer Styles -->
<style>
    /* Footer Styles */
    .footer {
        background: #ffffff; /* Pure White */
        color: #4b3869; /* Deep Purple for main text */
        padding: 3rem 0;
        margin-top: 4rem;
        box-shadow: 0 -4px 20px rgba(75, 56, 105, 0.08); /* Subtle purple shadow */
        border-top: 3px solid #b4aee8; /* Pastel purple border */
        margin-left: 150px;
    }

    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    .footer-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2.5rem;
    }

    .footer-section {
        background: #f7f7fa; /* Very light gray for contrast */
        padding: 1.5rem;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(180, 174, 232, 0.10); /* Soft purple shadow */
        transition: transform 0.3s ease;
    }

    .footer-section:hover {
        transform: translateY(-5px);
    }

    .footer-section h5 {
        color: #6a4caf; /* Stronger purple */
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        position: relative;
        padding-bottom: 0.8rem;
        text-align: center;
    }

    .footer-section h5::after {
        content: '';
        position: absolute;
        left: 50%;
        bottom: 0;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #b4aee8, #fbc2eb); /* Pastel purple to pink */
        border-radius: 3px;
    }

    .footer-section p,
    .footer-links a,
    .footer-contact li {
        color: #5c5470; /* Muted purple-gray */
    }

    .footer-links a {
        background: rgba(174, 214, 241, 0.3); /* Pastel blue */
        color: #4b3869;
        transition: all 0.3s ease;
        display: inline-block;
        padding: 0.3rem 1rem;
        border-radius: 15px;
    }

    .footer-links a:hover {
        color: #fff;
        background: linear-gradient(90deg, #fbc2eb, #b4aee8); /* Pink to purple */
        transform: translateX(5px);
    }

    .footer-bottom {
        text-align: center;
        margin-top: 2rem;
        color: #6a4caf;
        font-weight: 500;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 1rem;
        text-align: center;
    }

    .footer-contact {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-contact li {
        margin-bottom: 1rem;
        text-align: center;
    }

    .footer-contact i {
        margin-right: 0.5rem;
    }

    .social-links {
        display: flex;
        justify-content: center;
        margin-top: 1rem;
    }

    .social-links a {
        color: #4b3869;
        margin: 0 1rem;
        font-size: 1.5rem;
    }
</style>

<!-- Footer -->
<footer class="footer">
    <div class="footer-container">
        <div class="footer-content">
            <!-- Library Information -->
            <div class="footer-section">
                <h5>Library System</h5>
                <p>Your cozy corner for knowledge and learning! 📚✨ We provide access to a wonderful collection of books and resources to spark your imagination.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="footer-section">
                <h5>Quick Links</h5>
                <ul class="footer-links">
                    <li><a href="">Home</a></li>
                    <li><a href="">Book Catalog</a></li>
                    <li><a href="">About Us</a></li>
                    <li><a href="">Contact</a></li>
                    <li><a href="">Services</a></li>
                    <li><a href="">Events</a></li>
                </ul>
            </div>
            
            <!-- Contact Information -->
            <div class="footer-section">
                <h5>Contact Us</h5>
                <ul class="footer-contact">
                    <li><i class="fas fa-phone"></i> (123) 456-7890</li>
                    <li><i class="fas fa-envelope"></i> library@example.com</li>
                    <li><i class="fas fa-map-marker-alt"></i> 123 Library Street, City</li>
                    <li><i class="fas fa-clock"></i> Mon-Fri: 9:00 AM - 6:00 PM</li>
                </ul>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Library System. All rights reserved. 💕</p>
        </div>
    </div>
</footer>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
