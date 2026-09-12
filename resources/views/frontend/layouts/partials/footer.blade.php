{{-- eBay-style footer --}}
<style>
.eb-footer {
    background: #fff;
    border-top: 2px solid #e5e5e5;
    font-family: "Market Sans", Arial, sans-serif;
    color: #111;
    padding: 40px 0 0;
    margin-top: 0;
    clear: both;
}
.eb-footer__grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 0 16px;
    padding-bottom: 32px;
    border-bottom: 1px solid #e5e5e5;
}
@media (max-width: 1199px) {
    .eb-footer__grid { grid-template-columns: repeat(4, 1fr); }
}
@media (max-width: 767px) {
    .eb-footer__grid { grid-template-columns: repeat(2, 1fr); gap: 24px 16px; }
}
@media (max-width: 479px) {
    .eb-footer__grid { grid-template-columns: 1fr; }
}
.eb-footer__col-title {
    font-size: 14px;
    font-weight: 700;
    color: #111;
    margin: 0 0 12px;
    letter-spacing: 0;
}
.eb-footer__links {
    list-style: none;
    margin: 0;
    padding: 0;
}
.eb-footer__links li { margin-bottom: 8px; }
.eb-footer__links a {
    font-size: 13px;
    color: #555;
    text-decoration: none;
    line-height: 1.4;
    transition: color .15s;
}
.eb-footer__links a:hover { color: #111; text-decoration: underline; }
.eb-footer__divider {
    border: none;
    border-top: 1px solid #e5e5e5;
    margin: 20px 0 12px;
}
.eb-footer__social-links {
    list-style: none;
    margin: 0;
    padding: 0;
}
.eb-footer__social-links li { margin-bottom: 8px; }
.eb-footer__social-links a {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #555;
    text-decoration: none;
    transition: color .15s;
}
.eb-footer__social-links a:hover { color: #111; }
.eb-footer__social-icon {
    width: 20px;
    height: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #f0f0f0;
    font-size: 11px;
    color: #333;
    flex-shrink: 0;
}
.eb-footer__sites-select {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 1px solid #d0d0d0;
    border-radius: 24px;
    padding: 8px 14px;
    font-size: 13px;
    font-weight: 600;
    color: #111;
    cursor: pointer;
    background: #fff;
    transition: border-color .15s, box-shadow .15s;
    text-decoration: none;
}
.eb-footer__sites-select:hover {
    border-color: #999;
    box-shadow: 0 0 0 3px rgba(0,0,0,.07);
    color: #111;
    text-decoration: none;
}
.eb-footer__flag {
    width: 22px;
    height: 16px;
    border-radius: 2px;
    background: linear-gradient(180deg, #b22234 33%, #fff 33%, #fff 66%, #3c3b6e 66%);
    display: inline-block;
}
.eb-footer__bottom {
    padding: 20px 0;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 6px 16px;
    font-size: 12px;
    color: #555;
}
.eb-footer__bottom a {
    color: #555;
    text-decoration: underline;
    transition: color .15s;
}
.eb-footer__bottom a:hover { color: #111; }
.eb-footer__adchoice {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.eb-footer__adchoice-icon {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #3665f3;
    color: #fff;
    font-size: 9px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
</style>

<footer class="eb-footer">
    <div class="container">
        <div class="eb-footer__grid">

            {{-- Buy --}}
            <div class="eb-footer__col">
                <h4 class="eb-footer__col-title">Buy</h4>
                <ul class="eb-footer__links">
                    <li><a href="#">Registration</a></li>
                    <li><a href="#">Bidding &amp; buying help</a></li>
                    <li><a href="#">Stores</a></li>
                    <li><a href="#">eBay for Charity</a></li>
                    <li><a href="#">Charity Shop</a></li>
                    <li><a href="#">Seasonal Sales &amp; events</a></li>
                </ul>
            </div>

            {{-- Sell --}}
            <div class="eb-footer__col">
                <h4 class="eb-footer__col-title">Sell</h4>
                <ul class="eb-footer__links">
                    <li><a href="#">Start selling</a></li>
                    <li><a href="#">How to sell</a></li>
                    <li><a href="#">Business sellers</a></li>
                    <li><a href="#">Affiliates</a></li>
                </ul>
            </div>

            {{-- Tools & Apps --}}
            <div class="eb-footer__col">
                <h4 class="eb-footer__col-title">Tools &amp; apps</h4>
                <ul class="eb-footer__links">
                    <li><a href="#">Developers</a></li>
                    <li><a href="#">Security center</a></li>
                    <li><a href="#">Site map</a></li>
                </ul>
            </div>

            {{-- eBay Companies + Stay Connected --}}
            <div class="eb-footer__col">
                <h4 class="eb-footer__col-title">eBay companies</h4>
                <ul class="eb-footer__links">
                    <li><a href="#">TCGplayer</a></li>
                </ul>

                <hr class="eb-footer__divider">

                <h4 class="eb-footer__col-title">Stay connected</h4>
                <ul class="eb-footer__social-links">
                    <li>
                        <a href="#" target="_blank">
                            <span class="eb-footer__social-icon"><i class="fab fa-facebook-f"></i></span>
                            Facebook
                        </a>
                    </li>
                    <li>
                        <a href="#" target="_blank">
                            <span class="eb-footer__social-icon"><i class="fab fa-x-twitter"></i></span>
                            X (Twitter)
                        </a>
                    </li>
                    <li>
                        <a href="#" target="_blank">
                            <span class="eb-footer__social-icon"><i class="fab fa-instagram"></i></span>
                            Instagram
                        </a>
                    </li>
                </ul>
            </div>

            {{-- About eBay --}}
            <div class="eb-footer__col">
                <h4 class="eb-footer__col-title">About eBay</h4>
                <ul class="eb-footer__links">
                    <li><a href="#">Company info</a></li>
                    <li><a href="#">News</a></li>
                    <li><a href="#">Investors</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Diversity &amp; Inclusion</a></li>
                    <li><a href="#">Global Impact</a></li>
                    <li><a href="#">Government relations</a></li>
                    <li><a href="#">Advertise with us</a></li>
                    <li><a href="#">Policies</a></li>
                    <li><a href="#">Product Safety Tips</a></li>
                </ul>
            </div>

            {{-- Help & Contact + Community --}}
            <div class="eb-footer__col">
                <h4 class="eb-footer__col-title">Help &amp; Contact</h4>
                <ul class="eb-footer__links">
                    <li><a href="#">Seller Center</a></li>
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="#">Returns</a></li>
                    <li><a href="#">Money Back Guarantee</a></li>
                </ul>

                <hr class="eb-footer__divider">

                <h4 class="eb-footer__col-title">Community</h4>
                <ul class="eb-footer__links">
                    <li><a href="#">Announcements</a></li>
                    <li><a href="#">eBay Community</a></li>
                    <li><a href="#">eBay for Business Podcast</a></li>
                </ul>
            </div>

            {{-- eBay Sites --}}
            <div class="eb-footer__col">
                <h4 class="eb-footer__col-title">eBay Sites</h4>
                <a href="#" class="eb-footer__sites-select">
                    <span class="eb-footer__flag"></span>
                    United States
                    <i class="fas fa-chevron-down" style="font-size:10px; margin-left:2px;"></i>
                </a>
            </div>

        </div>

        {{-- Bottom copyright bar --}}
        <div class="eb-footer__bottom">
            <span>Copyright &copy; 1995-{{ date('Y') }} eBay Inc. All Rights Reserved.</span>
            <a href="#">Accessibility</a>
            <a href="#">User Agreement</a>
            <a href="#">Privacy</a>
            <a href="#">Payments Terms of Use</a>
            <a href="#">Cookies</a>
            <a href="#">CA Privacy Notice</a>
            <a href="#">Your Privacy Choices</a>
            <span>and</span>
            <a href="#" class="eb-footer__adchoice">
                AdChoice
                <span class="eb-footer__adchoice-icon">i</span>
            </a>
        </div>
    </div>
</footer>
