    </main>

    <div class="newsletter-section">
        <div class="container">
            <div class="newsletter-content">
                <h3>Anmäl dig till vår nyhetsbrev</h3>
                <form class="newsletter-form">
                    <input type="email" name="newsletter_email" placeholder="Din e-postadress" required>
                    <button type="submit" class="btn btn-newsletter">
                        <svg width="20" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="m4 4 16 8-16 8V4z"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h4>Om Kakelbolaget.se</h4>
                    <p>Kakelbolaget är en av Sveriges ledande kakel- &amp; klinkertillverkare på nätet med snabba leveranser över hela Sverige.</p>
                </div>

                <div class="footer-column">
                    <h4>Kundtjänst</h4>
                    <p>Tveka inte att kontakta oss på info@kakelbolaget.se eller via vår chatt.</p>
                </div>

                <div class="footer-column">
                    <h4>Fler sidor</h4>
                    <ul>
                        <li><a href="/bli-pluskund">Bli Pluskund</a></li>
                        <li><a href="/inspiration">Inspiration</a></li>
                        <li><a href="/om-oss">Om oss</a></li>
                        <li><a href="/kopvillkor">Köpvillkor</a></li>
                        <li><a href="/fragor-svar">Frågor &amp; Svar</a></li>
                        <li><a href="/ordlista">Ordlista</a></li>
                        <li><a href="/kontakt">Kontakta oss</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h4>Sociala medier</h4>
                    <div class="social-links">
                        <a href="#" class="social-link facebook">Facebook</a>
                        <a href="#" class="social-link instagram">Instagram</a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="footer-bottom-left">
                    <p>&copy; <?php echo date('Y'); ?> Kakelbolaget.se</p>
                </div>
                <div class="footer-bottom-right">
                    <div class="payment-methods">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/images/visa.svg'); ?>" alt="Visa">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/images/mastercard.svg'); ?>" alt="Mastercard">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/images/klarna.svg'); ?>" alt="Klarna">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/images/swish.svg'); ?>" alt="Swish">
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div><!-- /.site-wrapper -->

<div id="wall-calculator-modal" class="modal" role="dialog" aria-modal="true" aria-labelledby="wall-calculator-title" hidden>
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="wall-calculator-title">BERÄKNA DIN VÄGG</h2>
            <button type="button" class="close" aria-label="Stäng">&times;</button>
        </div>
        <div class="modal-body">
            <p>Mät både bredden och höjden på varje vägg som du vill klä in. Fyll sedan i båda måtten i kalkylatorn för att räkna ut dina kvadratmeter.</p>

            <div class="calculator-tabs" role="tablist">
                <button class="tab-btn active" data-tab="wall">VÄGG</button>
                <button class="tab-btn" data-tab="floor">GOLV</button>
            </div>

            <div class="calculator-form">
                <div class="input-row">
                    <label for="calc-width">BREDD</label>
                    <label for="calc-height">HÖJD</label>
                    <label for="calc-unit">ENHET</label>
                    <label for="calc-total">TOTAL</label>
                </div>
                <div class="input-row">
                    <input type="number" id="calc-width" min="0" step="0.1">
                    <input type="number" id="calc-height" min="0" step="0.1">
                    <select id="calc-unit">
                        <option value="m">m</option>
                        <option value="cm">cm</option>
                    </select>
                    <input type="number" id="calc-total" readonly>
                </div>
            </div>

            <div class="calculator-result">
                <p>Du behöver: <span id="calc-result">0.00</span>m²</p>
            </div>

            <button class="btn btn-primary" id="use-calculation">ANVÄND DESSA MÅTT</button>
        </div>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
