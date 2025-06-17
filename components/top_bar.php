<!-- Footer Styles -->
<style>
    .footer {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        color: #495057;
        padding: 3rem 0;
        margin-top: 4rem;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.05);
        border-top: 3px solid #ffd6e0;
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
        background: rgba(255, 255, 255, 0.7);
        padding: 1.5rem;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
    }

    .footer-section:hover {
        transform: translateY(-5px);
    }

    .footer-section h5 {
        color: #ff6b6b;
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
        background: linear-gradient(90deg, #ff6b6b, #ffd6e0);
        border-radius: 3px;
    }

    .footer-section p {
        color: #6c757d;
        line-height: 1.8;
        margin-bottom: 1.2rem;
        text-align: center;
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

    .footer-links a {
        color: #6c757d;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
        padding: 0.3rem 1rem;
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.5);
    }

    .footer-links a:hover {
        color: #ff6b6b;
        background: rgba(255, 107, 107, 0.1);
        transform: translateX(5px);
    }

    .footer-contact li {
        color: #6c757d;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.5rem;
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.5);
        transition: all 0.3s ease;
    }

    .footer-contact li:hover {
        background: rgba(255, 107, 107, 0.1);
        transform: translateX(5px);
    }

    .footer-contact i {
        color: #ff6b6b;
        margin-right: 10px;
        width: 20px;
        font-size: 1.1rem;
    }

    .footer-bottom {
        margin-top: 2.5rem;
        padding-top: 2rem;
        border-top: 2px solid #ffd6e0;
        text-align: center;
    }

    .footer-bottom p {
        color: #6c757d;
        font-size: 0.95rem;
        font-weight: 500;
    }

    .social-links {
        display: flex;
        gap: 1.2rem;
        margin-top: 1.5rem;
        justify-content: center;
    }

    .social-links a {
        color: #6c757d;
        font-size: 1.4rem;
        transition: all 0.3s ease;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 50%;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .social-links a:hover {
        color: #ff6b6b;
        background: rgba(255, 107, 107, 0.1);
        transform: translateY(-5px);
    }

    @media (max-width: 768px) {
        .footer-content {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .footer-section {
            padding: 1.2rem;
        }

        .footer-section h5 {
            font-size: 1.2rem;
        }

        .footer-links a:hover,
        .footer-contact li:hover {
            transform: none;
        }

        .social-links {
            gap: 1rem;
        }

        .social-links a {
            width: 35px;
            height: 35px;
            font-size: 1.2rem;
        }
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
