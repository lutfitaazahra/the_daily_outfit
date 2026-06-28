<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <h3>The Daily Outfit</h3>
                <p>Your everyday style, elevated.<br>Fashion yang nyaman untuk setiap momen.</p>
                <div class="footer-social">
                    <a href="#">📸</a>
                    <a href="#">🎵</a>
                    <a href="#">💬</a>
                </div>
            </div>
            <div class="footer-links">
                <h4>Shop</h4>
                <ul>
                    <li><a href="{{ route('shop', ['category' => 'tops']) }}">Tops</a></li>
                    <li><a href="{{ route('shop', ['category' => 'bottoms']) }}">Bottoms</a></li>
                    <li><a href="{{ route('shop', ['category' => 'dresses']) }}">Dresses</a></li>
                    <li><a href="{{ route('shop', ['category' => 'outerwear']) }}">Outerwear</a></li>
                    <li><a href="{{ route('shop', ['category' => 'accessories']) }}">Accessories</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Bantuan</h4>
                <ul>
                    <li><a href="#">Panduan Ukuran</a></li>
                    <li><a href="#">Kebijakan Return</a></li>
                    <li><a href="#">Cara Pemesanan</a></li>
                    <li><a href="#">Kontak Kami</a></li>
                </ul>
            </div>
            <div class="footer-contact">
                <h4>Kontak</h4>
                <p>📧 hello@dailyoutfit.id</p>
                <p>📱 0812-3456-7890</p>
                <p>📍 Wonosobo, Jawa Tengah</p>
                <p>🕐 Senin–Sabtu 09.00–21.00</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2024 The Daily Outfit. All rights reserved.</p>
            <div class="payment-icons">
                <span>BCA</span>
                <span>Mandiri</span>
                <span>GoPay</span>
                <span>OVO</span>
                <span>DANA</span>
            </div>
        </div>
    </div>
</footer>