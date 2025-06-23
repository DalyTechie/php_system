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
        font-family: 'Khmer OS Siemreap', sans-serif; /* Added Khmer font */
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
        font-family: 'Khmer OS Siemreap', sans-serif; /* Added Khmer font */
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
        font-family: 'Khmer OS Siemreap', sans-serif; /* Added Khmer font */
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

<footer class="footer">
    <div class="footer-container">
        <div class="footer-content">
            <div class="footer-section">
                <h5>ប្រព័ន្ធបណ្ណាល័យ</h5>
                <p>កន្លែងដ៏កក់ក្ដៅរបស់អ្នកសម្រាប់ចំណេះដឹង និងការរៀនសូត្រ! 📚✨ យើងផ្តល់ជូននូវបណ្តុំសៀវភៅ និងធនធានដ៏អស្ចារ្យដើម្បីជំរុញការស្រមើលស្រមៃរបស់អ្នក។</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>

            <div class="footer-section">
                <h5>តំណភ្ជាប់រហ័ស</h5>
                <ul class="footer-links">
                    <li><a href="">ទំព័រដើម</a></li>
                    <li><a href="">កាតាឡុកសៀវភៅ</a></li>
                    <li><a href="">អំពីពួកយើង</a></li>
                    <li><a href="">ទំនាក់ទំនង</a></li>
                    <li><a href="">សេវាកម្ម</a></li>
                    <li><a href="">ព្រឹត្តិការណ៍</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h5>ទំនាក់ទំនងយើង</h5>
                <ul class="footer-contact">
                    <li><i class="fas fa-phone"></i> (123) 456-7890</li>
                    <li><i class="fas fa-envelope"></i> library@example.com</li>
                    <li><i class="fas fa-map-marker-alt"></i> ១២៣ ផ្លូវបណ្ណាល័យ, ទីក្រុង</li>
                    <li><i class="fas fa-clock"></i> ច័ន្ទ-សុក្រ: ៩:០០ ព្រឹក - ៦:០០ ល្ងាច</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> ប្រព័ន្ធបណ្ណាល័យ។ រក្សាសិទ្ធិគ្រប់យ៉ាង។ 💕</p>
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>